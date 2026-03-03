<script setup lang="ts">
import type { Achievement, Subject } from '~/types/school'

definePageMeta({ middleware: 'auth' })

const academicStore = useAcademicStore()
const { getAchievements, createAchievement, updateAchievement, deleteAchievement, getAchievementTypeLabel, copyAchievements, importAchievementsFromCsv } = useSiee()
const toast = useToast()

// State
const loading = ref(true)
const saving = ref(false)
const achievements = ref<Achievement[]>([])
const showModal = ref(false)
const showCopyModal = ref(false)
const showImportModal = ref(false)
const editingItem = ref<Achievement | null>(null)

// Filters
const selectedGrade = ref<number | undefined>()
const selectedSubject = ref<number | undefined>()
const selectedPeriod = ref<number | undefined>()

// Copy form
const copyForm = ref({
  sourcePeriod: undefined as number | undefined
})

// Import form
const importFile = ref<File | null>(null)
const fileInputRef = ref<HTMLInputElement | null>(null)

// Form
const formData = ref({
  code: '',
  description: '',
  type: 'cognitive' as 'cognitive' | 'procedural' | 'attitudinal',
  order: 1,
  indicators: [] as { description: string; code: string }[]
})

// Computed
const gradeItems = computed(() =>
  academicStore.grades.map(g => ({ value: g.id, label: g.name }))
)

const subjectItems = computed(() => {
  if (!selectedGrade.value) return []
  return academicStore.subjects
    .filter(s => Number(s.grade_id) === Number(selectedGrade.value))
    .map(s => ({ value: s.id, label: s.name }))
})

const periodItems = computed(() =>
  academicStore.periods.map(p => ({ value: p.id, label: p.name }))
)

const otherPeriodItems = computed(() =>
  academicStore.periods
    .filter(p => p.id !== selectedPeriod.value)
    .map(p => ({ value: p.id, label: p.name }))
)

const typeItems = [
  { value: 'cognitive', label: 'Cognitivo' },
  { value: 'procedural', label: 'Procedimental' },
  { value: 'attitudinal', label: 'Actitudinal' }
]

const columns = [
  { accessorKey: 'code', header: 'Codigo' },
  { accessorKey: 'description', header: 'Descripcion' },
  { accessorKey: 'type', header: 'Tipo' },
  { accessorKey: 'indicators', header: 'Indicadores' },
  { accessorKey: 'actions', header: '' }
]

// Methods
const fetchAchievements = async () => {
  if (!selectedSubject.value || !selectedPeriod.value) {
    achievements.value = []
    loading.value = false
    return
  }

  loading.value = true
  try {
    const result = await getAchievements({
      subject_id: Number(selectedSubject.value),
      period_id: Number(selectedPeriod.value)
    })
    achievements.value = result
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron cargar los logros', color: 'error' })
  } finally {
    loading.value = false
  }
}

const openCreate = () => {
  editingItem.value = null
  formData.value = {
    code: `L${achievements.value.length + 1}`,
    description: '',
    type: 'cognitive',
    order: achievements.value.length + 1,
    indicators: []
  }
  showModal.value = true
}

const openEdit = (item: Achievement) => {
  editingItem.value = item
  formData.value = {
    code: item.code || '',
    description: item.description,
    type: item.type,
    order: item.order,
    indicators: item.indicators?.map(i => ({ description: i.description, code: i.code || '' })) || []
  }
  showModal.value = true
}

const addIndicator = () => {
  formData.value.indicators.push({
    code: `I${formData.value.indicators.length + 1}`,
    description: ''
  })
}

const removeIndicator = (index: number) => {
  formData.value.indicators.splice(index, 1)
}

const handleSave = async () => {
  if (!formData.value.description) {
    toast.add({ title: 'Error', description: 'La descripcion es requerida', color: 'error' })
    return
  }

  saving.value = true
  try {
    if (editingItem.value) {
      await updateAchievement(editingItem.value.id, formData.value)
      toast.add({ title: 'Exito', description: 'Logro actualizado', color: 'success' })
    } else {
      await createAchievement({
        subject_id: selectedSubject.value!,
        period_id: selectedPeriod.value!,
        ...formData.value
      })
      toast.add({ title: 'Exito', description: 'Logro creado', color: 'success' })
    }
    showModal.value = false
    fetchAchievements()
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo guardar el logro', color: 'error' })
  } finally {
    saving.value = false
  }
}

