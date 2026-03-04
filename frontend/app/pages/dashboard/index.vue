<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const academicStore = useAcademicStore()
const authStore = useAuthStore()
const { getAiWeeklySummary } = useReports()
const toast = useToast()

const loading = ref(true)
const aiSummary = ref<string | null>(null)
const aiSummaryLoading = ref(false)
const aiSummaryGenerated = ref(false)

const summaryLoaderMessages = [
  'Recopilando datos del período activo...',
  'Calculando índices de riesgo estudiantil...',
  'Identificando grupos prioritarios...',
  'Analizando asignaturas con más dificultades...',
  'Procesando patrones de asistencia...',
  'Consultando registros de convivencia...',
  'Generando análisis con IA...',
  'Redactando recomendaciones institucionales...',
  'Finalizando el informe ejecutivo...'
]
const summaryLoaderIndex = ref(0)
let summaryLoaderInterval: ReturnType<typeof setInterval> | null = null

function startSummaryLoader() {
  summaryLoaderIndex.value = 0
  summaryLoaderInterval = setInterval(() => {
    summaryLoaderIndex.value = (summaryLoaderIndex.value + 1) % summaryLoaderMessages.length
  }, 2200)
}

function stopSummaryLoader() {
  if (summaryLoaderInterval) {
    clearInterval(summaryLoaderInterval)
    summaryLoaderInterval = null
  }
}

onMounted(async () => {
  await academicStore.fetchAll()
  loading.value = false
})

const generateAiSummary = async () => {
  if (!academicStore.activePeriod) {
    toast.add({ title: 'Sin período activo', description: 'Active un período para generar el resumen', color: 'warning' })
    return
  }
  aiSummaryLoading.value = true
  startSummaryLoader()
  try {
    const res = await getAiWeeklySummary(academicStore.activePeriod.id)
    aiSummary.value = res.summary
    aiSummaryGenerated.value = true
  } catch {
    toast.add({ title: 'Error IA', description: 'No se pudo generar el resumen semanal', color: 'error' })
  } finally {
    stopSummaryLoader()
    aiSummaryLoading.value = false
  }
}

