<script setup lang="ts">
import type { RiskScore } from '~/types/school'

interface FailingStudent {
  student: { id: number, name: string, group: string }
  failing_subjects: Array<{ subject: string, area: string, grade: number }>
  failing_count: number
}

definePageMeta({
  middleware: 'auth'
})

const academicStore = useAcademicStore()
const { getFailingStudents, getRiskScores } = useReports()
const toast = useToast()

const loading = ref(false)
const initializing = ref(true)
const selectedGroup = ref<number | undefined>(undefined)
const selectedPeriod = ref<number | undefined>(undefined)
const searchQuery = ref('')
const minFailing = ref<number | undefined>(undefined)
const students = ref<FailingStudent[]>([])
const summary = ref({ total_students_failing: 0, total_failing_records: 0 })
const expanded = ref(false)

// Risk scores indexed by student_id for O(1) lookup
const riskScoreMap = ref<Map<number, RiskScore>>(new Map())

const riskConfig = {
  high: { label: 'Riesgo Alto', color: 'error' as const },
  medium: { label: 'Riesgo Moderado', color: 'warning' as const },
  low: { label: 'Sin Riesgo', color: 'success' as const }
}

const currentPage = ref(1)
const perPage = 12

const groupItems = computed(() => [
  { value: undefined, label: 'Todos los grupos' },
  ...academicStore.groups.map(g => ({ value: g.id, label: g.full_name || g.name }))
])
const periodItems = computed(() =>
  academicStore.periods.map(p => ({ value: p.id, label: p.name }))
)
const minFailingOptions = [
  { value: undefined, label: 'Todas' },
  { value: 1, label: '1+' },
  { value: 2, label: '2+' },
  { value: 3, label: '3+' }
]

const filteredStudents = computed(() => {
  let list = students.value
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    list = list.filter(s => s.student.name.toLowerCase().includes(q))
  }
  if (minFailing.value !== undefined) {
    list = list.filter(s => s.failing_count >= minFailing.value!)
  }
  return list
})

const totalPages = computed(() => Math.ceil(filteredStudents.value.length / perPage))

const pagedStudents = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return filteredStudents.value.slice(start, start + perPage)
})

const gradeColor = (grade: number) => {
  if (grade < 2.0) return 'error'
  if (grade < 3.0) return 'warning'
  return 'neutral'
}

const severityColor = (count: number) => {
  if (count >= 3) return 'error'
  if (count === 2) return 'warning'
  return 'neutral'
}

const fetchData = async () => {
  if (!selectedPeriod.value) return
  loading.value = true
  try {
    const [failingRes, riskRes] = await Promise.all([
      getFailingStudents(selectedPeriod.value, selectedGroup.value),
      getRiskScores(selectedPeriod.value, selectedGroup.value)
    ])
    students.value = failingRes.students
    summary.value = failingRes.summary
    riskScoreMap.value = new Map(riskRes.students.map(s => [s.student_id, s]))
    currentPage.value = 1
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo cargar el reporte', color: 'error' })
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  await Promise.all([academicStore.fetchGroups(), academicStore.fetchPeriods()])
  if (academicStore.activePeriod) {
    selectedPeriod.value = academicStore.activePeriod.id
    await fetchData()
  }
  initializing.value = false
})

