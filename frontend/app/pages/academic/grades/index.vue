<script setup lang="ts">
import type { Grade } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const { getGrades, createGrade, updateGrade, deleteGrade } = useAcademic()
const toast = useToast()

const loading = ref(true)
const showModal = ref(false)
const editingGrade = ref<Grade | null>(null)
const grades = ref<Grade[]>([])
const searchQuery = ref('')
const currentPage = ref(1)
const perPage = ref(15)
const totalItems = ref(0)
const totalPages = ref(1)

const formData = ref({
  name: '',
  level: 'primaria' as 'preescolar' | 'primaria' | 'secundaria' | 'media',
  order: 1
})

const columns = [
  { accessorKey: 'order', header: 'Orden' },
  { accessorKey: 'name', header: 'Grado' },
  { accessorKey: 'level', header: 'Nivel' },
  { accessorKey: 'groups_count', header: 'Grupos' },
  { accessorKey: 'actions', header: '' }
]

const levelOptions = [
  { value: 'preescolar', label: 'Preescolar' },
  { value: 'primaria', label: 'Primaria' },
  { value: 'secundaria', label: 'Secundaria' },
  { value: 'media', label: 'Media' }
]

const levelLabels: Record<string, string> = {
  preescolar: 'Preescolar',
  primaria: 'Primaria',
  secundaria: 'Secundaria',
  media: 'Media'
}

const levelColors: Record<string, 'primary' | 'secondary' | 'success' | 'info' | 'warning' | 'error' | 'neutral'> = {
  preescolar: 'secondary',
  primaria: 'primary',
  secundaria: 'success',
  media: 'warning'
}

const perPageOptions = [
  { value: 10, label: '10 por página' },
  { value: 15, label: '15 por página' },
  { value: 25, label: '25 por página' },
  { value: 50, label: '50 por página' }
]

const fetchGrades = async () => {
  loading.value = true
  try {
    const params: any = {
      page: currentPage.value,
      per_page: perPage.value
    }
    if (searchQuery.value) {
      params.search = searchQuery.value
    }
    const response = await getGrades(params)
    grades.value = response.data
    totalItems.value = response.meta.total
    totalPages.value = response.meta.last_page
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron cargar los grados', color: 'error' })
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
  fetchGrades()
}, 300)

const sortedGrades = computed(() => {
  return [...grades.value].sort((a, b) => a.order - b.order)
})

const openCreate = () => {
  editingGrade.value = null
  formData.value = {
    name: '',
    level: 'primaria',
    order: grades.value.length + 1
  }
  showModal.value = true
}

const openEdit = (grade: Grade) => {
  editingGrade.value = grade
  formData.value = {
    name: grade.name,
    level: grade.level,
    order: grade.order
  }
  showModal.value = true
}

const handleSave = async () => {
  if (!formData.value.name || !formData.value.level) {
    toast.add({ title: 'Error', description: 'Complete todos los campos requeridos', color: 'error' })
    return
  }

  try {
    if (editingGrade.value) {
      await updateGrade(editingGrade.value.id, formData.value)
      toast.add({ title: 'Éxito', description: 'Grado actualizado', color: 'primary' })
    } else {
      await createGrade(formData.value)
      toast.add({ title: 'Éxito', description: 'Grado creado', color: 'primary' })
    }
    showModal.value = false
    fetchGrades()
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo guardar el grado', color: 'error' })
  }
}

const handleDelete = async (grade: Grade) => {
  if (!confirm(`¿Está seguro que desea eliminar el grado ${grade.name}?`)) return

  try {
    await deleteGrade(grade.id)
    toast.add({ title: 'Éxito', description: 'Grado eliminado', color: 'primary' })
    fetchGrades()
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo eliminar el grado', color: 'error' })
  }
}

onMounted(() => {
  fetchGrades()
})

watch(currentPage, fetchGrades)

watch(perPage, () => {
  currentPage.value = 1
  fetchGrades()
})

watch(searchQuery, debouncedSearch)
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Grados Escolares">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>

        <template #right>
          <UButton
            icon="i-lucide-plus"
            label="Nuevo Grado"
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
            placeholder="Buscar grados..."
            class="max-w-md"
          >
            <template #leading>
              <UIcon name="i-lucide-search" class="w-4 h-4 text-muted" />
            </template>
          </UInput>
        </UPageCard>

        <!-- Level Overview -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <UPageCard
            v-for="level in levelOptions"
            :key="level.value"
            variant="subtle"
            class="text-center"
          >
            <p class="text-2xl font-bold" :class="`text-${levelColors[level.value]}-500`">
              {{ sortedGrades.filter(g => g.level === level.value).length }}
            </p>
            <p class="text-sm text-muted">
              {{ level.label }}
            </p>
          </UPageCard>
        </div>

        <UPageCard variant="subtle">
          <UTable
            :columns="columns"
            :data="sortedGrades"
            :loading="loading"
            :ui="{ tr: 'cursor-pointer hover:bg-elevated/50' }"
            @select="(_e, row) => openEdit(row.original)"
          >
            <template #order-cell="{ row }">
              <span class="text-muted">{{ row.original.order }}</span>
            </template>

            <template #level-cell="{ row }">
              <UBadge :color="levelColors[row.original.level]" variant="subtle">
                {{ levelLabels[row.original.level] || row.original.level }}
              </UBadge>
            </template>

            <template #groups_count-cell="{ row }">
              {{ row.original.groups_count ?? 0 }}
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
                Mostrando {{ grades.length }} de {{ totalItems }} registros
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
                  {{ editingGrade ? 'Editar Grado' : 'Nuevo Grado' }}
                </h3>
              </template>

              <div class="space-y-4">
                <UFormField label="Nombre del Grado" required>
                  <UInput
                    v-model="formData.name"
                    placeholder="Ej: Primero, Segundo, Noveno..."
                  />
                </UFormField>

                <UFormField label="Nivel Educativo" required>
                  <USelectMenu
                    v-model="formData.level"
                    :items="levelOptions"
                    value-key="value"
                    placeholder="Seleccionar nivel"
                  />
                </UFormField>

                <UFormField label="Orden">
                  <UInput
                    v-model="formData.order"
                    type="number"
                    min="1"
                  />
                </UFormField>
              </div>

              <template #footer>
                <div class="flex gap-2 justify-end">
                  <UButton color="neutral" variant="ghost" @click="showModal = false">
                    Cancelar
                  </UButton>
                  <UButton color="primary" @click="handleSave">
                    {{ editingGrade ? 'Guardar' : 'Crear' }}
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
