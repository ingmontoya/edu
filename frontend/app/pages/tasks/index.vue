<script setup lang="ts">
import type { Task } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const toast = useToast()
const academic = useAcademicStore()
const tasks = useTasks()

const loading = ref(true)
const items = ref<Task[]>([])
const groupFilter = ref<number | null>(null)
const deleteTarget = ref<Task | null>(null)
const deleting = ref(false)

const fetchTasks = async () => {
  loading.value = true
  try {
    items.value = await tasks.getTasks(
      groupFilter.value ? { group_id: groupFilter.value } : undefined
    )
  } catch {
    toast.add({ title: 'Error', description: 'No se pudieron cargar las tareas', color: 'error' })
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  await academic.fetchGroups()
  fetchTasks()
})

watch(groupFilter, fetchTasks)

const groupOptions = computed(() =>
  academic.groups.map(g => ({ label: g.full_name || g.name, value: g.id }))
)

const tableColumns = [
  { accessorKey: 'title', header: 'Título' },
  { accessorKey: 'group', header: 'Grupo' },
  { accessorKey: 'subject', header: 'Asignatura' },
  { accessorKey: 'due_date', header: 'Vence' },
  { accessorKey: 'progress', header: 'Entregas' },
  { accessorKey: 'actions', header: '' }
]

const submittedCount = (task: Task) => {
  if (!task.student_tasks) return null
  return task.student_tasks.filter(st => st.status !== 'pending').length
}

const showDeleteModal = ref(false)

const confirmDelete = (task: Task) => {
  deleteTarget.value = task
  showDeleteModal.value = true
}

const cancelDelete = () => {
  deleteTarget.value = null
  showDeleteModal.value = false
}

const doDelete = async () => {
  if (!deleteTarget.value) return
  deleting.value = true
  try {
    await tasks.deleteTask(deleteTarget.value.id)
    items.value = items.value.filter(t => t.id !== deleteTarget.value!.id)
    toast.add({ title: 'Tarea eliminada', color: 'success' })
    deleteTarget.value = null
    showDeleteModal.value = false
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo eliminar la tarea', color: 'error' })
  } finally {
    deleting.value = false
  }
}
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Tareas">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton
            icon="i-lucide-plus"
            label="Nueva Tarea"
            to="/tasks/new"
          />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-4 p-6">
        <!-- Filters -->
        <div class="flex gap-3">
          <USelectMenu
            v-model="groupFilter"
            :items="groupOptions"
            value-key="value"
            placeholder="Todos los grupos"
            class="w-56"
            clearable
          />
        </div>

        <!-- Loading -->
        <div v-if="loading" class="text-center py-12">
          <UIcon name="i-lucide-loader-2" class="w-8 h-8 text-primary animate-spin mx-auto" />
        </div>

        <!-- Table -->
        <UCard v-else-if="items.length">
          <UTable
            :data="items"
            :columns="tableColumns"
          >
            <template #title-cell="{ row }">
              <NuxtLink :to="`/tasks/${row.original.id}`" class="font-medium hover:text-primary">
                {{ row.original.title }}
              </NuxtLink>
            </template>

            <template #group-cell="{ row }">
              {{ row.original.group?.full_name || '—' }}
            </template>

            <template #subject-cell="{ row }">
              {{ row.original.subject?.name || '—' }}
            </template>

            <template #due_date-cell="{ row }">
              <span :class="tasks.isOverdue(row.original.due_date) && row.original.student_tasks_count ? 'text-error' : ''">
                {{ new Date(row.original.due_date).toLocaleDateString('es-CO') }}
              </span>
              <UBadge
                v-if="tasks.isOverdue(row.original.due_date)"
                color="error"
                variant="soft"
                size="xs"
                class="ml-1"
              >
                Vencida
              </UBadge>
            </template>

            <template #progress-cell="{ row }">
              <span v-if="row.original.student_tasks">
                {{ submittedCount(row.original) }}/{{ row.original.student_tasks.length }} entregadas
              </span>
              <span v-else class="text-muted">—</span>
            </template>

            <template #actions-cell="{ row }">
              <div class="flex gap-2 justify-end">
                <UButton
                  icon="i-lucide-eye"
                  variant="ghost"
                  size="xs"
                  :to="`/tasks/${row.original.id}`"
                />
                <UButton
                  icon="i-lucide-trash-2"
                  variant="ghost"
                  color="error"
                  size="xs"
                  @click="confirmDelete(row.original)"
                />
              </div>
            </template>
          </UTable>
        </UCard>

        <!-- Empty -->
        <UPageCard v-else variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-clipboard-list" class="w-12 h-12 text-muted mx-auto mb-4" />
            <h3 class="font-semibold mb-2">
              Sin tareas
            </h3>
            <p class="text-muted mb-4">
              Crea la primera tarea para tus estudiantes
            </p>
            <UButton label="Nueva Tarea" to="/tasks/new" />
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>

  <!-- Delete Modal -->
  <UModal v-model:open="showDeleteModal">
    <template #content>
      <UCard>
        <template #header>
          <h3 class="text-lg font-semibold">
            Eliminar tarea
          </h3>
        </template>
        <p>¿Estás seguro de que deseas eliminar <strong>{{ deleteTarget?.title }}</strong>? Esta acción no se puede deshacer.</p>
        <template #footer>
          <div class="flex justify-end gap-3">
            <UButton variant="ghost" @click="cancelDelete">
              Cancelar
            </UButton>
            <UButton color="error" :loading="deleting" @click="doDelete">
              Eliminar
            </UButton>
          </div>
        </template>
      </UCard>
    </template>
  </UModal>
</template>
