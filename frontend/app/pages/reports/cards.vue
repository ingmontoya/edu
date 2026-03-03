<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const academicStore = useAcademicStore()
const { downloadReportCardPdf, downloadBulkReportCardsPdf } = useReports()
const { getStudents } = useAcademic()
const toast = useToast()

const loading = ref(true)
const downloading = ref(false)
const selectedGroup = ref<number | undefined>(undefined)
const selectedPeriod = ref<number | undefined>(undefined)
const students = ref<any[]>([])

// Colores vibrantes para los avatares
const avatarColors = [
  'bg-red-500',
  'bg-orange-500',
  'bg-amber-500',
  'bg-yellow-500',
  'bg-lime-500',
  'bg-green-500',
  'bg-emerald-500',
  'bg-teal-500',
  'bg-cyan-500',
  'bg-sky-500',
  'bg-blue-500',
  'bg-indigo-500',
  'bg-violet-500',
  'bg-purple-500',
  'bg-fuchsia-500',
  'bg-pink-500',
  'bg-rose-500'
]

const getAvatarColor = (name: string | undefined): string => {
  if (!name) return avatarColors[0]
  let hash = 0
  for (let i = 0; i < name.length; i++) {
    hash = name.charCodeAt(i) + ((hash << 5) - hash)
  }
  return avatarColors[Math.abs(hash) % avatarColors.length]
}

const getInitials = (name: string | undefined): string => {
  if (!name) return '?'
  const parts = name.trim().split(' ')
  if (parts.length >= 2) {
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  }
  return name.substring(0, 2).toUpperCase()
}

// Transform items for USelectMenu (workaround for label-key bug)
const groupItems = computed(() =>
  academicStore.groups.map(g => ({ value: g.id, label: g.full_name || g.name }))
)
const periodItems = computed(() =>
  academicStore.periods.map(p => ({ value: p.id, label: p.name }))
)

const fetchStudents = async () => {
  if (!selectedGroup.value) {
    students.value = []
    return
  }

  loading.value = true
  try {
    const response = await getStudents({ group_id: selectedGroup.value, status: 'active' })
    students.value = response.data || []
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron cargar los estudiantes', color: 'error' })
  } finally {
    loading.value = false
  }
}

const downloadSingle = async (studentId: number) => {
  if (!selectedPeriod.value) {
    toast.add({ title: 'Atención', description: 'Seleccione un período', color: 'warning' })
    return
  }

  downloading.value = true
  try {
    await downloadReportCardPdf(studentId, selectedPeriod.value)
    toast.add({ title: 'Éxito', description: 'Boletín descargado', color: 'primary' })
  } catch (error: any) {
    console.error('Download error:', error)
    toast.add({
      title: 'Error',
      description: error?.message || 'No se pudo descargar el boletín',
      color: 'error'
    })
  } finally {
    downloading.value = false
  }
}

const downloadAll = async () => {
  if (!selectedGroup.value || !selectedPeriod.value) {
    toast.add({ title: 'Atención', description: 'Seleccione grupo y período', color: 'warning' })
    return
  }

  downloading.value = true
  try {
    await downloadBulkReportCardsPdf(selectedGroup.value, selectedPeriod.value)
    toast.add({ title: 'Éxito', description: 'Boletines descargados', color: 'primary' })
  } catch (error: any) {
    console.error('Bulk download error:', error)
    toast.add({
      title: 'Error',
      description: error?.message || 'No se pudieron descargar los boletines',
      color: 'error'
    })
  } finally {
    downloading.value = false
  }
}

onMounted(async () => {
  await Promise.all([
    academicStore.fetchGroups(),
    academicStore.fetchPeriods()
  ])

  if (academicStore.activePeriod) {
    selectedPeriod.value = academicStore.activePeriod.id
  }

  loading.value = false
})

watch(selectedGroup, () => {
  fetchStudents()
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Boletines">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>

        <template #right>
          <UButton
            icon="i-lucide-download"
            label="Descargar Todos"
            color="primary"
            :loading="downloading"
            :disabled="!selectedGroup || !selectedPeriod || !students.length"
            @click="downloadAll"
          />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Selectors -->
        <UPageCard variant="subtle">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <UFormField label="Grupo" required>
              <USelectMenu
                v-model="selectedGroup"
                :items="groupItems"
                value-key="value"
                placeholder="Seleccionar grupo"
              />
            </UFormField>

            <UFormField label="Periodo" required>
              <USelectMenu
                v-model="selectedPeriod"
                :items="periodItems"
                value-key="value"
                placeholder="Seleccionar periodo"
              />
            </UFormField>
          </div>
        </UPageCard>

        <!-- Students List -->
        <UPageCard v-if="students.length" title="Estudiantes" variant="subtle">
          <template #header>
            <div class="flex items-center justify-between">
              <h3 class="font-semibold">Estudiantes ({{ students.length }})</h3>
            </div>
          </template>

          <div class="divide-y">
            <div
              v-for="student in students"
              :key="student.id"
              class="flex items-center justify-between py-3"
            >
              <div class="flex items-center gap-3">
                <div
                  :class="[getAvatarColor(student.user?.name), 'w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-semibold shadow-sm']"
                >
                  {{ getInitials(student.user?.name) }}
                </div>
                <div>
                  <p class="font-medium">{{ student.user?.name }}</p>
                  <p class="text-sm text-muted">{{ student.user?.document_type }} {{ student.user?.document_number }}</p>
                </div>
              </div>
              <UButton
                icon="i-lucide-file-down"
                label="Descargar"
                color="primary"
                variant="soft"
                size="sm"
                :loading="downloading"
                :disabled="!selectedPeriod"
                @click="downloadSingle(student.id)"
              />
            </div>
          </div>
        </UPageCard>

        <!-- Empty State -->
        <UPageCard v-else-if="!loading && selectedGroup" variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-users" class="w-12 h-12 text-muted mx-auto mb-4" />
            <p class="text-muted">No hay estudiantes en este grupo</p>
          </div>
        </UPageCard>

        <!-- Instructions -->
        <UPageCard v-else-if="!loading" variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-file-text" class="w-12 h-12 text-primary mx-auto mb-4" />
            <h3 class="text-lg font-semibold mb-2">Generación de Boletines</h3>
            <p class="text-muted">Seleccione un grupo y periodo para generar los boletines en PDF</p>
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
