<script setup lang="ts">
import type { RemedialActivity } from '~/types/school'

definePageMeta({ middleware: 'auth' })

const academicStore = useAcademicStore()
const { getRemedials, deleteRemedial, getRemedialTypeLabel, getRemedialTypeColor } = useSiee()
const toast = useToast()

// State
const loading = ref(true)
const remedials = ref<RemedialActivity[]>([])
const selectedRemedial = ref<RemedialActivity | null>(null)
const showDeleteModal = ref(false)

// Filters
const selectedSubject = ref<number | undefined>()
const selectedPeriod = ref<number | undefined>()
const selectedType = ref<string | undefined>()
const search = ref('')

// Computed
const subjectItems = computed(() => [
  { value: undefined, label: 'Todas las asignaturas' },
  ...academicStore.subjects.map(s => ({ value: s.id, label: s.name }))
])

const periodItems = computed(() => [
  { value: undefined, label: 'Todos los periodos' },
  ...academicStore.periods.map(p => ({ value: p.id, label: p.name }))
])

const typeItems = [
  { value: undefined, label: 'Todos los tipos' },
  { value: 'recovery', label: 'Recuperacion' },
  { value: 'reinforcement', label: 'Refuerzo' },
  { value: 'leveling', label: 'Nivelacion' }
]

const filteredRemedials = computed(() => {
  let result = remedials.value

  if (selectedSubject.value) {
    result = result.filter(r => r.subject_id === selectedSubject.value)
  }

  if (selectedPeriod.value) {
    result = result.filter(r => r.period_id === selectedPeriod.value)
  }

  if (selectedType.value) {
    result = result.filter(r => r.type === selectedType.value)
  }

  if (search.value) {
    const searchLower = search.value.toLowerCase()
    result = result.filter(r =>
      r.title.toLowerCase().includes(searchLower) ||
      r.description.toLowerCase().includes(searchLower)
    )
  }

  return result
})

// Methods
const fetchRemedials = async () => {
  loading.value = true
  try {
    const params: Record<string, any> = {}
    if (selectedSubject.value) params.subject_id = selectedSubject.value
    if (selectedPeriod.value) params.period_id = selectedPeriod.value
    if (selectedType.value) params.type = selectedType.value

    remedials.value = await getRemedials(params)
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron cargar las actividades', color: 'error' })
  } finally {
    loading.value = false
  }
}

const confirmDelete = (remedial: RemedialActivity) => {
  selectedRemedial.value = remedial
  showDeleteModal.value = true
}

const handleDelete = async () => {
  if (!selectedRemedial.value) return

  try {
    await deleteRemedial(selectedRemedial.value.id)
    toast.add({ title: 'Exito', description: 'Actividad eliminada', color: 'success' })
    showDeleteModal.value = false
    fetchRemedials()
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo eliminar la actividad', color: 'error' })
  }
}

const getStatusStats = (remedial: RemedialActivity) => {
  if (!remedial.student_remedials?.length) return null
  const total = remedial.student_remedials.length
  const completed = remedial.student_remedials.filter(sr => sr.status === 'completed').length
  const passed = remedial.student_remedials.filter(sr => sr.status === 'passed').length
  const failed = remedial.student_remedials.filter(sr => sr.status === 'failed').length
  const pending = remedial.student_remedials.filter(sr => sr.status === 'pending').length

  return { total, completed, passed, failed, pending }
}

// Watchers
watch([selectedSubject, selectedPeriod, selectedType], () => {
  fetchRemedials()
})

