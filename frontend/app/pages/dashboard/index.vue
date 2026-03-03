<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const academicStore = useAcademicStore()
const loading = ref(true)

onMounted(async () => {
  await academicStore.fetchAll()
  loading.value = false
})

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
            <h2 class="text-xl font-semibold">Bienvenido a <span class="text-primary">Aula360</span></h2>
            <p class="text-muted">Panel de control del sistema de gestión académica</p>
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
                <p class="text-2xl font-bold">{{ stats.students }}</p>
                <p class="text-sm text-muted">Estudiantes</p>
              </div>
            </div>
          </UPageCard>

          <UPageCard variant="subtle">
            <div class="flex items-center gap-4">
              <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                <UIcon name="i-lucide-layout-grid" class="w-6 h-6 text-purple-600 dark:text-purple-400" />
              </div>
              <div>
                <p class="text-2xl font-bold">{{ stats.groups }}</p>
                <p class="text-sm text-muted">Grupos</p>
              </div>
            </div>
          </UPageCard>

          <UPageCard variant="subtle">
            <div class="flex items-center gap-4">
              <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl">
                <UIcon name="i-lucide-graduation-cap" class="w-6 h-6 text-green-600 dark:text-green-400" />
              </div>
              <div>
                <p class="text-2xl font-bold">{{ stats.grades }}</p>
                <p class="text-sm text-muted">Grados</p>
              </div>
            </div>
          </UPageCard>

          <UPageCard variant="subtle">
            <div class="flex items-center gap-4">
              <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                <UIcon name="i-lucide-calendar" class="w-6 h-6 text-orange-600 dark:text-orange-400" />
              </div>
              <div>
                <p class="text-lg font-bold">{{ stats.activePeriod }}</p>
                <p class="text-sm text-muted">Periodo Actual</p>
              </div>
            </div>
          </UPageCard>
        </div>

        <!-- Quick Actions -->
        <UPageCard title="Acciones Rápidas" variant="subtle">
          <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <UButton to="/students/new" icon="i-lucide-user-plus" label="Nuevo Estudiante" color="neutral" variant="outline" block />
            <UButton to="/grades/record" icon="i-lucide-file-edit" label="Registrar Notas" color="neutral" variant="outline" block />
            <UButton to="/attendance" icon="i-lucide-calendar-check" label="Tomar Asistencia" color="neutral" variant="outline" block />
            <UButton to="/reports/cards" icon="i-lucide-file-text" label="Generar Boletines" color="neutral" variant="outline" block />
          </div>
        </UPageCard>

        <!-- Active Year Info -->
        <UPageCard v-if="academicStore.activeYear" title="Año Académico Activo" variant="subtle">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <p class="text-sm text-muted">Año</p>
              <p class="font-semibold">{{ academicStore.activeYear.name }}</p>
            </div>
            <div>
              <p class="text-sm text-muted">Fecha Inicio</p>
              <p class="font-semibold">{{ formatDate(academicStore.activeYear.start_date) }}</p>
            </div>
            <div>
              <p class="text-sm text-muted">Fecha Fin</p>
              <p class="font-semibold">{{ formatDate(academicStore.activeYear.end_date) }}</p>
            </div>
          </div>

          <div v-if="academicStore.activeYear.periods?.length" class="mt-6">
            <p class="text-sm text-muted mb-3">Periodos</p>
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