watch([selectedPeriod, selectedGroup], fetchData)
watch([searchQuery, minFailing], () => {
  currentPage.value = 1
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Estudiantes en Riesgo">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <div class="flex items-center gap-2">
            <USelectMenu
              v-model="selectedPeriod"
              :items="periodItems"
              value-key="value"
              placeholder="Periodo"
              class="w-36"
            />
            <USelectMenu
              v-model="selectedGroup"
              :items="groupItems"
              value-key="value"
              placeholder="Todos los grupos"
              class="w-44"
            />
            <UButton
              to="/reports/risk"
              icon="i-lucide-brain"
              label="Índice IA"
              color="primary"
              variant="soft"
              size="sm"
            />
          </div>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div v-if="initializing" class="flex items-center justify-center py-24">
        <UIcon name="i-lucide-loader-2" class="w-8 h-8 animate-spin text-primary" />
      </div>

      <div v-else-if="!selectedPeriod" class="flex flex-col items-center justify-center py-24 gap-3">
        <UIcon name="i-lucide-calendar" class="w-12 h-12 text-muted" />
        <p class="text-muted">
          Seleccione un periodo para ver los estudiantes en riesgo
        </p>
      </div>

      <div v-else class="flex flex-col gap-4 p-6">
        <!-- Summary -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <UPageCard variant="subtle" class="text-center">
            <p class="text-3xl font-bold text-error">
              {{ summary.total_students_failing }}
            </p>
            <p class="text-sm text-muted mt-1">
              En riesgo
            </p>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <p class="text-3xl font-bold text-warning">
              {{ summary.total_failing_records }}
            </p>
            <p class="text-sm text-muted mt-1">
              Reprobadas total
            </p>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <p class="text-3xl font-bold text-primary">
              {{ filteredStudents.length }}
            </p>
            <p class="text-sm text-muted mt-1">
              Mostrando
            </p>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <p class="text-3xl font-bold">
              {{ summary.total_students_failing > 0 ? (summary.total_failing_records / summary.total_students_failing).toFixed(1) : '0' }}
            </p>
            <p class="text-sm text-muted mt-1">
              Prom. por alumno
            </p>
          </UPageCard>
        </div>

        <!-- Filtros + toggle -->
        <div class="flex flex-wrap items-center justify-between gap-3">
          <div class="flex items-center gap-3">
            <UInput v-model="searchQuery" placeholder="Buscar estudiante..." class="w-56">
              <template #leading>
                <UIcon name="i-lucide-search" class="w-4 h-4 text-muted" />
              </template>
            </UInput>
            <USelectMenu
              v-model="minFailing"
              :items="minFailingOptions"
              value-key="value"
              placeholder="Mín. reprobadas"
              class="w-44"
            />
          </div>
          <UButton
            v-if="filteredStudents.length > 0"
            :icon="expanded ? 'i-lucide-chevron-up' : 'i-lucide-chevron-down'"
            :label="expanded ? 'Colapsar todo' : 'Expandir todo'"
            color="neutral"
            variant="ghost"
            size="sm"
            @click="expanded = !expanded"
          />
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-16">
          <UIcon name="i-lucide-loader-2" class="w-6 h-6 animate-spin text-primary" />
        </div>

        <!-- Sin resultados -->
        <UPageCard v-else-if="filteredStudents.length === 0" variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-check-circle" class="w-12 h-12 text-success mx-auto mb-3" />
            <p class="font-medium">
              Sin estudiantes en riesgo
            </p>
            <p class="text-sm text-muted mt-1">
              {{ searchQuery || minFailing ? 'Ningún resultado para los filtros aplicados' : 'Todos los estudiantes tienen notas aprobatorias' }}
            </p>
          </div>
        </UPageCard>

        <template v-else>
          <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
            <UPageCard
              v-for="item in pagedStudents"
              :key="item.student.id"
              variant="subtle"
              :ui="{ body: 'p-0' }"
            >
              <!-- Header siempre visible -->
              <div class="flex items-center justify-between gap-2 px-4 py-3">
                <div class="min-w-0">
                  <p class="font-semibold truncate">
                    {{ item.student.name }}
                  </p>
                  <p class="text-xs text-muted">
                    {{ item.student.group }}
                  </p>
                </div>
                <div class="flex flex-col items-end gap-1 shrink-0">
                  <UBadge :color="severityColor(item.failing_count)" variant="soft" size="sm">
                    {{ item.failing_count }} {{ item.failing_count === 1 ? 'reprobada' : 'reprobadas' }}
                  </UBadge>
                  <template v-if="riskScoreMap.get(item.student.id)">
                    <NuxtLink
                      to="/reports/risk"
                      class="flex items-center gap-1"
                      :title="`${riskConfig[riskScoreMap.get(item.student.id)!.level].label} — ver índice completo`"
                    >
                      <UBadge
                        :color="riskConfig[riskScoreMap.get(item.student.id)!.level].color"
                        variant="subtle"
                        size="xs"
                      >
                        <UIcon name="i-lucide-brain" class="w-3 h-3 mr-1" />
                        {{ riskScoreMap.get(item.student.id)!.score }}
                      </UBadge>
                    </NuxtLink>
                  </template>
                </div>
              </div>

              <!-- Detalle colapsable globalmente -->
              <div v-if="expanded" class="border-t border-default px-4 py-3 space-y-2">
                <div
                  v-for="fs in item.failing_subjects"
                  :key="fs.subject"
                  class="flex items-center justify-between gap-2 text-sm"
                >
                  <div class="min-w-0">
                    <span class="truncate block">{{ fs.subject }}</span>
                    <span class="text-xs text-muted">{{ fs.area }}</span>
                  </div>
                  <UBadge
                    :color="gradeColor(Number(fs.grade))"
                    variant="subtle"
                    size="sm"
                    class="shrink-0 font-mono"
                  >
                    {{ Number(fs.grade).toFixed(1) }}
                  </UBadge>
                </div>
              </div>
            </UPageCard>
          </div>

          <!-- Paginación -->
          <div v-if="totalPages > 1" class="flex items-center justify-between pt-2">
            <span class="text-sm text-muted">
              {{ (currentPage - 1) * perPage + 1 }}–{{ Math.min(currentPage * perPage, filteredStudents.length) }} de {{ filteredStudents.length }}
            </span>
            <div class="flex items-center gap-1">
              <UButton
                icon="i-lucide-chevron-left"
                color="neutral"
                variant="ghost"
                size="sm"
                :disabled="currentPage === 1"
                @click="currentPage--"
              />
              <span class="text-sm px-3">{{ currentPage }} / {{ totalPages }}</span>
              <UButton
                icon="i-lucide-chevron-right"
                color="neutral"
                variant="ghost"
                size="sm"
                :disabled="currentPage === totalPages"
                @click="currentPage++"
              />
            </div>
          </div>
        </template>
      </div>
    </template>
  </UDashboardPanel>
</template>
