<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const auth = useAuthStore()
const api = useApi()
const toast = useToast()

if (import.meta.client && !auth.isStudent) {
  await navigateTo('/login')
}

const loading = ref(true)
const kardexData = ref<{
  academic_year: { id: number, name: string }
  enrollments: {
    id: number
    semester_number: number
    status: string
    final_grade: number | null
    subject?: { name: string, credits: number }
  }[]
  total_credits: number
  approved_credits: number
  papa: number | null
}[]>([])

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
  try {
    const res = await api.get<{ data: typeof kardexData.value }>('/student/kardex')
    kardexData.value = res.data
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo cargar el historial académico', color: 'error' })
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Historial Académico">
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
        <div v-if="loading" class="py-16 text-center text-muted">
          <UIcon name="i-lucide-loader" class="w-8 h-8 mx-auto mb-3 animate-spin opacity-40" />
          <p class="text-sm">
            Cargando historial...
          </p>
        </div>

        <div v-else-if="kardexData.length === 0" class="py-16 text-center text-muted">
          <UIcon name="i-lucide-file-text" class="w-10 h-10 mx-auto mb-3 opacity-30" />
          <p class="text-sm">
            No tienes matrículas registradas aún.
          </p>
        </div>

        <template v-else>
          <UPageCard
            v-for="yearData in kardexData"
            :key="yearData.academic_year.id"
            variant="subtle"
          >
            <template #header>
              <div class="flex items-center justify-between flex-wrap gap-3">
                <h3 class="font-semibold text-highlighted">
                  {{ yearData.academic_year.name }}
                </h3>
                <div class="flex items-center gap-4 text-sm">
                  <span class="text-muted">
                    {{ yearData.total_credits }} créditos inscritos
                  </span>
                  <span class="text-success font-medium">
                    {{ yearData.approved_credits }} aprobados
                  </span>
                  <span v-if="yearData.papa != null" class="font-mono font-bold" :class="gradeColor(yearData.papa)">
                    PAPA {{ yearData.papa.toFixed(2) }}
                  </span>
                </div>
              </div>
            </template>

            <UTable
              :columns="[
                { accessorKey: 'subject', header: 'Materia' },
                { accessorKey: 'credits', header: 'Créditos' },
                { accessorKey: 'semester_number', header: 'Semestre' },
                { accessorKey: 'final_grade', header: 'Nota Final' },
                { accessorKey: 'status', header: 'Estado' }
              ]"
              :data="yearData.enrollments"
              :ui="{ root: 'text-sm' }"
            >
              <template #subject-cell="{ row: r }">
                {{ r.original.subject?.name ?? '—' }}
              </template>
              <template #credits-cell="{ row: r }">
                <UBadge variant="subtle" color="neutral" size="xs">
                  {{ r.original.subject?.credits ?? '—' }} cr.
                </UBadge>
              </template>
              <template #semester_number-cell="{ row: r }">
                Semestre {{ r.original.semester_number }}
              </template>
              <template #final_grade-cell="{ row: r }">
                <span :class="gradeColor(r.original.final_grade ?? null)">
                  {{ r.original.final_grade != null ? Number(r.original.final_grade).toFixed(2) : '—' }}
                </span>
              </template>
              <template #status-cell="{ row: r }">
                <UBadge :color="statusColors[r.original.status]" variant="subtle" size="xs">
                  {{ statusLabels[r.original.status] }}
                </UBadge>
              </template>
            </UTable>
          </UPageCard>
        </template>
      </div>
    </template>
  </UDashboardPanel>
</template>
