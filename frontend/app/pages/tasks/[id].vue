<script setup lang="ts">
import type { Task, StudentTask } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const route = useRoute()
const toast = useToast()
const tasksComposable = useTasks()

const loading = ref(true)
const task = ref<Task | null>(null)

const fetchTask = async () => {
  loading.value = true
  try {
    task.value = await tasksComposable.getTask(Number(route.params.id))
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo cargar la tarea', color: 'error' })
  } finally {
    loading.value = false
  }
}

onMounted(fetchTask)

const studentTasks = computed(() => task.value?.student_tasks || [])

const submissionsColumns = [
  { accessorKey: 'student', header: 'Estudiante' },
  { accessorKey: 'status', header: 'Estado' },
  { accessorKey: 'submitted_at', header: 'Entregado' },
  { accessorKey: 'actions', header: '' }
]
const totalCount = computed(() => studentTasks.value.length)
const pendingCount = computed(() => studentTasks.value.filter(st => st.status === 'pending').length)
const submittedCount = computed(() => studentTasks.value.filter(st => st.status === 'submitted').length)
const reviewedCount = computed(() => studentTasks.value.filter(st => st.status === 'reviewed').length)

const handleDownloadAttachment = async () => {
  if (!task.value) return
  try {
    await tasksComposable.downloadAttachment(task.value)
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo descargar el archivo', color: 'error' })
  }
}

const handleDownloadSubmission = async (st: StudentTask) => {
  try {
    await tasksComposable.downloadSubmission(st)
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo descargar la entrega', color: 'error' })
  }
}

const handleReview = async (st: StudentTask) => {
  try {
    await tasksComposable.reviewStudentTask(st.id)
    st.status = 'reviewed'
    toast.add({ title: 'Marcado como revisado', color: 'success' })
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo marcar como revisado', color: 'error' })
  }
}
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar :title="task?.title || 'Tarea'">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div v-if="loading" class="text-center py-12">
        <UIcon name="i-lucide-loader-2" class="w-8 h-8 text-primary animate-spin mx-auto" />
      </div>

      <div v-else-if="task" class="flex flex-col gap-6 p-6">
        <UButton
          icon="i-lucide-arrow-left"
          variant="ghost"
          to="/tasks"
          class="self-start"
        >
          Volver
        </UButton>

        <!-- Task Header -->
        <UPageCard variant="subtle">
          <div class="flex flex-col gap-3">
            <div class="flex items-start justify-between gap-4">
              <div>
                <h2 class="text-xl font-semibold">
                  {{ task.title }}
                </h2>
                <div class="flex gap-4 mt-1 text-sm text-muted">
                  <span>{{ task.group?.full_name }}</span>
                  <span v-if="task.subject">· {{ task.subject.name }}</span>
                  <span>· Vence: {{ new Date(task.due_date).toLocaleDateString('es-CO') }}</span>
                  <UBadge
                    v-if="tasksComposable.isOverdue(task.due_date)"
                    color="error"
                    variant="soft"
                    size="xs"
                  >
                    Vencida
                  </UBadge>
                </div>
              </div>
              <UButton
                v-if="task.attachment_path"
                icon="i-lucide-paperclip"
                variant="soft"
                size="sm"
                @click="handleDownloadAttachment"
              >
                Descargar adjunto
              </UButton>
            </div>

            <p class="text-sm whitespace-pre-wrap">
              {{ task.instructions }}
            </p>
          </div>
        </UPageCard>

        <!-- Stats -->
        <div class="grid grid-cols-4 gap-3">
          <UPageCard variant="subtle" class="text-center">
            <div class="text-2xl font-bold">
              {{ totalCount }}
            </div>
            <div class="text-sm text-muted">
              Total
            </div>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <div class="text-2xl font-bold text-warning">
              {{ pendingCount }}
            </div>
            <div class="text-sm text-muted">
              Pendientes
            </div>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <div class="text-2xl font-bold text-info">
              {{ submittedCount }}
            </div>
            <div class="text-sm text-muted">
              Entregadas
            </div>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <div class="text-2xl font-bold text-success">
              {{ reviewedCount }}
            </div>
            <div class="text-sm text-muted">
              Revisadas
            </div>
          </UPageCard>
        </div>

        <!-- Student Tasks Table -->
        <UCard>
          <template #header>
            <h3 class="font-semibold">
              Entregas por estudiante
            </h3>
          </template>

          <UTable
            :data="studentTasks"
            :columns="submissionsColumns"
          >
            <template #student-cell="{ row }">
              {{ (row.original as StudentTask).student?.user?.name || '—' }}
            </template>

            <template #status-cell="{ row }">
              <UBadge
                :color="tasksComposable.getStatusColor((row.original as StudentTask).status)"
                variant="soft"
              >
                {{ tasksComposable.getStatusLabel((row.original as StudentTask).status) }}
              </UBadge>
            </template>

            <template #submitted_at-cell="{ row }">
              {{ (row.original as StudentTask).submitted_at ? new Date((row.original as StudentTask).submitted_at!).toLocaleDateString('es-CO') : '—' }}
            </template>

            <template #actions-cell="{ row }">
              <div class="flex gap-2 justify-end">
                <UButton
                  v-if="(row.original as StudentTask).submission_path"
                  icon="i-lucide-file-up"
                  variant="ghost"
                  size="xs"
                  title="Descargar entrega"
                  @click="handleDownloadSubmission(row.original as StudentTask)"
                />
                <UButton
                  v-if="(row.original as StudentTask).status === 'submitted'"
                  icon="i-lucide-check"
                  variant="ghost"
                  color="success"
                  size="xs"
                  title="Marcar como revisado"
                  @click="handleReview(row.original as StudentTask)"
                />
              </div>
            </template>
          </UTable>
        </UCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
