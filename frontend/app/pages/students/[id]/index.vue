<script setup lang="ts">
import type { Student, RiskScore, AiAnalysisRecord, AiAnalysisEvolution } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const route = useRoute()
const { getStudent } = useAcademic()
const academicStore = useAcademicStore()
const authStore = useAuthStore()
const { getRiskScores, getStudentAiAnalyses } = useReports()
const toast = useToast()

const loading = ref(true)
const student = ref<Student | null>(null)
const riskScore = ref<RiskScore | null>(null)

const aiAnalyses = ref<AiAnalysisRecord[]>([])
const aiEvolution = ref<AiAnalysisEvolution | null>(null)
const aiHistoryLoading = ref(false)
const expandedAnalyses = reactive(new Set<number>())

const studentId = computed(() => Number(route.params.id))

const canSeeAiHistory = computed(() => authStore.isAdmin || authStore.isCoordinator)

const statusColors: Record<string, 'primary' | 'secondary' | 'success' | 'info' | 'warning' | 'error' | 'neutral'> = {
  active: 'success',
  inactive: 'neutral',
  withdrawn: 'error',
  graduated: 'info'
}

const statusLabels: Record<string, string> = {
  active: 'Activo',
  inactive: 'Inactivo',
  withdrawn: 'Retirado',
  graduated: 'Graduado'
}

const riskConfig = {
  high: { label: 'Riesgo Alto', color: 'error' as const, bg: 'bg-red-500' },
  medium: { label: 'Riesgo Moderado', color: 'warning' as const, bg: 'bg-yellow-500' },
  low: { label: 'Sin Riesgo', color: 'success' as const, bg: 'bg-green-500' }
}

const riskLevelLabels = {
  high: 'Alto',
  medium: 'Moderado',
  low: 'Bajo'
}

