<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const route = useRoute()
const api = useApi()
const toast = useToast()
const { getPerformanceColor, getPerformanceLabel } = useGrades()

const loading = ref(true)
const student = ref<any>(null)
const grades = ref<any[]>([])
const attendance = ref<any>(null)
const selectedPeriod = ref<number | undefined>(undefined)
const periods = ref<any[]>([])

const periodItems = computed(() =>
  periods.value.map(p => ({ value: p.id, label: p.name }))
)

const fetchStudent = async () => {
  try {
    const data = await api.get<any>(`/guardian/students/${route.params.id}`)
    student.value = data.student
    periods.value = data.periods || []
    if (data.active_period) {
      selectedPeriod.value = data.active_period.id
    }
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo cargar la información del estudiante', color: 'error' })
  } finally {
    loading.value = false
  }
}

const fetchGrades = async () => {
  if (!selectedPeriod.value) return

  try {
    const data = await api.get<any[]>(`/guardian/students/${route.params.id}/grades?period_id=${selectedPeriod.value}`)
    grades.value = data
  } catch (error) {
    console.error('Error fetching grades:', error)
  }
}

const fetchAttendance = async () => {
  if (!selectedPeriod.value) return

  try {
    const data = await api.get<any>(`/guardian/students/${route.params.id}/attendance?period_id=${selectedPeriod.value}`)
    attendance.value = data
  } catch (error) {
    console.error('Error fetching attendance:', error)
  }
}

const average = computed(() => {
  const validGrades = grades.value.filter(g => g.grade !== null).map(g => parseFloat(g.grade))
  if (validGrades.length === 0) return null
  return (validGrades.reduce((a, b) => a + b, 0) / validGrades.length).toFixed(1)
})

onMounted(() => {
  fetchStudent()
})

watch(selectedPeriod, () => {
  fetchGrades()
  fetchAttendance()
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar :title="student?.user?.name || 'Estudiante'">
        <template #leading>
          <UButton
            icon="i-lucide-arrow-left"
            color="neutral"
            variant="ghost"
            to="/guardian"
          />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Loading -->
        <div v-if="loading" class="text-center py-12">
          <UIcon name="i-lucide-loader-2" class="w-8 h-8 text-primary animate-spin mx-auto" />
        </div>

        <template v-else-if="student">
          <!-- Student Info -->
          <UPageCard variant="subtle">
            <div class="flex items-center gap-4">
              <UAvatar :alt="student.user?.name" size="xl" />
              <div>
                <h2 class="text-xl font-semibold">{{ student.user?.name }}</h2>
                <p class="text-muted">{{ student.group?.full_name }}</p>
                <p class="text-sm text-muted">Documento: {{ student.user?.document_type }} {{ student.user?.document_number }}</p>
              </div>
            </div>
          </UPageCard>

          <!-- Period Selector -->
          <UPageCard variant="subtle">
            <UFormField label="Periodo">
              <USelectMenu
                v-model="selectedPeriod"
                :items="periodItems"
                value-key="value"
                placeholder="Seleccionar periodo"
                class="w-full md:w-64"
              />
            </UFormField>
          </UPageCard>

          <!-- Stats -->
          <div v-if="selectedPeriod" class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <UPageCard variant="subtle" class="text-center">
              <p class="text-3xl font-bold" :class="average ? getPerformanceColor(parseFloat(average)) === 'success' ? 'text-green-500' : getPerformanceColor(parseFloat(average)) === 'error' ? 'text-red-500' : 'text-primary' : ''">
                {{ average || '-' }}
              </p>
              <p class="text-sm text-muted">Promedio</p>
            </UPageCard>
            <UPageCard variant="subtle" class="text-center">
              <p class="text-3xl font-bold">{{ grades.filter(g => g.grade !== null).length }}</p>
              <p class="text-sm text-muted">Asignaturas</p>
            </UPageCard>
            <UPageCard variant="subtle" class="text-center">
              <p class="text-3xl font-bold text-green-500">{{ attendance?.present || 0 }}</p>
              <p class="text-sm text-muted">Asistencias</p>
            </UPageCard>
            <UPageCard variant="subtle" class="text-center">
              <p class="text-3xl font-bold text-red-500">{{ attendance?.absent || 0 }}</p>
              <p class="text-sm text-muted">Faltas</p>
            </UPageCard>
          </div>

          <!-- Grades Table -->
          <UPageCard v-if="selectedPeriod && grades.length" title="Calificaciones" variant="subtle">
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="border-b">
                    <th class="text-left p-3 font-medium">Asignatura</th>
                    <th class="text-left p-3 font-medium">Área</th>
                    <th class="text-center p-3 font-medium">Nota</th>
                    <th class="text-center p-3 font-medium">Desempeño</th>
                    <th class="text-left p-3 font-medium">Observaciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="grade in grades" :key="grade.subject_id" class="border-b">
                    <td class="p-3 font-medium">{{ grade.subject_name }}</td>
                    <td class="p-3 text-muted">{{ grade.area_name }}</td>
                    <td class="p-3 text-center">
                      <span
                        v-if="grade.grade"
                        class="font-semibold"
                        :class="{
                          'text-green-600': grade.grade >= 4.6,
                          'text-blue-600': grade.grade >= 4.0 && grade.grade < 4.6,
                          'text-yellow-600': grade.grade >= 3.0 && grade.grade < 4.0,
                          'text-red-600': grade.grade < 3.0
                        }"
                      >
                        {{ parseFloat(grade.grade).toFixed(1) }}
                      </span>
                      <span v-else class="text-muted">-</span>
                    </td>
                    <td class="p-3 text-center">
                      <UBadge
                        v-if="grade.grade"
                        :color="getPerformanceColor(grade.grade)"
                        variant="subtle"
                      >
                        {{ getPerformanceLabel(grade.grade) }}
                      </UBadge>
                    </td>
                    <td class="p-3 text-muted text-sm">{{ grade.observations || '-' }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </UPageCard>

          <!-- Empty grades -->
          <UPageCard v-else-if="selectedPeriod" variant="subtle">
            <div class="text-center py-8">
              <UIcon name="i-lucide-file-text" class="w-10 h-10 text-muted mx-auto mb-2" />
              <p class="text-muted">No hay calificaciones registradas para este periodo</p>
            </div>
          </UPageCard>
        </template>
      </div>
    </template>
  </UDashboardPanel>
</template>