// Initialize
onMounted(async () => {
  await Promise.all([
    academicStore.fetchSubjects(),
    academicStore.fetchPeriods()
  ])
  fetchRemedials()
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Actividades de Nivelacion" description="Gestionar actividades de recuperacion y refuerzo">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton
            icon="i-lucide-plus"
            label="Nueva Actividad"
            to="/siee/remedials/new"
          />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Filters -->
        <UPageCard variant="subtle">
          <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <UFormField label="Buscar">
              <UInput
                v-model="search"
                placeholder="Buscar actividad..."
                icon="i-lucide-search"
              />
            </UFormField>

            <UFormField label="Asignatura">
              <USelectMenu
                v-model="selectedSubject"
                :items="subjectItems"
                value-key="value"
              />
            </UFormField>

            <UFormField label="Periodo">
              <USelectMenu
                v-model="selectedPeriod"
                :items="periodItems"
                value-key="value"
              />
            </UFormField>

            <UFormField label="Tipo">
              <USelectMenu
                v-model="selectedType"
                :items="typeItems"
                value-key="value"
              />
            </UFormField>

            <div class="flex items-end">
              <UButton
                variant="soft"
                icon="i-lucide-refresh-cw"
                label="Actualizar"
                @click="fetchRemedials"
              />
            </div>
          </div>
        </UPageCard>

        <!-- Loading -->
        <div v-if="loading" class="flex justify-center py-12">
          <UIcon name="i-lucide-loader-2" class="w-8 h-8 animate-spin text-primary" />
        </div>

        <!-- Remedials Grid -->
        <div v-else-if="filteredRemedials.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <UPageCard
            v-for="remedial in filteredRemedials"
            :key="remedial.id"
            variant="outline"
          >
            <div class="flex flex-col gap-3">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <div class="flex items-center gap-2 mb-1">
                    <UBadge :color="getRemedialTypeColor(remedial.type)" size="xs">
                      {{ getRemedialTypeLabel(remedial.type) }}
                    </UBadge>
                  </div>
                  <h4 class="font-semibold">{{ remedial.title }}</h4>
                </div>
                <UDropdownMenu
                  :items="[
                    [
                      { label: 'Calificar', icon: 'i-lucide-check-square', to: `/siee/remedials/${remedial.id}/grade` },
                      { label: 'Editar', icon: 'i-lucide-edit', to: `/siee/remedials/${remedial.id}/edit` }
                    ],
                    [
                      { label: 'Eliminar', icon: 'i-lucide-trash-2', click: () => confirmDelete(remedial) }
                    ]
                  ]"
                >
                  <UButton icon="i-lucide-more-vertical" variant="ghost" size="xs" />
                </UDropdownMenu>
              </div>

              <p class="text-sm text-muted line-clamp-2">{{ remedial.description }}</p>

              <div class="flex flex-wrap gap-2 text-xs text-muted">
                <span class="flex items-center gap-1">
                  <UIcon name="i-lucide-book" class="w-3 h-3" />
                  {{ remedial.subject?.name || 'Sin asignatura' }}
                </span>
                <span class="flex items-center gap-1">
                  <UIcon name="i-lucide-calendar" class="w-3 h-3" />
                  {{ remedial.period?.name || 'Sin periodo' }}
                </span>
              </div>

              <div class="flex items-center gap-2 text-xs text-muted">
                <UIcon name="i-lucide-clock" class="w-3 h-3" />
                <span>Fecha limite: {{ new Date(remedial.due_date).toLocaleDateString() }}</span>
              </div>

              <!-- Stats -->
              <div v-if="getStatusStats(remedial)" class="grid grid-cols-4 gap-2 pt-2 border-t">
                <div class="text-center">
                  <p class="text-sm font-bold">{{ getStatusStats(remedial)!.total }}</p>
                  <p class="text-xs text-muted">Total</p>
                </div>
                <div class="text-center">
                  <p class="text-sm font-bold text-success-600">{{ getStatusStats(remedial)!.passed }}</p>
                  <p class="text-xs text-muted">Aprobados</p>
                </div>
                <div class="text-center">
                  <p class="text-sm font-bold text-error-600">{{ getStatusStats(remedial)!.failed }}</p>
                  <p class="text-xs text-muted">Reprobados</p>
                </div>
                <div class="text-center">
                  <p class="text-sm font-bold text-warning-600">{{ getStatusStats(remedial)!.pending }}</p>
                  <p class="text-xs text-muted">Pendientes</p>
                </div>
              </div>

              <div class="flex gap-2 pt-2">
                <UButton
                  :to="`/siee/remedials/${remedial.id}/grade`"
                  variant="soft"
                  size="xs"
                  icon="i-lucide-check-square"
                  label="Calificar"
                  class="flex-1"
                />
              </div>
            </div>
          </UPageCard>
        </div>

        <!-- Empty State -->
        <UPageCard v-else variant="subtle">
          <div class="flex flex-col items-center justify-center py-12 text-muted">
            <UIcon name="i-lucide-clipboard-list" class="w-12 h-12 mb-4" />
            <p class="text-lg font-medium mb-2">No hay actividades de nivelacion</p>
            <p class="text-sm mb-4">Cree una nueva actividad para comenzar</p>
            <UButton
              to="/siee/remedials/new"
              icon="i-lucide-plus"
              label="Nueva Actividad"
            />
          </div>
        </UPageCard>
      </div>
    </template>

    <!-- Delete Modal -->
    <UModal v-model:open="showDeleteModal">
      <template #content>
        <UCard>
          <template #header>
            <h3 class="font-semibold">Eliminar Actividad</h3>
          </template>

          <p>Esta seguro de eliminar la actividad "{{ selectedRemedial?.title }}"?</p>
          <p class="text-sm text-muted mt-2">Esta accion no se puede deshacer.</p>

          <template #footer>
            <div class="flex justify-end gap-2">
              <UButton variant="ghost" label="Cancelar" @click="showDeleteModal = false" />
              <UButton color="error" label="Eliminar" @click="handleDelete" />
            </div>
          </template>
        </UCard>
      </template>
    </UModal>
  </UDashboardPanel>
</template>
