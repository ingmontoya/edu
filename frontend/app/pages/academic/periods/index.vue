<script setup lang="ts">
import type { Period, AcademicYear } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const academicStore = useAcademicStore()
const { getPeriods, getAcademicYears, createPeriod, updatePeriod, deletePeriod } = useAcademic()
const toast = useToast()

const loading = ref(true)
const showModal = ref(false)
const editingPeriod = ref<Period | null>(null)
const periods = ref<Period[]>([])
const academicYears = ref<AcademicYear[]>([])
const searchQuery = ref('')
const currentPage = ref(1)
const perPage = ref(15)
const totalItems = ref(0)
const totalPages = ref(1)

const formData = ref({
  academic_year_id: undefined as number | undefined,
  name: '',
  start_date: '',
  end_date: '',
  weight: 25,
  is_active: false
})

// Transform items for USelectMenu (workaround for label-key bug)
const academicYearItems = computed(() =>
  academicYears.value.map(y => ({ value: y.id, label: y.name }))
)

const columns = [
  { accessorKey: 'name', header: 'Periodo' },
  { accessorKey: 'academic_year', header: 'Año Académico' },
  { accessorKey: 'dates', header: 'Fechas' },
  { accessorKey: 'weight', header: 'Peso (%)' },
  { accessorKey: 'is_closed', header: 'Estado' },
  { accessorKey: 'actions', header: '' }
]

const perPageOptions = [
  { value: 10, label: '10 por página' },
  { value: 15, label: '15 por página' },
  { value: 25, label: '25 por página' },
  { value: 50, label: '50 por página' }
]

const fetchPeriods = async () => {
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
    const response = await getPeriods(params)
    periods.value = response.data
    totalItems.value = response.meta.total
    totalPages.value = response.meta.last_page
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron cargar los periodos', color: 'error' })
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
  fetchPeriods()
}, 300)

const totalWeight = computed(() => {
  return periods.value.reduce((sum, p) => sum + (p.weight || 0), 0)
})

const openCreate = () => {
  editingPeriod.value = null
  formData.value = {
    academic_year_id: academicStore.activeYear?.id,
    name: '',
    start_date: '',
    end_date: '',
    weight: 25,
    is_active: false
  }
  showModal.value = true
}

const openEdit = (period: Period) => {
  editingPeriod.value = period
  formData.value = {
    academic_year_id: period.academic_year_id,
    name: period.name,
    start_date: period.start_date,
    end_date: period.end_date,
    weight: period.weight || 25,
    is_active: period.is_active
  }
  showModal.value = true
}

const handleSave = async () => {
  if (!formData.value.name || !formData.value.start_date || !formData.value.end_date || !formData.value.academic_year_id) {
    toast.add({ title: 'Error', description: 'Complete todos los campos requeridos', color: 'error' })
    return
  }

  try {
    if (editingPeriod.value) {
      await updatePeriod(editingPeriod.value.id, formData.value)
      toast.add({ title: 'Éxito', description: 'Periodo actualizado', color: 'primary' })
    } else {
      await createPeriod(formData.value as any)
      toast.add({ title: 'Éxito', description: 'Periodo creado', color: 'primary' })
    }
    showModal.value = false
    fetchPeriods()
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo guardar el periodo', color: 'error' })
  }
}

const handleDelete = async (period: Period) => {
  if (!confirm(`¿Está seguro que desea eliminar el periodo ${period.name}?`)) return

  try {
    await deletePeriod(period.id)
    toast.add({ title: 'Éxito', description: 'Periodo eliminado', color: 'primary' })
    fetchPeriods()
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo eliminar el periodo', color: 'error' })
  }
}

const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('es-CO', {
    month: 'short',
    day: 'numeric'
  })
}

onMounted(async () => {
  await academicStore.fetchAcademicYears()
  const response = await getAcademicYears({ per_page: 100 })
  academicYears.value = response.data
  await fetchPeriods()
})

watch(currentPage, fetchPeriods)

watch(perPage, () => {
  currentPage.value = 1
  fetchPeriods()
})

watch(searchQuery, debouncedSearch)
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Periodos Académicos">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>

        <template #right>
          <UButton
            icon="i-lucide-plus"
            label="Nuevo Periodo"
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
            placeholder="Buscar periodos..."
            class="max-w-md"
          >
            <template #leading>
              <UIcon name="i-lucide-search" class="w-4 h-4 text-muted" />
            </template>
          </UInput>
        </UPageCard>

        <!-- Weight Summary -->
        <UPageCard variant="subtle">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="font-semibold mb-1">
                Distribución de Pesos
              </h3>
              <p class="text-sm text-muted">
                Los pesos de todos los periodos deben sumar 100%
              </p>
            </div>
            <div class="text-right">
              <p class="text-3xl font-bold" :class="totalWeight === 100 ? 'text-green-500' : 'text-yellow-500'">
                {{ totalWeight }}%
              </p>
              <p v-if="totalWeight !== 100" class="text-sm text-yellow-500">
                {{ totalWeight < 100 ? `Falta ${100 - totalWeight}%` : `Excede ${totalWeight - 100}%` }}
              </p>
            </div>
          </div>
        </UPageCard>

        <UPageCard variant="subtle">
          <UTable
            :columns="columns"
            :data="periods"
            :loading="loading"
            :ui="{ tr: 'cursor-pointer hover:bg-elevated/50' }"
            @select="(_e, row) => openEdit(row.original)"
          >
            <template #academic_year-cell="{ row }">
              {{ row.original.academic_year?.name || '-' }}
            </template>

            <template #dates-cell="{ row }">
              {{ formatDate(row.original.start_date) }} - {{ formatDate(row.original.end_date) }}
            </template>

            <template #weight-cell="{ row }">
              <span class="font-medium">{{ row.original.weight }}%</span>
            </template>

            <template #is_closed-cell="{ row }">
              <UBadge :color="row.original.is_closed ? 'neutral' : 'primary'" variant="subtle">
                {{ row.original.is_closed ? 'Cerrado' : 'Abierto' }}
              </UBadge>
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
                  :disabled="!row.original.is_closed"
                  @click="handleDelete(row.original)"
                />
              </div>
            </template>
          </UTable>

          <!-- Pagination -->
          <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-4">
              <span class="text-sm text-muted">
                Mostrando {{ periods.length }} de {{ totalItems }} registros
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
                  {{ editingPeriod ? 'Editar Periodo' : 'Nuevo Periodo' }}
                </h3>
              </template>

              <div class="space-y-4">
                <UFormField label="Año Académico" required>
                  <USelectMenu
                    v-model="formData.academic_year_id"
                    :items="academicYearItems"
                    value-key="value"
                    placeholder="Seleccionar año"
                  />
                </UFormField>

                <UFormField label="Nombre del Periodo" required>
                  <UInput
                    v-model="formData.name"
                    placeholder="Ej: Primer Periodo, Segundo Periodo..."
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

                <UFormField label="Peso (%)">
                  <UInput
                    v-model="formData.weight"
                    type="number"
                    min="0"
                    max="100"
                  />
                  <p class="text-xs text-muted mt-1">
                    Porcentaje que representa este periodo en la nota final
                  </p>
                </UFormField>

                <UFormField>
                  <UCheckbox
                    v-model="formData.is_active"
                    label="Periodo activo"
                  />
                </UFormField>
              </div>

              <template #footer>
                <div class="flex gap-2 justify-end">
                  <UButton color="neutral" variant="ghost" @click="showModal = false">
                    Cancelar
                  </UButton>
                  <UButton color="primary" @click="handleSave">
                    {{ editingPeriod ? 'Guardar' : 'Crear' }}
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
