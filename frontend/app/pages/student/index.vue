<script setup lang="ts">
import type { Enrollment } from '~/types/school'

definePageMeta({ middleware: ['auth', 'student'] })

const auth = useAuthStore()
const api = useApi()
const toast = useToast()

if (import.meta.client && !auth.isStudent) {
  await navigateTo('/login')
}

const loading = ref(true)
const showBanner = ref(false)

const dismissBanner = () => {
  showBanner.value = false
  localStorage.setItem('student_portal_banner_dismissed', '1')
}
const studentData = ref<{
  student: { id: number, enrollment_code: string, user: { name: string } }
  active_year: { id: number, name: string } | null
  current_enrollments_count: number
  current_credits: number
} | null>(null)

const currentEnrollments = ref<Enrollment[]>([])

const statusColors: Record<string, 'primary' | 'success' | 'neutral' | 'error'> = {
  enrolled: 'primary',
  completed: 'success',
  withdrawn: 'neutral',
  failed: 'error'
}

const statusLabels: Record<string, string> = {
  enrolled: 'Matriculado',
  completed: 'Aprobado',
  failed: 'Reprobado',
  withdrawn: 'Retirado'
}

const gradeColor = (grade: number | null) => {
  if (grade == null) return 'text-muted'
  if (grade >= 4.0) return 'text-success font-semibold'
  if (grade >= 3.0) return 'text-warning font-semibold'
  return 'text-error font-semibold'
}

onMounted(async () => {
  if (!localStorage.getItem('student_portal_banner_dismissed')) {
    showBanner.value = true
  }
  try {
    const [meRes, enrollRes] = await Promise.all([
      api.get<typeof studentData.value>('/student/me'),
      api.get<{ data: Enrollment[] }>('/student/enrollments', { params: { status: 'enrolled' } })
    ])
    studentData.value = meRes
    currentEnrollments.value = enrollRes.data
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo cargar tu información', color: 'error' })
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Mi Portal Académico">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Onboarding banner — shown once, dismissed to localStorage -->
        <UAlert
          v-if="showBanner"
          icon="i-lucide-info"
          color="primary"
          variant="subtle"
          title="Tu portal es de solo consulta"
          description="Tu matrícula y la asignación de materias es gestionada por la institución. Si no ves materias activas o tienes dudas sobre tu matrícula, comunícate con secretaría académica."
          :close-button="{ 'icon': 'i-lucide-x', 'size': 'xs', 'aria-label': 'Cerrar aviso' }"
          @close="dismissBanner"
        />

        <div v-if="loading" class="py-16 text-center text-muted">
          <UIcon name="i-lucide-loader" class="w-8 h-8 mx-auto mb-3 animate-spin opacity-40" />
        </div>

        <template v-else-if="studentData">
          <!-- Welcome -->
          <UPageCard variant="subtle">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                <span class="text-lg font-bold text-primary">
                  {{ (studentData.student.user?.name ?? '?').charAt(0).toUpperCase() }}
                </span>
              </div>
              <div>
                <h2 class="text-lg font-semibold text-highlighted">
                  Bienvenido, {{ studentData.student.user?.name }}
                </h2>
                <p class="text-sm text-muted">
                  Código: {{ studentData.student.enrollment_code }}
                  <span v-if="studentData.active_year"> · {{ studentData.active_year.name }}</span>
                </p>
              </div>
            </div>
          </UPageCard>

          <!-- Stats -->
          <div class="grid grid-cols-2 gap-4">
            <UPageCard variant="subtle" class="text-center">
              <p class="text-3xl font-bold text-highlighted">
                {{ studentData.current_enrollments_count }}
              </p>
              <p class="text-sm text-muted mt-1">
                Materias activas
              </p>
            </UPageCard>
            <UPageCard variant="subtle" class="text-center">
              <p class="text-3xl font-bold text-highlighted">
                {{ studentData.current_credits }}
              </p>
              <p class="text-sm text-muted mt-1">
                Créditos inscritos
              </p>
            </UPageCard>
          </div>

          <!-- Current enrollments -->
          <UPageCard variant="subtle">
            <template #header>
              <div class="flex items-center justify-between">
                <h3 class="font-semibold">
                  Materias del semestre actual
                </h3>
                <UButton
                  icon="i-lucide-history"
                  label="Ver historial completo"
                  size="xs"
                  color="neutral"
                  variant="ghost"
                  to="/student/kardex"
                />
              </div>
            </template>

            <EmptyState
              v-if="currentEnrollments.length === 0"
              icon="i-lucide-book-open"
              title="Sin materias activas"
              note="Comunícate con secretaría académica para gestionar tu matrícula."
            />

            <div v-else class="space-y-2">
              <div
                v-for="e in currentEnrollments"
                :key="e.id"
                class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-700"
              >
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-highlighted truncate">
                    {{ e.subject?.name ?? '—' }}
                  </p>
                  <p class="text-xs text-muted">
                    Semestre {{ e.semester_number }}
                    <span v-if="e.subject?.credits"> · {{ e.subject.credits }} créditos</span>
                  </p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                  <span v-if="e.final_grade != null" class="text-sm font-mono" :class="gradeColor(e.final_grade)">
                    {{ Number(e.final_grade).toFixed(1) }}
                  </span>
                  <UBadge :color="statusColors[e.status]" variant="subtle" size="xs">
                    {{ statusLabels[e.status] }}
                  </UBadge>
                </div>
              </div>
            </div>
          </UPageCard>
        </template>
      </div>
    </template>
  </UDashboardPanel>
</template>
