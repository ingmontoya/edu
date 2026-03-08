<script setup lang="ts">
import type { Subject } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const academicStore = useAcademicStore()
const institution = useInstitutionStore()
const { getSubjects, createSubject, updateSubject, deleteSubject, getAreas } = useAcademic()
const toast = useToast()

const loading = ref(true)
const showModal = ref(false)
const editingSubject = ref<Subject | null>(null)
const areas = ref<any[]>([])
const subjects = ref<Subject[]>([])
const selectedGrade = ref<number | null>(null)
const selectedArea = ref<number | null>(null)
const searchQuery = ref('')
const currentPage = ref(1)
const perPage = ref(20)
const totalItems = ref(0)
const totalPages = ref(1)

const formData = ref({
  grade_id: undefined as number | undefined,
  area_id: undefined as number | undefined,
  name: '',
  intensity_hours: 4,
  credits: 3
})

const gradeFilterItems = computed(() => [
  { value: null, label: 'Todos los grados' },
  ...academicStore.grades.map(g => ({ value: g.id, label: g.name }))
])
const gradeItems = computed(() =>
  academicStore.grades.map(g => ({ value: g.id, label: g.name }))
)
const areaFilterItems = computed(() => [
  { value: null, label: 'Todas las áreas' },
  ...areas.value.map(a => ({ value: a.id, label: a.name }))
])
const areaItems = computed(() => [
  { value: null, label: 'Sin área' },
  ...areas.value.map(a => ({ value: a.id, label: a.name }))
])

const perPageOptions = [
  { value: 20, label: '20 por página' },
  { value: 50, label: '50 por página' },
  { value: 100, label: '100 por página' }
]

const columns = computed(() => {
  const cols: { accessorKey: string, header: string }[] = [
    { accessorKey: 'name', header: 'Asignatura' },
    { accessorKey: 'area', header: 'Área' },
    { accessorKey: 'grade', header: 'Grado' },
    { accessorKey: 'weekly_hours', header: 'Hrs/Sem' }
  ]
  if (institution.isHigherEd) {
    cols.push({ accessorKey: 'credits', header: 'Créditos' })
  }
  cols.push({ accessorKey: 'actions', header: '' })
  return cols
})

const fetchSubjects = async () => {
  loading.value = true
  try {
    const params: any = { page: currentPage.value, per_page: perPage.value }
    if (selectedGrade.value) params.grade_id = selectedGrade.value
    if (selectedArea.value) params.area_id = selectedArea.value
    if (searchQuery.value) params.search = searchQuery.value
    const response = await getSubjects(params)
    subjects.value = response.data
    totalItems.value = response.meta.total
    totalPages.value = response.meta.last_page
  } catch {
    toast.add({ title: 'Error', description: 'No se pudieron cargar las asignaturas', color: 'error' })
  } finally {
    loading.value = false
  }
}

const goToPage = (page: number) => {
  if (page >= 1 && page <= totalPages.value) currentPage.value = page
}

const debouncedSearch = useDebounceFn(() => {
  currentPage.value = 1
  fetchSubjects()
}, 300)

const openCreate = () => {
  editingSubject.value = null
  formData.value = {
    grade_id: selectedGrade.value ?? undefined,
    area_id: selectedArea.value ?? undefined,
    name: '',
    intensity_hours: 4,
    credits: 3
  }
  showModal.value = true
}

const openEdit = (subject: Subject) => {
  editingSubject.value = subject
  formData.value = {
    grade_id: subject.grade_id,
    area_id: subject.area_id,
    name: subject.name,
    intensity_hours: subject.intensity_hours || 4,
    credits: subject.credits ?? 3
  }
  showModal.value = true
}

const handleSave = async () => {
  if (!formData.value.name || !formData.value.grade_id) {
    toast.add({ title: 'Error', description: 'Complete los campos requeridos', color: 'error' })
    return
  }
  try {
    if (editingSubject.value) {
      await updateSubject(editingSubject.value.id, formData.value)
      toast.add({ title: 'Éxito', description: 'Asignatura actualizada', color: 'primary' })
    } else {
      await createSubject(formData.value)
      toast.add({ title: 'Éxito', description: 'Asignatura creada', color: 'primary' })
    }
    showModal.value = false
    fetchSubjects()
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo guardar la asignatura', color: 'error' })
  }
}

const handleDelete = async (subject: Subject) => {
  if (!confirm(`¿Está seguro que desea eliminar la asignatura ${subject.name}?`)) return
  try {
    await deleteSubject(subject.id)
    toast.add({ title: 'Éxito', description: 'Asignatura eliminada', color: 'primary' })
    fetchSubjects()
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo eliminar la asignatura', color: 'error' })
  }
}

onMounted(async () => {
  await academicStore.fetchGrades()
  try {
    areas.value = await getAreas()
  } catch (error) {
    console.error('Failed to load areas:', error)
  }
  await fetchSubjects()
})

