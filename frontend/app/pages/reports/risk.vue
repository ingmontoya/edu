<script setup lang="ts">
import type { RiskScore } from '~/types/school'
import type { AiAnalysis } from '~/composables/useReports'

definePageMeta({
  middleware: 'auth'
})

const academicStore = useAcademicStore()
const { getRiskScores, getAiStudentAnalysis } = useReports()
const toast = useToast()

const loading = ref(false)
const initializing = ref(true)
const selectedGroup = ref<number | undefined>(undefined)
const selectedPeriod = ref<number | undefined>(undefined)
const searchQuery = ref('')
const selectedLevel = ref<string | undefined>(undefined)

const students = ref<RiskScore[]>([])
const summary = ref({ total: 0, high_risk: 0, medium_risk: 0, low_risk: 0, average_score: 0 })

const currentPage = ref(1)
const perPage = 15

// AI Modal state
const aiModalOpen = ref(false)
const aiModalStudent = ref<RiskScore | null>(null)
const aiLoading = ref(false)
const aiCache = reactive(new Map<number, AiAnalysis>())

// Rotating loader messages
const loaderMessages = [
  'Consultando el historial académico...',
  'Analizando señales de riesgo con IA...',
  'Evaluando patrones de asistencia...',
  'Procesando registros de convivencia...',
  'Generando narrativa pedagógica...',
  'Identificando asignaturas críticas...',
  'Elaborando recomendaciones personalizadas...',
  'Revisando tendencias del período anterior...',
  'Finalizando el informe...'
]
const loaderMessageIndex = ref(0)
let loaderInterval: ReturnType<typeof setInterval> | null = null

function startLoaderMessages() {
  loaderMessageIndex.value = 0
  loaderInterval = setInterval(() => {
    loaderMessageIndex.value = (loaderMessageIndex.value + 1) % loaderMessages.length
  }, 2200)
}

function stopLoaderMessages() {
  if (loaderInterval) {
    clearInterval(loaderInterval)
    loaderInterval = null
  }
}

const groupItems = computed(() => [
  { value: undefined, label: 'Todos los grupos' },
  ...academicStore.groups.map(g => ({ value: g.id, label: g.full_name || g.name }))
])

const periodItems = computed(() =>
  academicStore.periods.map(p => ({ value: p.id, label: p.name }))
)

const levelOptions = [
  { value: undefined, label: 'Todos los niveles' },
  { value: 'high', label: 'Riesgo Alto' },
  { value: 'medium', label: 'Riesgo Moderado' },
  { value: 'low', label: 'Sin Riesgo' }
]

const riskConfig = {
  high: { label: 'Riesgo Alto', color: 'error' as const, bg: 'bg-red-500' },
  medium: { label: 'Riesgo Moderado', color: 'warning' as const, bg: 'bg-yellow-500' },
  low: { label: 'Sin Riesgo', color: 'success' as const, bg: 'bg-green-500' }
}

const filteredStudents = computed(() => {
  let list = students.value
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    list = list.filter(s => s.student.name.toLowerCase().includes(q))
  }
  if (selectedLevel.value) {
    list = list.filter(s => s.level === selectedLevel.value)
  }
  return list
})

const totalPages = computed(() => Math.ceil(filteredStudents.value.length / perPage))

const pagedStudents = computed(() => {
  const start = (currentPage.value - 1) * perPage
  return filteredStudents.value.slice(start, start + perPage)
})

const trendIcon = (trend: number | null) => {
  if (trend === null) return null
  if (trend > 0.1) return { icon: 'i-lucide-trending-up', color: 'text-success' }
  if (trend < -0.1) return { icon: 'i-lucide-trending-down', color: 'text-error' }
  return { icon: 'i-lucide-minus', color: 'text-muted' }
}

const fetchData = async () => {
  if (!selectedPeriod.value) return
  loading.value = true
  try {
    const res = await getRiskScores(selectedPeriod.value, selectedGroup.value)
    students.value = res.students
    summary.value = res.summary
    currentPage.value = 1
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo cargar el índice de riesgo', color: 'error' })
  } finally {
    loading.value = false
  }
}

