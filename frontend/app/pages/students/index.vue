<script setup lang="ts">
import type { Student } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const academicStore = useAcademicStore()
const { getStudents, deleteStudent, importStudentsFromCsv } = useAcademic()
const toast = useToast()

// Import modal state
const showImportModal = ref(false)
const importGroup = ref<number | undefined>(undefined)
const importFile = ref<File | null>(null)
const importing = ref(false)
const importErrors = ref<string[]>([])

const loading = ref(true)
const students = ref<Student[]>([])
const selectedGroup = ref<number | undefined>(undefined)
const searchQuery = ref('')
const currentPage = ref(1)
const perPage = ref(15)
const totalItems = ref(0)
const totalPages = ref(1)

const columns = [
  { accessorKey: 'user_name', header: 'Nombre' },
  { accessorKey: 'user_document', header: 'Documento' },
  { accessorKey: 'group_name', header: 'Grupo' },
  { accessorKey: 'status', header: 'Estado' },
  { accessorKey: 'actions', header: '' }
]

const statusColors: Record<string, 'primary' | 'secondary' | 'success' | 'info' | 'warning' | 'error' | 'neutral'> = {
  active: 'success',
  inactive: 'neutral',
  withdrawn: 'error',
  graduated: 'info'
}

const statusLabels: Record<string, string> = {
  active: 'Activo',
  inactive: 'Inactivo',
  withdrawn: 'Retirado',
  graduated: 'Graduado'
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

// Transform items for USelectMenu (workaround for label-key bug)
const groupItems = computed(() => [
  { value: null, label: 'Todos los grupos' },
  ...academicStore.groups.map(g => ({ value: g.id, label: g.full_name || g.name }))
])

const perPageOptions = [
  { value: 10, label: '10 por página' },
  { value: 15, label: '15 por página' },
  { value: 25, label: '25 por página' },
  { value: 50, label: '50 por página' }
]

const fetchStudents = async () => {
  loading.value = true
  try {
    const params: any = {
      page: currentPage.value,
      per_page: perPage.value
    }
    if (selectedGroup.value) {
      params.group_id = selectedGroup.value
    }
    if (searchQuery.value) {
      params.search = searchQuery.value
    }
    const response = await getStudents(params)
    students.value = response.data
    totalItems.value = response.meta.total
    totalPages.value = response.meta.last_page
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron cargar los estudiantes', color: 'error' })
  } finally {
    loading.value = false
  }
}

const handleDelete = async (student: Student) => {
  if (!confirm(`¿Está seguro que desea desactivar a ${student.user?.name}?`)) return

  try {
    await deleteStudent(student.id)
    toast.add({ title: 'Éxito', description: 'Estudiante desactivado', color: 'primary' })
    fetchStudents()
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo desactivar el estudiante', color: 'error' })
  }
}

const goToPage = (page: number) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page
  }
}

const debouncedSearch = useDebounceFn(() => {
  currentPage.value = 1
  fetchStudents()
}, 300)

onMounted(async () => {
  await academicStore.fetchGroups()
  await fetchStudents()
})

watch(selectedGroup, () => {
  currentPage.value = 1
  fetchStudents()
})

watch(currentPage, fetchStudents)

watch(perPage, () => {
  currentPage.value = 1
  fetchStudents()
})

watch(searchQuery, debouncedSearch)

// Import functions
const openImportModal = () => {
  importGroup.value = undefined
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
  if (!importGroup.value || !importFile.value) {
    toast.add({ title: 'Error', description: 'Seleccione un grupo y un archivo CSV', color: 'error' })
    return
  }

  importing.value = true
  importErrors.value = []

  try {
    const result = await importStudentsFromCsv(importGroup.value, importFile.value)
    toast.add({ title: 'Exito', description: result.message, color: 'success' })

    if (result.errors.length > 0) {
      importErrors.value = result.errors
    } else {
      showImportModal.value = false
      fetchStudents()
    }
  } catch (error: any) {
    toast.add({ title: 'Error', description: error.message || 'Error al importar', color: 'error' })
  } finally {
    importing.value = false
  }
}

