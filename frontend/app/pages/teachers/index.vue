<script setup lang="ts">
import type { Teacher } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const { getTeachers, createTeacher, updateTeacher, deleteTeacher, importTeachersFromCsv } = useAcademic()
const toast = useToast()

const loading = ref(true)
const showModal = ref(false)

// Import modal state
const showImportModal = ref(false)
const importFile = ref<File | null>(null)
const importing = ref(false)
const importErrors = ref<string[]>([])
const editingTeacher = ref<Teacher | null>(null)
const teachers = ref<Teacher[]>([])
const searchQuery = ref('')
const currentPage = ref(1)
const perPage = ref(15)
const totalItems = ref(0)
const totalPages = ref(1)

const formData = ref({
  name: '',
  email: '',
  document_type: 'CC',
  document_number: '',
  phone: '',
  specialty: '',
  contract_type: 'full_time' as 'full_time' | 'part_time' | 'contractor'
})

const columns = [
  { accessorKey: 'name', header: 'Nombre' },
  { accessorKey: 'document', header: 'Documento' },
  { accessorKey: 'email', header: 'Correo' },
  { accessorKey: 'specialization', header: 'Especialidad' },
  { accessorKey: 'assignments', header: 'Asignaciones' },
  { accessorKey: 'actions', header: '' }
]

const documentTypes = [
  { value: 'CC', label: 'Cédula de Ciudadanía' },
  { value: 'CE', label: 'Cédula de Extranjería' },
  { value: 'TI', label: 'Tarjeta de Identidad' },
  { value: 'NUIP', label: 'NUIP' },
  { value: 'PAS', label: 'Pasaporte' }
]

const contractTypes = [
  { value: 'full_time', label: 'Tiempo Completo' },
  { value: 'part_time', label: 'Medio Tiempo' },
  { value: 'contractor', label: 'Contratista' }
]

const contractLabels: Record<string, string> = {
  full_time: 'Tiempo Completo',
  part_time: 'Medio Tiempo',
  contractor: 'Contratista'
}

const contractColors: Record<string, 'primary' | 'secondary' | 'success' | 'info' | 'warning' | 'error' | 'neutral'> = {
  full_time: 'success',
  part_time: 'info',
  contractor: 'warning'
}

// Colores vibrantes para los avatares
const avatarColors = [
  'bg-red-500',
  'bg-orange-500',
  'bg-amber-500',
  'bg-yellow-500',
  'bg-lime-500',
  'bg-green-500',
  'bg-emerald-500',
  'bg-teal-500',
  'bg-cyan-500',
  'bg-sky-500',
  'bg-blue-500',
  'bg-indigo-500',
  'bg-violet-500',
  'bg-purple-500',
  'bg-fuchsia-500',
  'bg-pink-500',
  'bg-rose-500'
]

const getAvatarColor = (name: string | undefined): string => {
  if (!name) return avatarColors[0]
  let hash = 0
  for (let i = 0; i < name.length; i++) {
    hash = name.charCodeAt(i) + ((hash << 5) - hash)
  }
  return avatarColors[Math.abs(hash) % avatarColors.length]
}

const getInitials = (name: string | undefined): string => {
  if (!name) return '?'
  const parts = name.trim().split(' ')
  if (parts.length >= 2) {
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
  }
  return name.substring(0, 2).toUpperCase()
}

const perPageOptions = [
  { value: 10, label: '10 por página' },
  { value: 15, label: '15 por página' },
  { value: 25, label: '25 por página' },
  { value: 50, label: '50 por página' }
]

const fetchData = async () => {
  loading.value = true
  try {
    const params: any = {
      page: currentPage.value,
      per_page: perPage.value
    }
    if (searchQuery.value) {
      params.search = searchQuery.value
    }
    const response = await getTeachers(params)
    teachers.value = response.data
    totalItems.value = response.meta.total
    totalPages.value = response.meta.last_page
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron cargar los docentes', color: 'error' })
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
  fetchData()
}, 300)

const openCreate = () => {
  editingTeacher.value = null
  formData.value = {
    name: '',
    email: '',
    document_type: 'CC',
    document_number: '',
    phone: '',
    specialty: '',
    contract_type: 'full_time'
  }
  showModal.value = true
}

const openEdit = (teacher: Teacher) => {
  editingTeacher.value = teacher
  formData.value = {
    name: teacher.user?.name || '',
    email: teacher.user?.email || '',
    document_type: teacher.user?.document_type || 'CC',
    document_number: teacher.user?.document_number || '',
    phone: teacher.user?.phone || '',
    specialty: teacher.specialty || '',
    contract_type: teacher.contract_type || 'full_time'
  }
  showModal.value = true
}

