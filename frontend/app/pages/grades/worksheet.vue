<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const academicStore = useAcademicStore()
const { getWorksheet } = useGrades()
const toast = useToast()

const loading = ref(true)
const loadingData = ref(false)
const selectedGroup = ref<number | undefined>(undefined)
const selectedPeriod = ref<number | undefined>(undefined)
const worksheetData = ref<any>(null)
const isFullscreen = ref(false)
const tableContainer = ref<HTMLElement | null>(null)

const toggleFullscreen = async () => {
  if (!tableContainer.value) return

  if (!document.fullscreenElement) {
    await tableContainer.value.requestFullscreen()
    isFullscreen.value = true
  } else {
    await document.exitFullscreen()
    isFullscreen.value = false
  }
}


// Transform items for USelectMenu
const groupItems = computed(() =>
  academicStore.groups.map(g => ({ value: g.id, label: g.full_name || g.name }))
)
const periodItems = computed(() =>
  academicStore.periods.map(p => ({ value: p.id, label: p.name }))
)

const fetchWorksheet = async () => {
  if (!selectedGroup.value || !selectedPeriod.value) {
    worksheetData.value = null
    return
  }

  loadingData.value = true
  try {
    const data = await getWorksheet(selectedGroup.value, selectedPeriod.value)
    worksheetData.value = data
  } catch (error: any) {
    console.error('Error fetching worksheet:', error)
    toast.add({ title: 'Error', description: error?.message || 'No se pudo cargar la planilla', color: 'error' })
    worksheetData.value = null
  } finally {
    loadingData.value = false
  }
}

const getGradeClass = (grade: number | string | null | undefined) => {
  if (grade === null || grade === undefined) return 'text-muted'
  const num = typeof grade === 'string' ? parseFloat(grade) : grade
  if (isNaN(num)) return 'text-muted'
  if (num >= 4.6) return 'text-green-600 dark:text-green-400 font-semibold'
  if (num >= 4.0) return 'text-blue-600 dark:text-blue-400'
  if (num >= 3.0) return 'text-yellow-600 dark:text-yellow-400'
  return 'text-red-600 dark:text-red-400 font-semibold'
}

const formatGrade = (grade: number | string | null | undefined) => {
  if (grade === null || grade === undefined) return '-'
  const num = typeof grade === 'string' ? parseFloat(grade) : grade
  if (isNaN(num)) return '-'
  return num.toFixed(1)
}

onMounted(async () => {
  // Listener para detectar salida de fullscreen con ESC
  document.addEventListener('fullscreenchange', () => {
    isFullscreen.value = !!document.fullscreenElement
  })

  await Promise.all([
    academicStore.fetchGroups(),
    academicStore.fetchPeriods()
  ])

  if (academicStore.activePeriod) {
    selectedPeriod.value = academicStore.activePeriod.id
  }

  loading.value = false
})