const stats = computed(() => ({
  students: academicStore.groups.reduce((sum, g) => sum + (g.students?.length || 0), 0),
  groups: academicStore.groups.length,
  grades: academicStore.grades.length,
  activePeriod: academicStore.activePeriod?.name || 'Sin periodo activo'
}))

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('es-CO', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Inicio">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Welcome Card -->
        <UPageCard variant="subtle">
          <div>
            <h2 class="text-xl font-semibold">
              Bienvenido a <span class="text-primary">Aula360</span>
            </h2>
            <p class="text-muted">
              Panel de control del sistema de gestión académica
            </p>
          </div>
        </UPageCard>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <UPageCard variant="subtle">
            <div class="flex items-center gap-4">
              <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                <UIcon name="i-lucide-users" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
              </div>
              <div>
                <p class="text-2xl font-bold">
                  {{ stats.students }}
                </p>
                <p class="text-sm text-muted">
                  Estudiantes
                </p>
              </div>
            </div>
          </UPageCard>

          <UPageCard variant="subtle">
            <div class="flex items-center gap-4">
              <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                <UIcon name="i-lucide-layout-grid" class="w-6 h-6 text-purple-600 dark:text-purple-400" />
              </div>
              <div>
                <p class="text-2xl font-bold">
                  {{ stats.groups }}
                </p>
                <p class="text-sm text-muted">
                  Grupos
                </p>
              </div>
            </div>
          </UPageCard>

          <UPageCard variant="subtle">
            <div class="flex items-center gap-4">
              <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl">
                <UIcon name="i-lucide-graduation-cap" class="w-6 h-6 text-green-600 dark:text-green-400" />
              </div>
              <div>
                <p class="text-2xl font-bold">
                  {{ stats.grades }}
                </p>
                <p class="text-sm text-muted">
                  Grados
                </p>
              </div>
            </div>
          </UPageCard>

          <UPageCard variant="subtle">
            <div class="flex items-center gap-4">
              <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                <UIcon name="i-lucide-calendar" class="w-6 h-6 text-orange-600 dark:text-orange-400" />
              </div>
              <div>
                <p class="text-lg font-bold">
                  {{ stats.activePeriod }}
                </p>
                <p class="text-sm text-muted">
                  Periodo Actual
                </p>
              </div>
            </div>
          </UPageCard>
        </div>

        <!-- Quick Actions -->
        <UPageCard title="Acciones Rápidas" variant="subtle">
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <UButton
              to="/students/new"
              icon="i-lucide-user-plus"
              label="Nuevo Estudiante"
              color="neutral"
              variant="outline"
              block
            />
            <UButton
              to="/grades/record"
              icon="i-lucide-file-edit"
              label="Registrar Notas"
              color="neutral"
              variant="outline"
              block
            />
            <UButton
              to="/attendance"
              icon="i-lucide-calendar-check"
              label="Tomar Asistencia"
              color="neutral"
              variant="outline"
              block
            />
            <UButton
              to="/reports/cards"
              icon="i-lucide-file-text"
              label="Generar Boletines"
              color="neutral"
              variant="outline"
              block
            />
          </div>
        </UPageCard>

        <!-- AI Weekly Summary (admin/coordinator only) -->
        <UPageCard v-if="authStore.isAdmin || authStore.isCoordinator" variant="subtle">
          <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
              <UIcon name="i-lucide-brain" class="w-5 h-5 text-primary" />
              <h3 class="font-semibold">
                Análisis Semanal IA
              </h3>
            </div>
            <UButton
              :icon="aiSummaryLoading ? 'i-lucide-loader-2' : 'i-lucide-sparkles'"
              :label="aiSummaryGenerated ? 'Regenerar' : 'Generar'"
              :loading="aiSummaryLoading"
              color="primary"
              variant="soft"
              size="sm"
              :disabled="aiSummaryLoading"
              @click="generateAiSummary"
            />
          </div>
          <div v-if="aiSummaryLoading" class="flex flex-col items-center gap-3 py-6">
            <div class="relative w-10 h-10">
              <svg
                class="w-10 h-10 animate-spin text-primary/20"
                viewBox="0 0 40 40"
                fill="none"
              >
                <circle
                  cx="20"
                  cy="20"
                  r="17"
                  stroke="currentColor"
                  stroke-width="3"
                />
              </svg>
              <svg
                class="absolute inset-0 w-10 h-10 animate-spin text-primary"
                viewBox="0 0 40 40"
                fill="none"
                style="animation-duration: 1.2s"
              >
                <circle
                  cx="20"
                  cy="20"
                  r="17"
                  stroke="currentColor"
                  stroke-width="3"
                  stroke-dasharray="28 78"
                  stroke-linecap="round"
                />
              </svg>
              <UIcon name="i-lucide-brain" class="absolute inset-0 m-auto w-4 h-4 text-primary" />
            </div>
            <Transition name="fade-msg" mode="out-in">
              <p :key="summaryLoaderIndex" class="text-sm text-muted text-center font-medium">
                {{ summaryLoaderMessages[summaryLoaderIndex] }}
              </p>
            </Transition>
            <div class="flex gap-1.5">
              <span
                v-for="(_, i) in summaryLoaderMessages"
                :key="i"
                class="w-1.5 h-1.5 rounded-full transition-all duration-300"
                :class="i === summaryLoaderIndex ? 'bg-primary scale-125' : 'bg-muted/40'"
              />
            </div>
          </div>
          <div v-else-if="aiSummary" class="text-sm leading-relaxed text-default">
            {{ aiSummary }}
          </div>
          <p v-else class="text-sm text-muted">
            Genera un resumen ejecutivo de la situación académica actual del colegio basado en el período activo.
          </p>
        </UPageCard>

        <!-- Active Year Info -->
        <UPageCard v-if="academicStore.activeYear" title="Año Académico Activo" variant="subtle">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <p class="text-sm text-muted">
                Año
              </p>
              <p class="font-semibold">
                {{ academicStore.activeYear.name }}
              </p>
            </div>
            <div>
              <p class="text-sm text-muted">
                Fecha Inicio
              </p>
              <p class="font-semibold">
                {{ formatDate(academicStore.activeYear.start_date) }}
              </p>
            </div>
            <div>
              <p class="text-sm text-muted">
                Fecha Fin
              </p>
              <p class="font-semibold">
                {{ formatDate(academicStore.activeYear.end_date) }}
              </p>
            </div>
          </div>

          <div v-if="academicStore.activeYear.periods?.length" class="mt-6">
            <p class="text-sm text-muted mb-3">
              Periodos
            </p>
            <div class="flex flex-wrap gap-2">
              <UBadge
                v-for="period in academicStore.activeYear.periods"
                :key="period.id"
                :color="period.is_closed ? 'neutral' : 'primary'"
                variant="subtle"
              >
                {{ period.name }} ({{ period.weight }}%)
              </UBadge>
            </div>
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
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