const openAiModal = async (item: RiskScore) => {
  aiModalStudent.value = item
  aiModalOpen.value = true

  if (aiCache.has(item.student_id)) return
  if (!selectedPeriod.value) return

  aiLoading.value = true
  startLoaderMessages()

  try {
    const result = await getAiStudentAnalysis(item.student_id, selectedPeriod.value)
    aiCache.set(item.student_id, result)
  } catch {
    toast.add({ title: 'Error IA', description: 'No se pudo generar el análisis', color: 'error' })
  } finally {
    stopLoaderMessages()
    aiLoading.value = false
  }
}

const currentAiData = computed(() => {
  if (!aiModalStudent.value) return null
  return aiCache.get(aiModalStudent.value.student_id) ?? null
})

onMounted(async () => {
  await Promise.all([academicStore.fetchGroups(), academicStore.fetchPeriods()])
  if (academicStore.activePeriod) {
    selectedPeriod.value = academicStore.activePeriod.id
    await fetchData()
  }
  initializing.value = false
})

watch([selectedPeriod, selectedGroup], fetchData)
watch([searchQuery, selectedLevel], () => {
  currentPage.value = 1
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Índice de Riesgo Estudiantil">
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
          </div>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div v-if="initializing" class="flex items-center justify-center py-24">
        <UIcon name="i-lucide-loader-2" class="w-8 h-8 animate-spin text-primary" />
      </div>

      <div v-else-if="!selectedPeriod" class="flex flex-col items-center justify-center py-24 gap-3">
        <UIcon name="i-lucide-bar-chart-3" class="w-12 h-12 text-muted" />
        <p class="text-muted">
          Seleccione un periodo para calcular el índice de riesgo
        </p>
      </div>

      <div v-else class="flex flex-col gap-4 p-6">
        <!-- Summary cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
          <UPageCard variant="subtle" class="text-center">
            <p class="text-3xl font-bold">
              {{ summary.total }}
            </p>
            <p class="text-sm text-muted mt-1">
              Total estudiantes
            </p>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <p class="text-3xl font-bold text-error">
              {{ summary.high_risk }}
            </p>
            <p class="text-sm text-muted mt-1">
              Riesgo alto
            </p>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <p class="text-3xl font-bold text-warning">
              {{ summary.medium_risk }}
            </p>
            <p class="text-sm text-muted mt-1">
              Riesgo moderado
            </p>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <p class="text-3xl font-bold text-success">
              {{ summary.low_risk }}
            </p>
            <p class="text-sm text-muted mt-1">
              Sin riesgo
            </p>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <p class="text-3xl font-bold text-primary">
              {{ summary.average_score }}
            </p>
            <p class="text-sm text-muted mt-1">
              Score promedio
            </p>
          </UPageCard>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-3">
          <UInput v-model="searchQuery" placeholder="Buscar estudiante..." class="w-56">
            <template #leading>
              <UIcon name="i-lucide-search" class="w-4 h-4 text-muted" />
            </template>
          </UInput>
          <USelectMenu
            v-model="selectedLevel"
            :items="levelOptions"
            value-key="value"
            placeholder="Nivel de riesgo"
            class="w-48"
          />
        </div>

        <!-- Loading -->
        <div v-if="loading" class="flex items-center justify-center py-16">
          <UIcon name="i-lucide-loader-2" class="w-6 h-6 animate-spin text-primary" />
        </div>

        <!-- Empty state -->
        <UPageCard v-else-if="filteredStudents.length === 0" variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-shield-check" class="w-12 h-12 text-success mx-auto mb-3" />
            <p class="font-medium">
              Sin resultados
            </p>
            <p class="text-sm text-muted mt-1">
              {{ students.length === 0 ? 'No hay estudiantes en este periodo' : 'Ningún resultado para los filtros aplicados' }}
            </p>
          </div>
        </UPageCard>

        <!-- Student cards -->
        <template v-else>
          <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
            <UPageCard
              v-for="item in pagedStudents"
              :key="item.student_id"
              variant="subtle"
              :ui="{ body: 'p-0' }"
            >
              <!-- Header -->
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
                  <UBadge :color="riskConfig[item.level].color" variant="soft" size="sm">
                    {{ riskConfig[item.level].label }}
                  </UBadge>
                  <span
                    class="text-xs font-mono font-bold"
                    :class="{
                      'text-error': item.level === 'high',
                      'text-warning': item.level === 'medium',
                      'text-success': item.level === 'low'
                    }"
                  >Score {{ item.score }}/100</span>
                </div>
              </div>

              <!-- Score bar -->
              <div class="px-4 pb-2">
                <div class="h-1.5 rounded-full bg-muted/30 overflow-hidden">
                  <div
                    class="h-full rounded-full transition-all"
                    :class="riskConfig[item.level].bg"
                    :style="{ width: `${item.score}%` }"
                  />
                </div>
              </div>

              <!-- Signals -->
              <div class="border-t border-default px-4 py-3 grid grid-cols-2 gap-x-4 gap-y-1.5">
                <div class="flex items-center gap-1.5 text-xs">
                  <UIcon name="i-lucide-book-open" class="w-3.5 h-3.5 shrink-0" :class="item.signals.failing_subjects > 0 ? 'text-error' : 'text-muted'" />
                  <span>
                    <span :class="item.signals.failing_subjects > 0 ? 'text-error font-medium' : 'text-muted'">{{ item.signals.failing_subjects }}</span>
                    <span class="text-muted">/{{ item.signals.total_subjects }} reprobadas</span>
                  </span>
                </div>
                <div class="flex items-center gap-1.5 text-xs">
                  <UIcon name="i-lucide-calendar" class="w-3.5 h-3.5 shrink-0" :class="item.signals.attendance_pct < 80 ? 'text-error' : 'text-muted'" />
                  <span :class="item.signals.attendance_pct < 80 ? 'text-error font-medium' : 'text-muted'">
                    {{ item.signals.attendance_pct }}% asistencia
                  </span>
                </div>
                <div class="flex items-center gap-1.5 text-xs">
                  <UIcon name="i-lucide-shield" class="w-3.5 h-3.5 shrink-0" :class="item.signals.disciplinary_incidents > 0 ? 'text-error' : 'text-muted'" />
                  <span :class="item.signals.disciplinary_incidents > 0 ? 'text-error font-medium' : 'text-muted'">
                    {{ item.signals.disciplinary_incidents }} convivencia
                  </span>
                </div>
                <div class="flex items-center gap-1.5 text-xs">
                  <UIcon name="i-lucide-clipboard-list" class="w-3.5 h-3.5 shrink-0" :class="item.signals.pending_remedials > 0 ? 'text-warning' : 'text-muted'" />
                  <span :class="item.signals.pending_remedials > 0 ? 'text-warning font-medium' : 'text-muted'">
                    {{ item.signals.pending_remedials }} nivelaciones
                  </span>
                </div>
                <div v-if="item.signals.grade_trend !== null" class="flex items-center gap-1.5 text-xs col-span-2">
                  <UIcon
                    :name="trendIcon(item.signals.grade_trend)?.icon ?? 'i-lucide-minus'"
                    class="w-3.5 h-3.5 shrink-0"
                    :class="trendIcon(item.signals.grade_trend)?.color"
                  />
                  <span :class="trendIcon(item.signals.grade_trend)?.color">
                    Tendencia {{ item.signals.grade_trend > 0 ? '+' : '' }}{{ item.signals.grade_trend.toFixed(2) }} vs período anterior
                  </span>
                </div>
              </div>

              <!-- AI Analyze button -->
              <div class="border-t border-default px-4 py-2.5">
                <UButton
                  icon="i-lucide-brain"
                  label="Analizar con IA"
                  color="primary"
                  variant="ghost"
                  size="xs"
                  block
                  @click="openAiModal(item)"
                />
              </div>
            </UPageCard>
          </div>

          <!-- Pagination -->
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

  <!-- AI Analysis Modal -->
  <UModal
    v-model:open="aiModalOpen"
    :title="aiModalStudent ? `Análisis IA — ${aiModalStudent.student.name}` : 'Análisis IA'"
    size="xl"
    :ui="{ content: 'max-w-4xl' }"
  >
    <template #body>
      <div v-if="aiModalStudent" class="flex flex-col gap-5">
        <!-- Unified loading state -->
        <div v-if="aiLoading" class="flex flex-col items-center justify-center gap-4 py-10">
          <div class="relative w-14 h-14">
            <svg
              class="w-14 h-14 animate-spin text-primary/20"
              viewBox="0 0 56 56"
              fill="none"
            >
              <circle
                cx="28"
                cy="28"
                r="24"
                stroke="currentColor"
                stroke-width="4"
              />
            </svg>
            <svg
              class="absolute inset-0 w-14 h-14 animate-spin text-primary"
              viewBox="0 0 56 56"
              fill="none"
              style="animation-duration: 1.2s"
            >
              <circle
                cx="28"
                cy="28"
                r="24"
                stroke="currentColor"
                stroke-width="4"
                stroke-dasharray="40 112"
                stroke-linecap="round"
              />
            </svg>
            <UIcon name="i-lucide-brain" class="absolute inset-0 m-auto w-5 h-5 text-primary" />
          </div>
          <Transition name="fade-msg" mode="out-in">
            <p :key="loaderMessageIndex" class="text-sm text-muted text-center font-medium">
              {{ loaderMessages[loaderMessageIndex] }}
            </p>
          </Transition>
          <div class="flex gap-1.5 mt-1">
            <span
              v-for="(_, i) in loaderMessages"
              :key="i"
              class="w-1.5 h-1.5 rounded-full transition-all duration-300"
              :class="i === loaderMessageIndex ? 'bg-primary scale-125' : 'bg-muted/40'"
            />
          </div>
        </div>

        <!-- Results -->
        <template v-else>
          <!-- Narrative section -->
          <div>
            <h3 class="font-semibold text-sm mb-2 flex items-center gap-1.5">
              <UIcon name="i-lucide-file-text" class="w-4 h-4 text-primary" />
              Narrativa de situación
            </h3>
            <div v-if="currentAiData?.narrative" class="text-sm leading-relaxed text-default bg-muted/20 rounded-lg p-3">
              {{ currentAiData.narrative }}
            </div>
            <div v-else class="text-sm text-muted italic">
              No se pudo generar la narrativa.
            </div>
          </div>

          <!-- Recommendations section -->
          <div>
            <h3 class="font-semibold text-sm mb-2 flex items-center gap-1.5">
              <UIcon name="i-lucide-lightbulb" class="w-4 h-4 text-warning" />
              Recomendaciones pedagógicas
            </h3>
            <div v-if="currentAiData?.recommendations.length" class="flex flex-col gap-2">
              <div
                v-for="(rec, idx) in currentAiData.recommendations"
                :key="idx"
                class="rounded-lg border border-default p-3 bg-muted/10"
              >
                <p class="font-medium text-sm text-primary mb-1">
                  {{ rec.subject }}
                </p>
                <p class="text-xs text-muted mb-1">
                  <span class="font-medium text-default">Estrategia:</span> {{ rec.strategy }}
                </p>
                <p class="text-xs text-muted">
                  <span class="font-medium text-default">Actividad:</span> {{ rec.activity }}
                </p>
              </div>
            </div>
            <div v-else class="text-sm text-muted italic">
              {{ aiModalStudent.signals.failing_subjects === 0 ? 'El estudiante no tiene asignaturas reprobadas.' : 'No se pudieron generar recomendaciones.' }}
            </div>
          </div>
        </template>
      </div>
    </template>
    <template #footer>
      <UButton
        label="Cerrar"
        color="neutral"
        variant="outline"
        @click="aiModalOpen = false"
      />
    </template>
  </UModal>
</template>

<style scoped>
.fade-msg-enter-active,
.fade-msg-leave-active {
  transition: opacity 0.4s ease, transform 0.4s ease;
}
.fade-msg-enter-from {
  opacity: 0;
  transform: translateY(6px);
}
.fade-msg-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}
</style>
