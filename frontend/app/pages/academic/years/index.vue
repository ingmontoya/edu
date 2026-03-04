<script setup lang="ts">
import type { AcademicYear } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const { getAcademicYears, createAcademicYear, updateAcademicYear, deleteAcademicYear } = useAcademic()
const toast = useToast()

const loading = ref(true)
const showModal = ref(false)
const editingYear = ref<AcademicYear | null>(null)
const academicYears = ref<AcademicYear[]>([])
const searchQuery = ref('')
const currentPage = ref(1)
const perPage = ref(15)
const totalItems = ref(0)
const totalPages = ref(1)

const formData = ref({
  name: '',
  start_date: '',
  end_date: '',
  is_active: false
})

const columns = [
  { accessorKey: 'name', header: 'Nombre' },
  { accessorKey: 'start_date', header: 'Fecha Inicio' },
  { accessorKey: 'end_date', header: 'Fecha Fin' },
  { accessorKey: 'is_active', header: 'Estado' },
  { accessorKey: 'periods_count', header: 'Periodos' },
  { accessorKey: 'actions', header: '' }
]

const perPageOptions = [
  { value: 10, label: '10 por página' },
  { value: 15, label: '15 por página' },
  { value: 25, label: '25 por página' },
  { value: 50, label: '50 por página' }
]

const fetchAcademicYears = async () => {
  loading.value = true
  try {
    const params: any = {
      page: currentPage.value,
      per_page: perPage.value
    }
    if (searchQuery.value) {
      params.search = searchQuery.value
    }
    const response = await getAcademicYears(params)
    academicYears.value = response.data
    totalItems.value = response.meta.total
    totalPages.value = response.meta.last_page
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron cargar los años académicos', color: 'error' })
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
  fetchAcademicYears()
}, 300)

const openCreate = () => {
  editingYear.value = null
  const currentYear = new Date().getFullYear()
  formData.value = {
    name: `${currentYear}`,
    start_date: `${currentYear}-01-15`,
    end_date: `${currentYear}-11-30`,
    is_active: false
  }
  showModal.value = true
}

const openEdit = (year: AcademicYear) => {
  editingYear.value = year
  formData.value = {
    name: year.name,
    start_date: year.start_date,
    end_date: year.end_date,
    is_active: year.is_active
  }
  showModal.value = true
}

const handleSave = async () => {
  if (!formData.value.name || !formData.value.start_date || !formData.value.end_date) {
    toast.add({ title: 'Error', description: 'Complete todos los campos requeridos', color: 'error' })
    return
  }

  try {
    if (editingYear.value) {
      await updateAcademicYear(editingYear.value.id, formData.value)
      toast.add({ title: 'Éxito', description: 'Año académico actualizado', color: 'primary' })
    } else {
      await createAcademicYear(formData.value)
      toast.add({ title: 'Éxito', description: 'Año académico creado', color: 'primary' })
    }
    showModal.value = false
    fetchAcademicYears()
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo guardar el año académico', color: 'error' })
  }
}

const handleDelete = async (year: AcademicYear) => {
  if (!confirm(`¿Está seguro que desea eliminar el año académico ${year.name}?`)) return

  try {
    await deleteAcademicYear(year.id)
    toast.add({ title: 'Éxito', description: 'Año académico eliminado', color: 'primary' })
    fetchAcademicYears()
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo eliminar el año académico', color: 'error' })
  }
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('es-CO', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

onMounted(() => {
  fetchAcademicYears()
})

watch(currentPage, fetchAcademicYears)

watch(perPage, () => {
  currentPage.value = 1
  fetchAcademicYears()
})

watch(searchQuery, debouncedSearch)
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Años Académicos">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>

        <template #right>
          <UButton
            icon="i-lucide-plus"
            label="Nuevo Año Académico"
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
            placeholder="Buscar años académicos..."
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
            :data="academicYears"
            :loading="loading"
            :ui="{ tr: 'cursor-pointer hover:bg-elevated/50' }"
            @select="(_e, row) => openEdit(row.original)"
          >
            <template #start_date-cell="{ row }">
              {{ formatDate(row.original.start_date) }}
            </template>

            <template #end_date-cell="{ row }">
              {{ formatDate(row.original.end_date) }}
            </template>

            <template #is_active-cell="{ row }">
              <UBadge :color="row.original.is_active ? 'primary' : 'neutral'" variant="subtle">
                {{ row.original.is_active ? 'Activo' : 'Inactivo' }}
              </UBadge>
            </template>

            <template #periods_count-cell="{ row }">
              {{ row.original.periods?.length || 0 }}
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
                  :disabled="row.original.is_active"
                  @click="handleDelete(row.original)"
                />
              </div>
            </template>
          </UTable>

          <!-- Pagination -->
          <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4">
              <span class="text-sm text-muted">
                Mostrando {{ academicYears.length }} de {{ totalItems }} registros
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
                  {{ editingYear ? 'Editar Año Académico' : 'Nuevo Año Académico' }}
                </h3>
              </template>

              <div class="space-y-4">
                <UFormField label="Nombre" required>
                  <UInput
                    v-model="formData.name"
                    placeholder="Ej: 2025"
                  />
                </UFormField>

                <div class="grid grid-cols-2 gap-4">
                  <UFormField label="Fecha Inicio" required>
                    <UInput
                      v-model="formData.start_date"
                      type="date"
                    />
                  </UFormField>

                  <UFormField label="Fecha Fin" required>
                    <UInput
                      v-model="formData.end_date"
                      type="date"
                    />
                  </UFormField>
                </div>

                <UFormField>
                  <UCheckbox
                    v-model="formData.is_active"
                    label="Año activo"
                  />
                </UFormField>
              </div>

              <template #footer>
                <div class="flex gap-2 justify-end">
                  <UButton color="neutral" variant="ghost" @click="showModal = false">
                    Cancelar
                  </UButton>
                  <UButton color="primary" @click="handleSave">
                    {{ editingYear ? 'Guardar' : 'Crear' }}
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
