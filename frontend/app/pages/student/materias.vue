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
const enrollments = ref<Enrollment[]>([])

// Group by semester
const bySemester = computed(() => {
  const map = new Map<number, Enrollment[]>()
  for (const e of enrollments.value) {
    if (!map.has(e.semester_number)) map.set(e.semester_number, [])
    map.get(e.semester_number)!.push(e)
  }
  return [...map.entries()]
    .sort(([a], [b]) => a - b)
    .map(([sem, items]) => ({ semester: sem, enrollments: items }))
})

const statusColors: Record<string, 'primary' | 'success' | 'neutral' | 'error'> = {
  enrolled: 'primary',
  completed: 'success',
  withdrawn: 'neutral',
  failed: 'error'
}

const statusLabels: Record<string, string> = {
  enrolled: 'En curso',
  completed: 'Aprobado',
  failed: 'Reprobado',
  withdrawn: 'Retirado'
}

const totalCredits = computed(() =>
  enrollments.value.reduce((s, e) => s + (e.subject?.credits ?? 0), 0)
)
const approvedCredits = computed(() =>
  enrollments.value
    .filter(e => e.status === 'completed')
    .reduce((s, e) => s + (e.subject?.credits ?? 0), 0)
)

onMounted(async () => {
  try {
    const res = await api.get<{ data: Enrollment[] }>('/student/enrollments')
    enrollments.value = res.data
  } catch {
    toast.add({ title: 'Error', description: 'No se pudieron cargar las materias', color: 'error' })
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Mis Materias">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton
            icon="i-lucide-arrow-left"
            label="Volver"
            size="sm"
            color="neutral"
            variant="ghost"
            to="/student"
          />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Page description -->
        <p class="text-sm text-muted">
          Aquí encuentras todas las materias en las que estás inscrito, organizadas por semestre. La matrícula es gestionada por la institución.
        </p>

        <div v-if="loading" class="py-16 text-center text-muted">
          <UIcon name="i-lucide-loader" class="w-8 h-8 mx-auto mb-3 animate-spin opacity-40" />
          <p class="text-sm">
            Cargando materias...
          </p>
        </div>

        <template v-else-if="enrollments.length === 0">
          <EmptyState
            icon="i-lucide-book-x"
            title="Sin materias registradas"
            description="Aún no tienes materias inscritas en el sistema."
            note="Comunícate con Secretaría Académica para gestionar tu matrícula académica."
          />
        </template>

        <template v-else>
          <!-- Credits summary -->
          <div class="grid grid-cols-2 gap-4">
            <UPageCard variant="subtle" class="text-center">
              <p class="text-3xl font-bold text-highlighted">
                {{ totalCredits }}
              </p>
              <p class="text-sm text-muted mt-1">
                Créditos totales
              </p>
            </UPageCard>
            <UPageCard variant="subtle" class="text-center">
              <p class="text-3xl font-bold" :class="approvedCredits > 0 ? 'text-success' : 'text-highlighted'">
                {{ approvedCredits }}
              </p>
              <p class="text-sm text-muted mt-1">
                Créditos aprobados
              </p>
            </UPageCard>
          </div>

          <!-- Per-semester breakdown -->
          <UPageCard
            v-for="group in bySemester"
            :key="group.semester"
            variant="subtle"
          >
            <template #header>
              <h3 class="font-semibold text-highlighted">
                Semestre {{ group.semester }}
              </h3>
            </template>

            <div class="space-y-2">
              <div
                v-for="e in group.enrollments"
                :key="e.id"
                class="flex items-center gap-3 p-3 rounded-lg bg-slate-50 dark:bg-slate-800/40 border border-slate-100 dark:border-slate-700"
              >
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-highlighted truncate">
                    {{ e.subject?.name ?? '—' }}
                  </p>
                  <p class="text-xs text-muted">
                    {{ e.subject?.credits ?? '—' }} créditos
                    <span v-if="e.academic_year"> · {{ e.academic_year.name }}</span>
                  </p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                  <span
                    v-if="e.final_grade != null"
                    class="text-sm font-mono font-semibold"
                    :class="Number(e.final_grade) >= 3.0 ? 'text-success' : 'text-error'"
                  >
                    {{ Number(e.final_grade).toFixed(2) }}
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