const handleDelete = async (item: Achievement) => {
  if (!confirm('¿Eliminar este logro?')) return

  try {
    await deleteAchievement(item.id)
    toast.add({ title: 'Exito', description: 'Logro eliminado', color: 'success' })
    fetchAchievements()
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo eliminar el logro', color: 'error' })
  }
}

// Copy achievements
const openCopyModal = () => {
  copyForm.value.sourcePeriod = undefined
  showCopyModal.value = true
}

const handleCopy = async () => {
  if (!copyForm.value.sourcePeriod) {
    toast.add({ title: 'Error', description: 'Seleccione un periodo de origen', color: 'error' })
    return
  }

  saving.value = true
  try {
    await copyAchievements({
      source_subject_id: selectedSubject.value!,
      source_period_id: copyForm.value.sourcePeriod,
      target_period_id: selectedPeriod.value!
    })
    toast.add({ title: 'Exito', description: 'Logros copiados correctamente', color: 'success' })
    showCopyModal.value = false
    fetchAchievements()
  } catch (error: any) {
    toast.add({ title: 'Error', description: error.message || 'No se pudieron copiar los logros', color: 'error' })
  } finally {
    saving.value = false
  }
}

// Import from CSV
const openImportModal = () => {
  importFile.value = null
  showImportModal.value = true
}

const handleFileSelect = (event: Event) => {
  const target = event.target as HTMLInputElement
  if (target.files && target.files.length > 0) {
    importFile.value = target.files[0]
  }
}

const handleImport = async () => {
  if (!importFile.value) {
    toast.add({ title: 'Error', description: 'Seleccione un archivo CSV', color: 'error' })
    return
  }

  saving.value = true
  try {
    const result = await importAchievementsFromCsv(
      selectedSubject.value!,
      selectedPeriod.value!,
      importFile.value
    )
    toast.add({
      title: 'Exito',
      description: `${result.count} logros importados`,
      color: 'success'
    })
    if (result.errors && result.errors.length > 0) {
      console.warn('Errores durante la importacion:', result.errors)
    }
    showImportModal.value = false
    fetchAchievements()
  } catch (error: any) {
    toast.add({ title: 'Error', description: error.message || 'No se pudo importar el archivo', color: 'error' })
  } finally {
    saving.value = false
  }
}

const downloadTemplate = () => {
  const csvContent = 'codigo,descripcion,tipo\nL1,Identifica y aplica conceptos basicos,cognitive\nL2,Demuestra habilidades practicas,procedural\nL3,Muestra actitudes positivas,attitudinal'
  const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' })
  const link = document.createElement('a')
  link.href = URL.createObjectURL(blob)
  link.download = 'plantilla_logros.csv'
  link.click()
}

// Watchers
watch(selectedGrade, () => {
  selectedSubject.value = undefined
  achievements.value = []
})

watch([selectedSubject, selectedPeriod], () => {
  if (selectedSubject.value && selectedPeriod.value) {
    fetchAchievements()
  } else {
    achievements.value = []
  }
})