const downloadTemplate = () => {
  const headers = 'nombre,documento_tipo,documento_numero,email,telefono,direccion,codigo_matricula'
  const example = 'Juan Perez,TI,1234567890,juan.perez@email.com,3001234567,Calle 123,EST001'
  const csv = headers + '\n' + example
  const blob = new Blob([csv], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = 'plantilla_estudiantes.csv'
  link.click()
  window.URL.revokeObjectURL(url)
}
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Estudiantes">
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
              to="/students/new"
              icon="i-lucide-plus"
              label="Nuevo Estudiante"
              color="primary"
            />
          </div>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Filters -->
        <UPageCard variant="subtle">
          <div class="flex flex-wrap gap-4 items-end">
            <UFormField label="Buscar" class="w-64">
              <UInput
                v-model="searchQuery"
                placeholder="Nombre o documento..."
              >
                <template #leading>
                  <UIcon name="i-lucide-search" class="w-4 h-4 text-muted" />
                </template>
              </UInput>
            </UFormField>
            <UFormField label="Filtrar por Grupo" class="w-64">
              <USelectMenu
                v-model="selectedGroup"
                :items="groupItems"
                value-key="value"
                placeholder="Seleccionar grupo"
              />
            </UFormField>
          </div>
        </UPageCard>

        <!-- Table -->
        <UPageCard variant="subtle">
          <UTable
            :columns="columns"
            :data="students"
            :loading="loading"
            :ui="{ tr: 'cursor-pointer hover:bg-elevated/50' }"
            @select="(_e, row) => navigateTo(`/students/${row.original.id}`)"
          >
            <template #user_name-cell="{ row }">
              <div class="flex items-center gap-3">
                <div
                  :class="[getAvatarColor(row.original.user?.name), 'w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-semibold shadow-sm']"
                >
                  {{ getInitials(row.original.user?.name) }}
                </div>
                <span class="font-medium">{{ row.original.user?.name }}</span>
              </div>
            </template>

            <template #user_document-cell="{ row }">
              {{ row.original.user?.document_number }}
            </template>

            <template #group_name-cell="{ row }">
              {{ row.original.group?.full_name || row.original.group?.name }}
            </template>

            <template #status-cell="{ row }">
              <UBadge :color="statusColors[row.original.status]" variant="subtle">
                {{ statusLabels[row.original.status] }}
              </UBadge>
            </template>

            <template #actions-cell="{ row }">
              <div class="flex gap-2 justify-end">
                <UButton
                  :to="`/students/${row.original.id}`"
                  icon="i-lucide-eye"
                  color="neutral"
                  variant="ghost"
                  size="xs"
                />
                <UButton
                  :to="`/students/${row.original.id}/edit`"
                  icon="i-lucide-edit"
                  color="neutral"
                  variant="ghost"
                  size="xs"
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
                Mostrando {{ students.length }} de {{ totalItems }} registros
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
      </div>

      <!-- Import Modal -->
      <UModal v-model:open="showImportModal">
        <template #content>
          <UCard>
            <template #header>
              <h3 class="text-lg font-semibold">
                Importar Estudiantes desde CSV
              </h3>
              <p class="text-sm text-muted">
                Suba un archivo CSV con los datos de los estudiantes
              </p>
            </template>

            <div class="space-y-4">
              <UFormField label="Grupo destino" required>
                <USelectMenu
                  v-model="importGroup"
                  :items="groupItems.filter(g => g.value !== null)"
                  value-key="value"
                  placeholder="Seleccionar grupo"
                />
              </UFormField>

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
                <p>nombre, documento_tipo, documento_numero, email, telefono, direccion, codigo_matricula</p>
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
    </template>
  </UDashboardPanel>
</template>
