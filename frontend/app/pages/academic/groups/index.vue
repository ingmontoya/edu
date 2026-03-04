<script setup lang="ts">
import type { Group } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const academicStore = useAcademicStore()
const { getGroups, createGroup, updateGroup, deleteGroup } = useAcademic()
const toast = useToast()

const loading = ref(true)
const showModal = ref(false)
const editingGroup = ref<Group | null>(null)
const groups = ref<Group[]>([])
const searchQuery = ref('')
const currentPage = ref(1)
const perPage = ref(15)
const totalItems = ref(0)
const totalPages = ref(1)

const formData = ref({
  grade_id: undefined as number | undefined,
  name: '',
  capacity: 35
})

const columns = [
  { accessorKey: 'full_name', header: 'Grupo' },
  { accessorKey: 'grade_level', header: 'Nivel' },
  { accessorKey: 'capacity', header: 'Capacidad' },
  { accessorKey: 'director', header: 'Director' },
  { accessorKey: 'students_count', header: 'Estudiantes' },
  { accessorKey: 'actions', header: '' }
]

const levelLabels: Record<string, string> = {
  preescolar: 'Preescolar',
  primaria: 'Primaria',
  secundaria: 'Secundaria',
  media: 'Media'
}

const perPageOptions = [
  { value: 10, label: '10 por página' },
  { value: 15, label: '15 por página' },
  { value: 25, label: '25 por página' },
  { value: 50, label: '50 por página' }
]

// Transform items for USelectMenu (workaround for label-key bug)
const gradeItems = computed(() =>
  academicStore.grades.map(g => ({ value: g.id, label: g.name }))
)

const fetchGroups = async () => {
  loading.value = true
  try {
    const params: any = {
      page: currentPage.value,
      per_page: perPage.value,
      academic_year_id: academicStore.activeYear?.id
    }
    if (searchQuery.value) {
      params.search = searchQuery.value
    }
    const response = await getGroups(params)
    groups.value = response.data
    totalItems.value = response.meta.total
    totalPages.value = response.meta.last_page
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron cargar los grupos', color: 'error' })
  } finally {
    loading.value = false
  }
}

const goToPage = (page: number) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

const debouncedSearch = useDebounceFn(() => {
  currentPage.value = 1
  fetchGroups()
}, 300)

const openCreate = () => {
  editingGroup.value = null
  formData.value = {
    grade_id: undefined,
    name: '',
    capacity: 35
  }
  showModal.value = true
}

const openEdit = (group: Group) => {
  editingGroup.value = group
  formData.value = {
    grade_id: group.grade_id,
    name: group.name,
    capacity: group.capacity || 35
  }
  showModal.value = true
}

const handleSave = async () => {
  if (!formData.value.grade_id || !formData.value.name) {
    toast.add({ title: 'Error', description: 'Complete todos los campos', color: 'error' })
    return
  }

  try {
    if (editingGroup.value) {
      await updateGroup(editingGroup.value.id, {
        ...formData.value,
        academic_year_id: academicStore.activeYear?.id
      })
      toast.add({ title: 'Éxito', description: 'Grupo actualizado', color: 'primary' })
    } else {
      await createGroup({
        ...formData.value,
        academic_year_id: academicStore.activeYear?.id
      })
      toast.add({ title: 'Éxito', description: 'Grupo creado', color: 'primary' })
    }
    showModal.value = false
    fetchGroups()
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo guardar el grupo', color: 'error' })
  }
}

const handleDelete = async (group: Group) => {
  if (!confirm(`¿Está seguro que desea eliminar el grupo ${group.full_name}?`)) return

  try {
    await deleteGroup(group.id)
    toast.add({ title: 'Éxito', description: 'Grupo eliminado', color: 'primary' })
    fetchGroups()
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo eliminar el grupo', color: 'error' })
  }
}

onMounted(async () => {
  await academicStore.fetchGrades()
  await fetchGroups()
})

watch(currentPage, fetchGroups)

watch(perPage, () => {
  currentPage.value = 1
  fetchGroups()
})

