<script setup lang="ts">
import type { StudentTask, Student } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const toast = useToast()
const api = useApi()
const tasksComposable = useTasks()

const loading = ref(true)
const students = ref<Student[]>([])
const selectedStudentId = ref<number | null>(null)
const studentTasks = ref<StudentTask[]>([])

const fetchStudents = async () => {
  try {
    students.value = await api.get<Student[]>('/guardian/students')
    if (students.value.length === 1 && students.value[0]) {
      selectedStudentId.value = students.value[0].id
    }
  } catch {
    toast.add({ title: 'Error', description: 'No se pudieron cargar los estudiantes', color: 'error' })
  }
}

const fetchTasks = async () => {
  loading.value = true
  try {
    studentTasks.value = await tasksComposable.getGuardianTasks(
      selectedStudentId.value ?? undefined
    )
  } catch {
    toast.add({ title: 'Error', description: 'No se pudieron cargar las tareas', color: 'error' })
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  await fetchStudents()
  fetchTasks()
})

watch(selectedStudentId, fetchTasks)

const studentOptions = computed(() =>
  students.value.map(s => ({ label: s.user?.name || `Estudiante ${s.id}`, value: s.id }))
)
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Tareas">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-4 p-6">
        <!-- Student filter (if multiple children) -->
        <USelectMenu
          v-if="students.length > 1"
          v-model="selectedStudentId"
          :options="studentOptions"
          value-key="value"
          label-key="label"
          placeholder="Todos mis hijos"
          class="w-56"
          clearable
        />

        <!-- Loading -->
        <div v-if="loading" class="text-center py-12">
          <UIcon name="i-lucide-loader-2" class="w-8 h-8 text-primary animate-spin mx-auto" />
        </div>

        <!-- Tasks list -->
        <template v-else-if="studentTasks.length">
          <UPageCard
            v-for="st in studentTasks"
            :key="st.id"
            variant="subtle"
          >
            <div class="flex items-start justify-between gap-4">
              <div class="flex flex-col gap-1">
                <div class="flex items-center gap-2">
                  <span class="font-medium">{{ st.task?.title }}</span>
                  <UBadge
                    :color="tasksComposable.getStatusColor(st.status)"
                    variant="soft"
                    size="xs"
                  >
                    {{ tasksComposable.getStatusLabel(st.status) }}
                  </UBadge>
                </div>
                <div class="text-sm text-muted flex gap-3">
                  <span v-if="st.task?.subject">{{ st.task.subject.name }}</span>
                  <span v-if="st.task?.group">{{ st.task.group.full_name }}</span>
                  <span>Vence: {{ st.task ? new Date(st.task.due_date).toLocaleDateString('es-CO') : '—' }}</span>
                  <UBadge
                    v-if="st.task && tasksComposable.isOverdue(st.task.due_date) && st.status === 'pending'"
                    color="error"
                    variant="soft"
                    size="xs"
                  >
                    Vencida
                  </UBadge>
                </div>
                <p v-if="students.length > 1" class="text-xs text-muted">
                  Estudiante: {{ st.student?.user?.name }}
                </p>
                <p v-if="st.submitted_at" class="text-xs text-muted">
                  Entregado: {{ new Date(st.submitted_at).toLocaleDateString('es-CO') }}
                </p>
              </div>
            </div>
          </UPageCard>
        </template>

        <!-- Empty -->
        <UPageCard v-else variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-clipboard-list" class="w-12 h-12 text-muted mx-auto mb-4" />
            <h3 class="font-semibold mb-2">
              Sin tareas asignadas
            </h3>
            <p class="text-muted">
              No hay tareas registradas para sus hijos en este momento
            </p>
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