// Initialize
onMounted(async () => {
  await Promise.all([
    academicStore.fetchGrades(),
    academicStore.fetchSubjects(),
    academicStore.fetchPeriods()
  ])
  loading.value = false
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Logros por Asignatura" description="Gestion de logros e indicadores (SIEE)">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <div v-if="selectedSubject && selectedPeriod" class="flex gap-2">
            <UButton
              icon="i-lucide-copy"
              label="Copiar de otro periodo"
              variant="soft"
              @click="openCopyModal"
            />
            <UButton
              icon="i-lucide-upload"
              label="Importar CSV"
              variant="soft"
              @click="openImportModal"
            />
            <UButton
              icon="i-lucide-plus"
              label="Nuevo Logro"
              @click="openCreate"
            />
          </div>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Filters -->
        <UPageCard title="Seleccionar Asignatura" variant="subtle">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <UFormField label="Grado">
              <USelectMenu
                v-model="selectedGrade"
                :items="gradeItems"
                value-key="value"
                placeholder="Seleccionar grado"
              />
            </UFormField>

            <UFormField label="Asignatura">
              <USelectMenu
                v-model="selectedSubject"
                :items="subjectItems"
                value-key="value"
                placeholder="Seleccionar asignatura"
                :disabled="!selectedGrade"
              />
            </UFormField>

            <UFormField label="Periodo">
              <USelectMenu
                v-model="selectedPeriod"
                :items="periodItems"
                value-key="value"
                placeholder="Seleccionar periodo"
              />
            </UFormField>
          </div>
        </UPageCard>

        <!-- Info message if no selection -->
        <UPageCard v-if="!selectedSubject || !selectedPeriod" variant="subtle">
          <div class="flex items-center justify-center py-8 text-muted">
            <UIcon name="i-lucide-info" class="w-5 h-5 mr-2" />
            Seleccione grado, asignatura y periodo para ver los logros
          </div>
        </UPageCard>

        <!-- Achievements Table -->
        <UPageCard v-else title="Logros Definidos" variant="subtle">
          <template #actions>
            <UBadge color="primary" variant="subtle">
              {{ achievements.length }} logros
            </UBadge>
          </template>

          <UTable :columns="columns" :data="achievements" :loading="loading" :ui="{ tr: 'cursor-pointer hover:bg-elevated/50' }" @select="(_e, row) => openEdit(row.original)">
            <template #code-cell="{ row }">
              <span class="font-mono font-medium">{{ row.original.code || '-' }}</span>
            </template>

            <template #description-cell="{ row }">
              <p class="max-w-md">{{ row.original.description }}</p>
            </template>

            <template #type-cell="{ row }">
              <UBadge :color="row.original.type === 'cognitive' ? 'primary' : row.original.type === 'procedural' ? 'info' : 'warning'" variant="subtle">
                {{ getAchievementTypeLabel(row.original.type) }}
              </UBadge>
            </template>

            <template #indicators-cell="{ row }">
              <span class="text-muted">
                {{ row.original.indicators?.length || 0 }} indicadores
              </span>
            </template>

            <template #actions-cell="{ row }">
              <div class="flex gap-2 justify-end">
                <UButton
                  icon="i-lucide-edit"
                  size="xs"
                  variant="ghost"
                  @click="openEdit(row.original)"
                />
                <UButton
                  icon="i-lucide-trash"
                  size="xs"
                  variant="ghost"
                  color="error"
                  @click="handleDelete(row.original)"
                />
              </div>
            </template>
          </UTable>

          <div v-if="achievements.length === 0 && !loading" class="text-center py-8 text-muted">
            No hay logros definidos para esta asignatura y periodo
          </div>
        </UPageCard>

        <!-- Create/Edit Modal -->
        <UModal v-model:open="showModal">
          <template #content>
            <UCard>
              <template #header>
                <h3 class="text-lg font-semibold">
                  {{ editingItem ? 'Editar Logro' : 'Nuevo Logro' }}
                </h3>
              </template>

              <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                  <UFormField label="Codigo">
                    <UInput v-model="formData.code" placeholder="L1, L2..." />
                  </UFormField>

                  <UFormField label="Tipo" required>
                    <USelectMenu
                      v-model="formData.type"
                      :items="typeItems"
                      value-key="value"
                    />
                  </UFormField>
                </div>

                <UFormField label="Descripcion del Logro" required>
                  <UTextarea
                    v-model="formData.description"
                    placeholder="Describe el logro que el estudiante debe alcanzar..."
                    :rows="3"
                  />
                </UFormField>

                <!-- Indicators -->
                <div class="border rounded-lg p-4">
                  <div class="flex items-center justify-between mb-3">
                    <h4 class="font-medium">Indicadores de Logro</h4>
                    <UButton
                      icon="i-lucide-plus"
                      size="xs"
                      variant="soft"
                      label="Agregar"
                      @click="addIndicator"
                    />
                  </div>

                  <div v-if="formData.indicators.length === 0" class="text-sm text-muted text-center py-4">
                    No hay indicadores definidos
                  </div>

                  <div v-else class="space-y-3">
                    <div v-for="(indicator, index) in formData.indicators" :key="index" class="flex gap-2 items-start">
                      <UInput
                        v-model="indicator.code"
                        placeholder="I1"
                        class="w-16"
                      />
                      <UInput
                        v-model="indicator.description"
                        placeholder="Descripcion del indicador..."
                        class="flex-1"
                      />
                      <UButton
                        icon="i-lucide-x"
                        size="xs"
                        variant="ghost"
                        color="error"
                        @click="removeIndicator(index)"
                      />
                    </div>
                  </div>
                </div>
              </div>

              <template #footer>
                <div class="flex justify-end gap-2">
                  <UButton variant="ghost" label="Cancelar" @click="showModal = false" />
                  <UButton
                    :loading="saving"
                    :label="editingItem ? 'Guardar Cambios' : 'Crear Logro'"
                    @click="handleSave"
                  />
                </div>
              </template>
            </UCard>
          </template>
        </UModal>

        <!-- Copy Modal -->
        <UModal v-model:open="showCopyModal">
          <template #content>
            <UCard>
              <template #header>
                <h3 class="text-lg font-semibold">Copiar Logros de Otro Periodo</h3>
              </template>

              <div class="space-y-4">
                <p class="text-sm text-muted">
                  Seleccione el periodo desde el cual desea copiar los logros para esta asignatura.
                </p>

                <UFormField label="Periodo de Origen" required>
                  <USelectMenu
                    v-model="copyForm.sourcePeriod"
                    :items="otherPeriodItems"
                    value-key="value"
                    placeholder="Seleccionar periodo"
                  />
                </UFormField>

                <UAlert
                  icon="i-lucide-info"
                  color="info"
                  variant="subtle"
                  title="Nota"
                  description="Se copiaran todos los logros e indicadores del periodo seleccionado al periodo actual."
                />
              </div>

              <template #footer>
                <div class="flex justify-end gap-2">
                  <UButton variant="ghost" label="Cancelar" @click="showCopyModal = false" />
                  <UButton
                    :loading="saving"
                    label="Copiar Logros"
                    @click="handleCopy"
                  />
                </div>
              </template>
            </UCard>
          </template>
        </UModal>

        <!-- Import Modal -->
        <UModal v-model:open="showImportModal">
          <template #content>
            <UCard>
              <template #header>
                <h3 class="text-lg font-semibold">Importar Logros desde CSV</h3>
              </template>

              <div class="space-y-4">
                <p class="text-sm text-muted">
                  Suba un archivo CSV con los logros. El formato debe ser: codigo, descripcion, tipo (cognitive/procedural/attitudinal)
                </p>

                <div class="border-2 border-dashed rounded-lg p-6 text-center">
                  <input
                    ref="fileInputRef"
                    type="file"
                    accept=".csv,.txt"
                    class="hidden"
                    @change="handleFileSelect"
                  />
                  <UIcon name="i-lucide-file-spreadsheet" class="w-10 h-10 mx-auto mb-3 text-muted" />
                  <p v-if="importFile" class="font-medium">{{ importFile.name }}</p>
                  <p v-else class="text-muted">Ningún archivo seleccionado</p>
                  <UButton
                    variant="soft"
                    label="Seleccionar archivo"
                    class="mt-3"
                    @click="fileInputRef?.click()"
                  />
                </div>

                <UButton
                  variant="link"
                  icon="i-lucide-download"
                  label="Descargar plantilla CSV"
                  @click="downloadTemplate"
                />
              </div>

              <template #footer>
                <div class="flex justify-end gap-2">
                  <UButton variant="ghost" label="Cancelar" @click="showImportModal = false" />
                  <UButton
                    :loading="saving"
                    :disabled="!importFile"
                    label="Importar"
                    @click="handleImport"
                  />
                </div>
              </template>
            </UCard>
          </template>
        </UModal>
      </div>
    </template>
  </UDashboardPanel>
</template>