watch([selectedGroup, selectedPeriod], () => {
  fetchWorksheet()
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Planilla de Notas">
        <template #leading>
          <UDashboardSidebarCollapse />
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

        <!-- Loading -->
        <UPageCard v-if="loadingData" variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-loader-2" class="w-12 h-12 text-primary mx-auto mb-4 animate-spin" />
            <p class="text-muted">Cargando planilla...</p>
          </div>
        </UPageCard>

        <!-- Worksheet Table -->
        <div
          v-else-if="worksheetData && worksheetData.worksheet?.length"
          ref="tableContainer"
          class="relative"
          :class="{ 'bg-white dark:bg-gray-900 p-4': isFullscreen }"
        >
          <UPageCard variant="subtle">
            <!-- Header con botón fullscreen -->
            <div class="flex items-center justify-between mb-4">
              <div>
                <h3 class="font-semibold">{{ worksheetData.group?.full_name }}</h3>
                <p class="text-sm text-muted">{{ worksheetData.period?.name }} - {{ worksheetData.worksheet.length }} estudiantes</p>
              </div>
              <UButton
                :icon="isFullscreen ? 'i-lucide-minimize-2' : 'i-lucide-maximize-2'"
                :label="isFullscreen ? 'Salir' : 'Pantalla completa'"
                color="neutral"
                variant="outline"
                size="sm"
                @click="toggleFullscreen"
              />
            </div>

            <div class="overflow-x-auto" :class="{ 'max-h-[calc(100vh-150px)] overflow-y-auto': isFullscreen }">
            <table class="w-full text-sm border-collapse">
              <thead>
                <tr class="bg-gray-50 dark:bg-gray-800">
                  <th class="text-left p-2 font-medium sticky left-0 bg-gray-50 dark:bg-gray-800 z-10 border-b align-bottom">#</th>
                  <th class="text-left p-2 font-medium sticky left-8 bg-gray-50 dark:bg-gray-800 z-10 min-w-[180px] border-b align-bottom">Estudiante</th>
                  <th
                    v-for="subject in worksheetData.subjects"
                    :key="subject.id"
                    class="border-b p-1 align-bottom"
                    :title="subject.name"
                    style="min-width: 45px; max-width: 45px;"
                  >
                    <div class="flex justify-center">
                      <span
                        class="text-xs font-medium whitespace-nowrap"
                        style="writing-mode: vertical-lr; transform: rotate(180deg); max-height: 100px; overflow: hidden; text-overflow: ellipsis;"
                      >
                        {{ subject.name }}
                      </span>
                    </div>
                  </th>
                  <th
                    class="border-b p-1 bg-gray-100 dark:bg-gray-700 align-bottom"
                    style="min-width: 45px; max-width: 45px;"
                  >
                    <div class="flex justify-center">
                      <span
                        class="text-xs font-bold whitespace-nowrap"
                        style="writing-mode: vertical-lr; transform: rotate(180deg);"
                      >
                        Prom.
                      </span>
                    </div>
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(student, index) in worksheetData.worksheet"
                  :key="student.student_id"
                  class="border-b hover:bg-gray-50 dark:hover:bg-gray-800"
                >
                  <td class="p-2 text-muted sticky left-0 bg-white dark:bg-gray-900 text-xs">{{ index + 1 }}</td>
                  <td class="p-2 font-medium sticky left-8 bg-white dark:bg-gray-900 text-xs truncate max-w-[180px]">
                    {{ student.student_name }}
                  </td>
                  <td
                    v-for="subject in worksheetData.subjects"
                    :key="subject.id"
                    class="p-1 text-center text-xs"
                    :class="getGradeClass(student.grades[subject.id])"
                  >
                    {{ formatGrade(student.grades[subject.id]) }}
                  </td>
                  <td
                    class="p-1 text-center font-semibold bg-gray-50 dark:bg-gray-800 text-xs"
                    :class="getGradeClass(student.average)"
                  >
                    {{ formatGrade(student.average) }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

            <!-- Legend -->
            <div class="mt-4 pt-4 border-t flex flex-wrap gap-4 text-sm">
              <span class="flex items-center gap-2">
                <span class="w-3 h-3 rounded bg-green-500" />
                Superior (4.6 - 5.0)
              </span>
              <span class="flex items-center gap-2">
                <span class="w-3 h-3 rounded bg-blue-500" />
                Alto (4.0 - 4.5)
              </span>
              <span class="flex items-center gap-2">
                <span class="w-3 h-3 rounded bg-yellow-500" />
                Básico (3.0 - 3.9)
              </span>
              <span class="flex items-center gap-2">
                <span class="w-3 h-3 rounded bg-red-500" />
                Bajo (1.0 - 2.9)
              </span>
            </div>
          </UPageCard>
        </div>

        <!-- Empty State -->
        <UPageCard v-else-if="!loading && selectedGroup && selectedPeriod && !loadingData" variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-file-x" class="w-12 h-12 text-muted mx-auto mb-4" />
            <p class="text-muted">No hay notas registradas para este grupo y periodo</p>
          </div>
        </UPageCard>

        <!-- Instructions -->
        <UPageCard v-else-if="!loading && !loadingData" variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-table" class="w-12 h-12 text-primary mx-auto mb-4" />
            <h3 class="text-lg font-semibold mb-2">Planilla de Notas</h3>
            <p class="text-muted">Seleccione un grupo y periodo para ver la planilla completa de notas</p>
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