watch(searchQuery, debouncedSearch)
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Grupos">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>

        <template #right>
          <UButton
            icon="i-lucide-plus"
            label="Nuevo Grupo"
            color="primary"
            @click="openCreate"
          />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Search -->
        <UPageCard variant="subtle">
          <UInput
            v-model="searchQuery"
            placeholder="Buscar grupos..."
            class="max-w-md"
          >
            <template #leading>
              <UIcon name="i-lucide-search" class="w-4 h-4 text-muted" />
            </template>
          </UInput>
        </UPageCard>

        <UPageCard variant="subtle">
          <UTable
            :columns="columns"
            :data="groups"
            :loading="loading"
            :ui="{ tr: 'cursor-pointer hover:bg-elevated/50' }"
            @select="(_e, row) => openEdit(row.original)"
          >
            <template #grade_level-cell="{ row }">
              <UBadge variant="subtle">
                {{ levelLabels[row.original.grade?.level] || row.original.grade?.level }}
              </UBadge>
            </template>

            <template #director-cell="{ row }">
              <span v-if="row.original.director">{{ row.original.director?.name }}</span>
              <span v-else class="text-muted">Sin asignar</span>
            </template>

            <template #students_count-cell="{ row }">
              {{ row.original.students?.length || 0 }}
            </template>

            <template #actions-cell="{ row }">
              <div class="flex gap-2 justify-end">
                <UButton
                  icon="i-lucide-edit"
                  color="neutral"
                  variant="ghost"
                  size="xs"
                  @click="openEdit(row.original)"
                />
                <UButton
                  icon="i-lucide-trash"
                  color="error"
                  variant="ghost"
                  size="xs"
                  @click="handleDelete(row.original)"
                />
              </div>
            </template>
          </UTable>

          <!-- Pagination -->
          <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4">
              <span class="text-sm text-muted">
                Mostrando {{ groups.length }} de {{ totalItems }} registros
              </span>
              <USelectMenu
                v-model="perPage"
                :items="perPageOptions"
                value-key="value"
                class="w-40"
              />
            </div>
            <div class="flex items-center gap-2">
              <UButton
                icon="i-lucide-chevrons-left"
                color="neutral"
                variant="ghost"
                size="sm"
                :disabled="currentPage === 1"
                @click="goToPage(1)"
              />
              <UButton
                icon="i-lucide-chevron-left"
                color="neutral"
                variant="ghost"
                size="sm"
                :disabled="currentPage === 1"
                @click="goToPage(currentPage - 1)"
              />
              <span class="text-sm px-3">
                Página {{ currentPage }} de {{ totalPages }}
              </span>
              <UButton
                icon="i-lucide-chevron-right"
                color="neutral"
                variant="ghost"
                size="sm"
                :disabled="currentPage === totalPages"
                @click="goToPage(currentPage + 1)"
              />
              <UButton
                icon="i-lucide-chevrons-right"
                color="neutral"
                variant="ghost"
                size="sm"
                :disabled="currentPage === totalPages"
                @click="goToPage(totalPages)"
              />
            </div>
          </div>
        </UPageCard>

        <!-- Modal -->
        <UModal v-model:open="showModal">
          <template #content>
            <UCard>
              <template #header>
                <h3 class="font-semibold">
                  {{ editingGroup ? 'Editar Grupo' : 'Nuevo Grupo' }}
                </h3>
              </template>

              <div class="space-y-4">
                <UFormField label="Grado" required>
                  <USelectMenu
                    v-model="formData.grade_id"
                    :items="gradeItems"
                    value-key="value"
                    placeholder="Seleccionar grado"
                  />
                </UFormField>

                <UFormField label="Nombre del Grupo" required>
                  <UInput
                    v-model="formData.name"
                    placeholder="Ej: A, B, C..."
                  />
                </UFormField>

                <UFormField label="Capacidad">
                  <UInput
                    v-model="formData.capacity"
                    type="number"
                    min="1"
                    max="50"
                  />
                </UFormField>
              </div>

              <template #footer>
                <div class="flex gap-2 justify-end">
                  <UButton color="neutral" variant="ghost" @click="showModal = false">
                    Cancelar
                  </UButton>
                  <UButton color="primary" @click="handleSave">
                    {{ editingGroup ? 'Guardar' : 'Crear' }}
                  </UButton>
                </div>
              </template>
            </UCard>
          </template>
        </UModal>
      </div>
    </template>
  </UDashboardPanel>
</template>