const handleSave = async () => {
  if (!formData.value.name || !formData.value.email || !formData.value.document_number) {
    toast.add({ title: 'Error', description: 'Complete los campos requeridos', color: 'error' })
    return
  }

  try {
    if (editingTeacher.value) {
      await updateTeacher(editingTeacher.value.id, formData.value)
      toast.add({ title: 'Éxito', description: 'Docente actualizado', color: 'primary' })
    } else {
      await createTeacher(formData.value)
      toast.add({ title: 'Éxito', description: 'Docente creado', color: 'primary' })
    }
    showModal.value = false
    fetchData()
  } catch (error: any) {
    toast.add({
      title: 'Error',
      description: error?.message || 'No se pudo guardar el docente',
      color: 'error'
    })
  }
}

const handleDelete = async (teacher: Teacher) => {
  if (!confirm(`¿Está seguro que desea eliminar al docente ${teacher.user?.name}?`)) return

  try {
    await deleteTeacher(teacher.id)
    toast.add({ title: 'Éxito', description: 'Docente eliminado', color: 'primary' })
    fetchData()
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo eliminar el docente', color: 'error' })
  }
}

onMounted(() => {
  fetchData()
})

watch(currentPage, fetchData)

watch(perPage, () => {
  currentPage.value = 1
  fetchData()
})

watch(searchQuery, debouncedSearch)

// Import functions
const openImportModal = () => {
  importFile.value = null
  importErrors.value = []
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

  importing.value = true
  importErrors.value = []

  try {
    const result = await importTeachersFromCsv(importFile.value)
    toast.add({ title: 'Exito', description: result.message, color: 'success' })

    if (result.errors.length > 0) {
      importErrors.value = result.errors
    } else {
      showImportModal.value = false
      fetchData()
    }
  } catch (error: any) {
    toast.add({ title: 'Error', description: error.message || 'Error al importar', color: 'error' })
  } finally {
    importing.value = false
  }
}