const formatDate = (date?: string) => {
  if (!date) return '-'
  return new Date(date).toLocaleDateString('es-CO', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const formatShortDate = (date: string) => {
  return new Date(date).toLocaleDateString('es-CO', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const toggleAnalysis = (id: number) => {
  if (expandedAnalyses.has(id)) {
    expandedAnalyses.delete(id)
  } else {
    expandedAnalyses.add(id)
  }
}

onMounted(async () => {
  try {
    await academicStore.fetchPeriods()
    student.value = await getStudent(studentId.value)

    const period = academicStore.activePeriod ?? academicStore.periods[0] ?? null
    if (period && student.value?.group_id) {
      try {
        const res = await getRiskScores(period.id, student.value.group_id)
        riskScore.value = res.students.find(s => s.student_id === studentId.value) ?? null
      } catch {
        // Risk score is optional — don't block the page
      }
    }

    if (canSeeAiHistory.value) {
      aiHistoryLoading.value = true
      try {
        const res = await getStudentAiAnalyses(studentId.value)
        aiAnalyses.value = res.analyses
        aiEvolution.value = res.evolution
      } catch {
        // AI history is optional
      } finally {
        aiHistoryLoading.value = false
      }
    }
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo cargar el estudiante', color: 'error' })
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar :title="student?.user?.name || 'Estudiante'">
        <template #leading>
          <UButton
            to="/students"
            color="neutral"
            variant="ghost"
            size="sm"
          >
            <UIcon name="i-lucide-arrow-left" class="w-4 h-4 mr-2" />
            Volver
          </UButton>
        </template>

        <template #right>
          <UButton :to="`/students/${studentId}/edit`" color="primary">
            <UIcon name="i-lucide-edit" class="w-4 h-4 mr-2" />
            Editar
          </UButton>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <div v-if="loading" class="flex items-center justify-center py-12">
          <UIcon name="i-lucide-loader-2" class="w-8 h-8 animate-spin text-primary" />
        </div>

        <template v-else-if="student">
          <!-- Header Card -->
          <UPageCard variant="subtle">
            <div class="flex items-start gap-6">
              <UAvatar :alt="student.user?.name" size="xl" />
              <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                  <h2 class="text-2xl font-bold">
                    {{ student.user?.name }}
                  </h2>
                  <UBadge :color="statusColors[student.status]" variant="subtle">
                    {{ statusLabels[student.status] }}
                  </UBadge>
                </div>
                <p class="text-muted mb-1">
                  {{ student.user?.document_type }} {{ student.user?.document_number }}
                </p>
                <p v-if="student.group" class="text-muted">
                  {{ student.group.full_name }}
                </p>
              </div>
            </div>
          </UPageCard>

          <!-- Personal Information -->
          <UPageCard title="Informacion Personal" variant="subtle">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-muted">
                  Nombre Completo
                </p>
                <p class="font-medium">
                  {{ student.user?.name || '-' }}
                </p>
              </div>
              <div>
                <p class="text-sm text-muted">
                  Documento
                </p>
                <p class="font-medium">
                  {{ student.user?.document_type }} {{ student.user?.document_number }}
                </p>
              </div>
              <div>
                <p class="text-sm text-muted">
                  Correo Electronico
                </p>
                <p class="font-medium">
                  {{ student.user?.email || '-' }}
                </p>
              </div>
              <div>
                <p class="text-sm text-muted">
                  Telefono
                </p>
                <p class="font-medium">
                  {{ student.user?.phone || '-' }}
                </p>
              </div>
              <div>
                <p class="text-sm text-muted">
                  Fecha de Nacimiento
                </p>
                <p class="font-medium">
                  {{ formatDate(student.user?.birth_date) }}
                </p>
              </div>
              <div>
                <p class="text-sm text-muted">
                  Direccion
                </p>
                <p class="font-medium">
                  {{ student.user?.address || '-' }}
                </p>
              </div>
            </div>
          </UPageCard>

          <!-- Academic Information -->
          <UPageCard title="Informacion Academica" variant="subtle">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-muted">
                  Grupo Actual
                </p>
                <p class="font-medium">
                  {{ student.group?.full_name || '-' }}
                </p>
              </div>
              <div>
                <p class="text-sm text-muted">
                  Codigo de Matricula
                </p>
                <p class="font-medium">
                  {{ student.enrollment_code || '-' }}
                </p>
              </div>
              <div>
                <p class="text-sm text-muted">
                  Fecha de Matricula
                </p>
                <p class="font-medium">
                  {{ formatDate(student.enrollment_date) }}
                </p>
              </div>
              <div>
                <p class="text-sm text-muted">
                  Estado
                </p>
                <UBadge :color="statusColors[student.status]" variant="subtle">
                  {{ statusLabels[student.status] }}
                </UBadge>
              </div>
            </div>
          </UPageCard>

          <!-- Risk Score Widget -->
          <UPageCard v-if="riskScore" title="Índice de Riesgo (Período Activo)" variant="subtle">
            <div class="flex items-center gap-6">
              <!-- Score circle -->
              <div class="shrink-0 text-center">
                <p
                  class="text-4xl font-bold"
                  :class="{
                    'text-error': riskScore.level === 'high',
                    'text-warning': riskScore.level === 'medium',
                    'text-success': riskScore.level === 'low'
                  }"
                >
                  {{ riskScore.score }}
                </p>
                <p class="text-xs text-muted">
                  / 100
                </p>
              </div>

              <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-2">
                  <UBadge :color="riskConfig[riskScore.level].color" variant="soft">
                    {{ riskConfig[riskScore.level].label }}
                  </UBadge>
                </div>
                <!-- Bar -->
                <div class="h-2 rounded-full bg-muted/30 overflow-hidden mb-3">
                  <div
                    class="h-full rounded-full transition-all"
                    :class="riskConfig[riskScore.level].bg"
                    :style="{ width: `${riskScore.score}%` }"
                  />
                </div>
                <!-- Signals grid -->
                <div class="grid grid-cols-2 gap-x-4 gap-y-1">
                  <div class="flex items-center gap-1.5 text-xs">
                    <UIcon name="i-lucide-book-open" class="w-3.5 h-3.5 shrink-0" :class="riskScore.signals.failing_subjects > 0 ? 'text-error' : 'text-muted'" />
                    <span :class="riskScore.signals.failing_subjects > 0 ? 'text-error font-medium' : 'text-muted'">
                      {{ riskScore.signals.failing_subjects }}/{{ riskScore.signals.total_subjects }} reprobadas
                    </span>
                  </div>
                  <div class="flex items-center gap-1.5 text-xs">
                    <UIcon name="i-lucide-calendar" class="w-3.5 h-3.5 shrink-0" :class="riskScore.signals.attendance_pct < 80 ? 'text-error' : 'text-muted'" />
                    <span :class="riskScore.signals.attendance_pct < 80 ? 'text-error font-medium' : 'text-muted'">
                      {{ riskScore.signals.attendance_pct }}% asistencia
                    </span>
                  </div>
                  <div class="flex items-center gap-1.5 text-xs">
                    <UIcon name="i-lucide-shield" class="w-3.5 h-3.5 shrink-0" :class="riskScore.signals.disciplinary_incidents > 0 ? 'text-error' : 'text-muted'" />
                    <span :class="riskScore.signals.disciplinary_incidents > 0 ? 'text-error font-medium' : 'text-muted'">
                      {{ riskScore.signals.disciplinary_incidents }} incidentes convivencia
                    </span>
                  </div>
                  <div class="flex items-center gap-1.5 text-xs">
                    <UIcon name="i-lucide-clipboard-list" class="w-3.5 h-3.5 shrink-0" :class="riskScore.signals.pending_remedials > 0 ? 'text-warning' : 'text-muted'" />
                    <span :class="riskScore.signals.pending_remedials > 0 ? 'text-warning font-medium' : 'text-muted'">
                      {{ riskScore.signals.pending_remedials }} nivelaciones pendientes
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </UPageCard>

          <!-- Guardians -->
          <UPageCard v-if="student.guardians?.length" title="Acudientes" variant="subtle">
            <div class="divide-y">
              <div
                v-for="guardian in student.guardians"
                :key="guardian.id"
                class="py-3 first:pt-0 last:pb-0"
              >
                <div class="flex items-center gap-3">
                  <UAvatar :alt="guardian.user?.name" size="sm" />
                  <div>
                    <p class="font-medium">
                      {{ guardian.user?.name }}
                    </p>
                    <p class="text-sm text-muted">
                      {{ guardian.relationship }} - {{ guardian.user?.phone || 'Sin telefono' }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </UPageCard>

          <!-- AI Analysis History (admin/coordinator only) -->
          <UPageCard v-if="canSeeAiHistory" variant="subtle">
            <template #header>
              <div class="flex items-center gap-2">
                <UIcon name="i-lucide-brain" class="w-5 h-5 text-primary" />
                <span class="font-semibold">Historial de Análisis IA</span>
              </div>
            </template>

            <div v-if="aiHistoryLoading" class="flex items-center justify-center py-8">
              <UIcon name="i-lucide-loader-2" class="w-6 h-6 animate-spin text-primary" />
            </div>

            <template v-else>
              <!-- Evolution banner -->
              <div v-if="aiEvolution" class="mb-4 rounded-lg border border-default p-3 bg-muted/10 flex items-center gap-3">
                <UIcon
                  :name="aiEvolution.score_delta <= 0 ? 'i-lucide-trending-down' : 'i-lucide-trending-up'"
                  class="w-5 h-5 shrink-0"
                  :class="aiEvolution.score_delta <= 0 ? 'text-success' : 'text-error'"
                />
                <div class="min-w-0">
                  <p class="text-sm font-medium">
                    <template v-if="aiEvolution.level_changed">
                      {{ riskLevelLabels[aiEvolution.previous_level] }} → {{ riskLevelLabels[aiEvolution.current_level] }}
                    </template>
                    <template v-else>
                      Sin cambio de nivel ({{ riskLevelLabels[aiEvolution.current_level] }})
                    </template>
                  </p>
                  <p class="text-xs text-muted">
                    <span :class="aiEvolution.score_delta <= 0 ? 'text-success font-medium' : 'text-error font-medium'">
                      {{ aiEvolution.score_delta > 0 ? '+' : '' }}{{ aiEvolution.score_delta }} puntos de riesgo
                    </span>
                    vs análisis anterior
                    <span v-if="aiEvolution.score_delta < 0" class="text-success"> — mejora detectada</span>
                    <span v-else-if="aiEvolution.score_delta > 0" class="text-error"> — aumento de riesgo</span>
                  </p>
                </div>
              </div>

              <!-- Empty state -->
              <div v-if="aiAnalyses.length === 0" class="text-center py-10">
                <UIcon name="i-lucide-brain" class="w-10 h-10 text-muted mx-auto mb-3" />
                <p class="font-medium text-sm">
                  Sin análisis generados
                </p>
                <p class="text-xs text-muted mt-1 mb-4">
                  Genera el primer análisis IA desde el módulo de riesgo
                </p>
                <UButton
                  to="/reports/risk"
                  color="primary"
                  variant="soft"
                  size="sm"
                >
                  Ir a Índice de Riesgo
                </UButton>
              </div>

              <!-- Analyses list -->
              <div v-else class="flex flex-col gap-3">
                <div
                  v-for="analysis in aiAnalyses"
                  :key="analysis.id"
                  class="rounded-lg border border-default overflow-hidden"
                >
                  <!-- Row header -->
                  <button
                    class="w-full flex items-center justify-between gap-3 px-4 py-3 hover:bg-muted/10 transition-colors text-left"
                    @click="toggleAnalysis(analysis.id)"
                  >
                    <div class="flex items-center gap-3 min-w-0">
                      <UIcon
                        :name="expandedAnalyses.has(analysis.id) ? 'i-lucide-chevron-down' : 'i-lucide-chevron-right'"
                        class="w-4 h-4 text-muted shrink-0"
                      />
                      <span class="text-sm font-medium">{{ formatShortDate(analysis.created_at) }}</span>
                      <span class="text-xs text-muted">{{ analysis.period.name }}</span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                      <UBadge :color="riskConfig[analysis.risk_level].color" variant="soft" size="sm">
                        {{ riskConfig[analysis.risk_level].label }}
                      </UBadge>
                      <span
                        class="text-xs font-mono font-bold"
                        :class="{
                          'text-error': analysis.risk_level === 'high',
                          'text-warning': analysis.risk_level === 'medium',
                          'text-success': analysis.risk_level === 'low'
                        }"
                      >
                        {{ analysis.risk_score }}/100
                      </span>
                    </div>
                  </button>

                  <!-- Expanded content -->
                  <div v-if="expandedAnalyses.has(analysis.id)" class="border-t border-default px-4 py-3 space-y-3 bg-muted/5">
                    <div>
                      <p class="text-xs font-semibold text-muted uppercase tracking-wide mb-1">
                        Narrativa
                      </p>
                      <p class="text-sm leading-relaxed">
                        {{ analysis.narrative }}
                      </p>
                    </div>
                    <div v-if="analysis.recommendations.length">
                      <p class="text-xs font-semibold text-muted uppercase tracking-wide mb-2">
                        Recomendaciones
                      </p>
                      <div class="flex flex-wrap gap-1.5">
                        <UBadge
                          v-for="(rec, i) in analysis.recommendations"
                          :key="i"
                          color="primary"
                          variant="outline"
                          size="sm"
                        >
                          {{ rec.subject }}
                        </UBadge>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </template>
          </UPageCard>

          <!-- Quick Actions -->
          <UPageCard title="Acciones Rapidas" variant="subtle">
            <div class="flex flex-wrap gap-2">
              <UButton to="/grades/record" color="primary" variant="soft">
                <UIcon name="i-lucide-file-edit" class="w-4 h-4 mr-2" />
                Registrar Notas
              </UButton>
              <UButton to="/attendance" color="primary" variant="soft">
                <UIcon name="i-lucide-calendar-check" class="w-4 h-4 mr-2" />
                Asistencia
              </UButton>
              <UButton to="/reports/cards" color="primary" variant="soft">
                <UIcon name="i-lucide-file-text" class="w-4 h-4 mr-2" />
                Boletin
              </UButton>
            </div>
          </UPageCard>
        </template>

        <UPageCard v-else variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-user-x" class="w-12 h-12 text-muted mx-auto mb-4" />
            <p class="text-muted">
              Estudiante no encontrado
            </p>
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