watch([selectedGrade, selectedArea], () => {
  currentPage.value = 1
  fetchSubjects()
})
watch(currentPage, fetchSubjects)
watch(perPage, () => { currentPage.value = 1; fetchSubjects() })
watch(searchQuery, debouncedSearch)
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Asignaturas">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>

        <template #right>
          <div class="flex items-center gap-2">
            <UInput
              v-model="searchQuery"
              placeholder="Buscar..."
              class="w-44"
            >
              <template #leading>
                <UIcon name="i-lucide-search" class="w-4 h-4 text-muted" />
              </template>
            </UInput>
            <USelectMenu
              v-model="selectedGrade"
              :items="gradeFilterItems"
              value-key="value"
              placeholder="Grado"
              class="w-36"
            />
            <USelectMenu
              v-model="selectedArea"
              :items="areaFilterItems"
              value-key="value"
              placeholder="Área"
              class="w-36"
            />
            <UButton
              icon="i-lucide-plus"
              label="Nueva"
              color="primary"
              @click="openCreate"
            />
          </div>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col h-full">
        <UTable
          :columns="columns"
          :data="subjects"
          :loading="loading"
          :ui="{ tr: 'cursor-pointer hover:bg-elevated/50' }"
          @select="(_e, row) => openEdit(row.original)"
        >
          <template #area-cell="{ row }">
            <UBadge v-if="row.original.area" variant="subtle">
              {{ row.original.area?.name }}
            </UBadge>
            <span v-else class="text-muted">—</span>
          </template>

          <template #grade-cell="{ row }">
            {{ row.original.grade?.name || '—' }}
          </template>

          <template #weekly_hours-cell="{ row }">
            {{ row.original.weekly_hours || '—' }} hrs
          </template>

          <template #credits-cell="{ row }">
            {{ row.original.credits ?? '—' }}
          </template>

          <template #actions-cell="{ row }">
            <div class="flex gap-2 justify-end">
              <UButton
                icon="i-lucide-edit"
                color="neutral"
                variant="ghost"
                size="xs"
                @click.stop="openEdit(row.original)"
              />
              <UButton
                icon="i-lucide-trash"
                color="error"
                variant="ghost"
                size="xs"
                @click.stop="handleDelete(row.original)"
              />
            </div>
          </template>
        </UTable>

        <!-- Pagination -->
        <div class="flex items-center justify-between px-4 py-3 border-t border-default">
          <div class="flex items-center gap-3">
            <span class="text-sm text-muted">{{ totalItems }} asignaturas</span>
            <USelectMenu
              v-model="perPage"
              :items="perPageOptions"
              value-key="value"
              class="w-36"
            />
          </div>
          <div class="flex items-center gap-1">
            <UButton
              icon="i-lucide-chevrons-left"
              color="neutral"
              variant="ghost"
              size="sm"
              :disabled="currentPage === 1"
              @click="goToPage(1)"
            />
            <UButton
              icon="i-lucide-chevron-left"
              color="neutral"
              variant="ghost"
              size="sm"
              :disabled="currentPage === 1"
              @click="goToPage(currentPage - 1)"
            />
            <span class="text-sm px-3">{{ currentPage }} / {{ totalPages }}</span>
            <UButton
              icon="i-lucide-chevron-right"
              color="neutral"
              variant="ghost"
              size="sm"
              :disabled="currentPage === totalPages"
              @click="goToPage(currentPage + 1)"
            />
            <UButton
              icon="i-lucide-chevrons-right"
              color="neutral"
              variant="ghost"
              size="sm"
              :disabled="currentPage === totalPages"
              @click="goToPage(totalPages)"
            />
          </div>
        </div>
      </div>
    </template>
  </UDashboardPanel>

  <!-- Modal -->
  <UModal v-model:open="showModal">
    <template #content>
      <UCard>
        <template #header>
          <h3 class="font-semibold">
            {{ editingSubject ? 'Editar Asignatura' : 'Nueva Asignatura' }}
          </h3>
        </template>

        <div class="space-y-4">
          <UFormField label="Nombre" required>
            <UInput v-model="formData.name" placeholder="Ej: Matemáticas, Español..." />
          </UFormField>

          <UFormField label="Grado" required>
            <USelectMenu
              v-model="formData.grade_id"
              :items="gradeItems"
              value-key="value"
              placeholder="Seleccionar grado"
            />
          </UFormField>

          <UFormField label="Área">
            <USelectMenu
              v-model="formData.area_id"
              :items="areaItems"
              value-key="value"
              placeholder="Seleccionar área"
            />
          </UFormField>

          <UFormField label="Intensidad horaria (hrs/semana)">
            <UInput
              v-model="formData.intensity_hours"
              type="number"
              min="1"
              max="20"
            />
          </UFormField>

          <UFormField v-if="institution.isHigherEd" label="Créditos académicos">
            <UInput
              v-model="formData.credits"
              type="number"
              min="1"
              max="10"
            />
          </UFormField>
        </div>

        <template #footer>
          <div class="flex gap-2 justify-end">
            <UButton color="neutral" variant="ghost" @click="showModal = false">
              Cancelar
            </UButton>
            <UButton color="primary" @click="handleSave">
              {{ editingSubject ? 'Guardar' : 'Crear' }}
            </UButton>
          </div>
        </template>
      </UCard>
    </template>
  </UModal>
</template>