const downloadTemplate = () => {
  const headers = 'nombre,documento_tipo,documento_numero,email,telefono,direccion,especializacion'
  const example = 'Maria Garcia,CC,1234567890,maria.garcia@email.com,3001234567,Calle 123,Matematicas'
  const csv = headers + '\n' + example
  const blob = new Blob([csv], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = 'plantilla_docentes.csv'
  link.click()
  window.URL.revokeObjectURL(url)
}
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Docentes">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>

        <template #right>
          <div class="flex gap-2">
            <UButton
              icon="i-lucide-upload"
              label="Importar CSV"
              variant="outline"
              @click="openImportModal"
            />
            <UButton
              icon="i-lucide-plus"
              label="Nuevo Docente"
              color="primary"
              @click="openCreate"
            />
          </div>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Search -->
        <UPageCard variant="subtle">
          <UInput
            v-model="searchQuery"
            placeholder="Buscar por nombre, correo, documento o especialidad..."
            class="max-w-md"
          >
            <template #leading>
              <UIcon name="i-lucide-search" class="w-4 h-4 text-muted" />
            </template>
          </UInput>
        </UPageCard>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
          <UPageCard variant="subtle" class="text-center">
            <p class="text-2xl font-bold text-primary">
              {{ totalItems }}
            </p>
            <p class="text-sm text-muted">
              Total Docentes
            </p>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <p class="text-2xl font-bold text-green-500">
              {{ teachers.filter(t => t.contract_type === 'full_time').length }}
            </p>
            <p class="text-sm text-muted">
              Tiempo Completo
            </p>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <p class="text-2xl font-bold text-blue-500">
              {{ teachers.filter(t => t.contract_type === 'part_time').length }}
            </p>
            <p class="text-sm text-muted">
              Medio Tiempo
            </p>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <p class="text-2xl font-bold text-yellow-500">
              {{ teachers.filter(t => t.contract_type === 'contractor').length }}
            </p>
            <p class="text-sm text-muted">
              Contratistas
            </p>
          </UPageCard>
        </div>

        <UPageCard variant="subtle">
          <UTable
            :columns="columns"
            :data="teachers"
            :loading="loading"
            :ui="{ tr: 'cursor-pointer hover:bg-elevated/50' }"
            @select="(_e, row) => openEdit(row.original)"
          >
            <template #name-cell="{ row }">
              <div class="flex items-center gap-3">
                <div
                  :class="[getAvatarColor(row.original.user?.name), 'w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold shadow-sm']"
                >
                  {{ getInitials(row.original.user?.name) }}
                </div>
                <span class="font-medium">{{ row.original.user?.name }}</span>
              </div>
            </template>

            <template #document-cell="{ row }">
              <span class="text-muted">
                {{ row.original.user?.document_type }} {{ row.original.user?.document_number }}
              </span>
            </template>

            <template #email-cell="{ row }">
              {{ row.original.user?.email }}
            </template>

            <template #specialization-cell="{ row }">
              {{ row.original.specialization || '-' }}
            </template>

            <template #assignments-cell="{ row }">
              {{ row.original.assignments?.length || 0 }}
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
                Mostrando {{ teachers.length }} de {{ totalItems }} registros
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
                  {{ editingTeacher ? 'Editar Docente' : 'Nuevo Docente' }}
                </h3>
              </template>

              <div class="space-y-4">
                <UFormField label="Nombre Completo" required>
                  <UInput
                    v-model="formData.name"
                    placeholder="Nombre completo del docente"
                  />
                </UFormField>

                <UFormField label="Correo Electrónico" required>
                  <UInput
                    v-model="formData.email"
                    type="email"
                    placeholder="correo@ejemplo.com"
                  />
                </UFormField>

                <div class="grid grid-cols-2 gap-4">
                  <UFormField label="Tipo Documento" required>
                    <USelectMenu
                      v-model="formData.document_type"
                      :items="documentTypes"
                      value-key="value"
                    />
                  </UFormField>

                  <UFormField label="Número de Documento" required>
                    <UInput
                      v-model="formData.document_number"
                      placeholder="Número de documento"
                    />
                  </UFormField>
                </div>

                <UFormField label="Teléfono">
                  <UInput
                    v-model="formData.phone"
                    placeholder="Número de teléfono"
                  />
                </UFormField>

                <UFormField label="Especialidad">
                  <UInput
                    v-model="formData.specialty"
                    placeholder="Ej: Matemáticas, Ciencias..."
                  />
                </UFormField>

                <UFormField label="Tipo de Contrato">
                  <USelectMenu
                    v-model="formData.contract_type"
                    :items="contractTypes"
                    value-key="value"
                  />
                </UFormField>
              </div>

              <template #footer>
                <div class="flex gap-2 justify-end">
                  <UButton color="neutral" variant="ghost" @click="showModal = false">
                    Cancelar
                  </UButton>
                  <UButton color="primary" @click="handleSave">
                    {{ editingTeacher ? 'Guardar' : 'Crear' }}
                  </UButton>
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
                <h3 class="text-lg font-semibold">
                  Importar Docentes desde CSV
                </h3>
                <p class="text-sm text-muted">
                  Suba un archivo CSV con los datos de los docentes
                </p>
              </template>

              <div class="space-y-4">
                <UFormField label="Archivo CSV" required>
                  <input
                    type="file"
                    accept=".csv,.txt"
                    class="w-full px-3 py-2 border rounded-md dark:bg-neutral-900 dark:border-neutral-700"
                    @change="handleFileSelect"
                  >
                </UFormField>

                <div class="text-sm text-muted">
                  <p class="font-medium mb-1">
                    Formato esperado del CSV:
                  </p>
                  <p>nombre, documento_tipo, documento_numero, email, telefono, direccion, especializacion</p>
                  <UButton
                    variant="link"
                    size="xs"
                    icon="i-lucide-download"
                    label="Descargar plantilla"
                    class="mt-1 p-0"
                    @click="downloadTemplate"
                  />
                </div>

                <div v-if="importErrors.length > 0" class="p-3 bg-red-50 dark:bg-red-900/20 rounded-md">
                  <p class="text-sm font-medium text-red-600 dark:text-red-400 mb-2">
                    Errores durante la importacion:
                  </p>
                  <ul class="text-xs text-red-600 dark:text-red-400 list-disc pl-4 max-h-32 overflow-y-auto">
                    <li v-for="(error, i) in importErrors" :key="i">
                      {{ error }}
                    </li>
                  </ul>
                </div>
              </div>

              <template #footer>
                <div class="flex justify-end gap-2">
                  <UButton variant="ghost" label="Cancelar" @click="showImportModal = false" />
                  <UButton
                    :loading="importing"
                    icon="i-lucide-upload"
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
