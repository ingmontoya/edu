<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const auth = useAuthStore()
const api = useApi()
const toast = useToast()

const loading = ref(true)
const students = ref<any[]>([])

const fetchStudents = async () => {
  try {
    const data = await api.get<any[]>('/guardian/students')
    students.value = data
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron cargar los estudiantes', color: 'error' })
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  fetchStudents()
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Mis Hijos">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Welcome -->
        <UPageCard variant="subtle">
          <div>
            <h2 class="text-xl font-semibold">
              Bienvenido, {{ auth.user?.name }}
            </h2>
            <p class="text-muted">
              Portal de padres de familia - Consulte el rendimiento académico de sus hijos
            </p>
          </div>
        </UPageCard>

        <!-- Loading -->
        <div v-if="loading" class="text-center py-12">
          <UIcon name="i-lucide-loader-2" class="w-8 h-8 text-primary animate-spin mx-auto" />
          <p class="text-muted mt-2">
            Cargando...
          </p>
        </div>

        <!-- Students List -->
        <div v-else-if="students.length" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <UPageCard
            v-for="student in students"
            :key="student.id"
            variant="subtle"
            class="hover:ring-2 hover:ring-primary/50 transition-all cursor-pointer"
            @click="navigateTo(`/guardian/student/${student.id}`)"
          >
            <div class="flex items-center gap-4">
              <UAvatar :alt="student.user?.name" size="lg" />
              <div class="flex-1">
                <h3 class="font-semibold text-lg">
                  {{ student.user?.name }}
                </h3>
                <p class="text-sm text-muted">
                  {{ student.group?.full_name }}
                </p>
                <p class="text-xs text-muted">
                  Código: {{ student.enrollment_code }}
                </p>
              </div>
              <UIcon name="i-lucide-chevron-right" class="w-5 h-5 text-muted" />
            </div>
          </UPageCard>
        </div>

        <!-- Empty State -->
        <UPageCard v-else variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-users" class="w-12 h-12 text-muted mx-auto mb-4" />
            <h3 class="font-semibold mb-2">
              Sin estudiantes asignados
            </h3>
            <p class="text-muted">
              No tiene estudiantes vinculados a su cuenta
            </p>
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
