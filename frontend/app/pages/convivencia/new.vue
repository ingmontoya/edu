<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Nuevo Registro de Convivencia">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton
            variant="ghost"
            label="Cancelar"
            to="/convivencia"
          />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <UPageCard variant="subtle">
          <form
            class="space-y-5"
            @submit.prevent="handleSubmit"
          >
            <!-- Student search -->
            <UFormField
              label="Estudiante"
              required
            >
              <UInput
                v-model="studentSearch"
                placeholder="Buscar estudiante por nombre o documento..."
                icon="i-lucide-search"
                @input="searchStudents"
              />
              <div
                v-if="studentResults.length > 0"
                class="mt-2 border border-default rounded-lg divide-y divide-default"
              >
                <div
                  v-for="s in studentResults"
                  :key="s.id"
                  class="px-3 py-2 cursor-pointer hover:bg-elevated/50 flex items-center justify-between"
                  :class="{ 'bg-primary/5': form.student_id === s.id }"
                  @click="selectStudent(s)"
                >
                  <div>
                    <span class="font-medium">{{ s.user?.name }}</span>
                    <span class="text-sm text-muted ml-2">{{ s.group?.grade?.name }} {{ s.group?.name }}</span>
                  </div>
                  <UIcon
                    v-if="form.student_id === s.id"
                    name="i-lucide-check"
                    class="size-4 text-primary"
                  />
                </div>
              </div>
              <p
                v-if="selectedStudentName"
                class="mt-1 text-sm text-primary"
              >
                Seleccionado: {{ selectedStudentName }}
              </p>
            </UFormField>

            <div class="grid grid-cols-2 gap-4">
              <UFormField
                label="Tipo de Situación"
                required
              >
                <USelectMenu
                  v-model="form.type"
                  :items="typeOptions"
                  placeholder="Seleccionar tipo"
                  value-key="value"
                  label-key="label"
                  class="w-full"
                />
              </UFormField>

              <UFormField
                label="Categoría"
                required
              >
                <USelectMenu
                  v-model="form.category"
                  :items="categoryOptions"
                  placeholder="Seleccionar categoría"
                  value-key="value"
                  label-key="label"
                  class="w-full"
                />
              </UFormField>
            </div>

            <UFormField
              label="Fecha"
              required
            >
              <UInput
                v-model="form.date"
                type="date"
                class="w-48"
              />
            </UFormField>

            <UFormField
              label="Descripción"
              required
            >
              <UTextarea
                v-model="form.description"
                placeholder="Describe la situación detalladamente..."
                :rows="4"
                class="w-full"
              />
            </UFormField>

            <UFormField label="Lugar">
              <UInput
                v-model="form.location"
                placeholder="Salón, patio, corredor..."
                class="w-full"
              />
            </UFormField>

            <UFormField label="Testigos">
              <UTextarea
                v-model="form.witnesses"
                placeholder="Nombres de testigos..."
                :rows="2"
                class="w-full"
              />
            </UFormField>

            <UFormField label="Acción Inmediata Tomada">
              <UTextarea
                v-model="form.action_taken"
                placeholder="Acción tomada en el momento..."
                :rows="2"
                class="w-full"
              />
            </UFormField>

            <UFormField label="Compromiso">
              <UTextarea
                v-model="form.commitment"
                placeholder="Compromiso firmado (si aplica)..."
                :rows="2"
                class="w-full"
              />
            </UFormField>

            <div class="flex items-center gap-2">
              <UCheckbox v-model="form.notify_guardian" />
              <label class="text-sm">Notificar al acudiente</label>
            </div>

            <UAlert
              v-if="errorMessage"
              color="error"
              variant="subtle"
              icon="i-lucide-alert-circle"
            >
              <template #title>
                {{ errorMessage }}
              </template>
            </UAlert>

            <div class="flex gap-3">
              <UButton
                type="submit"
                :loading="loading"
                :disabled="!form.student_id || !form.type || !form.category || !form.description || !form.date"
              >
                Guardar Registro
              </UButton>
              <UButton
                variant="ghost"
                to="/convivencia"
              >
                Cancelar
              </UButton>
            </div>
          </form>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>

<script setup lang="ts">
import type { Student } from '~/types/school'

const api = useApi()
const convivencia = useConvivencia()
const router = useRouter()

const studentSearch = ref('')
const studentResults = ref<Student[]>([])
const selectedStudentName = ref('')

const form = reactive({
  student_id: undefined as number | undefined,
  type: undefined as string | undefined,
  category: undefined as string | undefined,
  description: '',
  date: new Date().toISOString().split('T')[0],
  location: '',
  witnesses: '',
  action_taken: '',
  commitment: '',
  notify_guardian: true
})

const loading = ref(false)
const errorMessage = ref('')

const typeOptions = [
  { label: 'Tipo 1 — Conflicto o desacuerdo', value: 'type1' },
  { label: 'Tipo 2 — Agresión o acoso escolar', value: 'type2' },
  { label: 'Tipo 3 — Vulneración de derechos', value: 'type3' }
]

const categoryOptions = [
  { label: 'Verbal', value: 'verbal' },
  { label: 'Físico', value: 'physical' },
  { label: 'Psicológico', value: 'psychological' },
  { label: 'Ciberbullying', value: 'cyberbullying' },
  { label: 'Otro', value: 'other' }
]

let searchTimeout: ReturnType<typeof setTimeout>

function searchStudents() {
  clearTimeout(searchTimeout)
  if (studentSearch.value.length < 2) {
    studentResults.value = []
    return
  }
  searchTimeout = setTimeout(async () => {
    try {
      const res = await api.get<{ data: Student[] }>(`/students?search=${encodeURIComponent(studentSearch.value)}&per_page=8`)
      studentResults.value = res.data ?? []
    } catch { /* ignore */ }
  }, 300)
}

function selectStudent(student: Student) {
  form.student_id = student.id
  selectedStudentName.value = student.user?.name ?? ''
  studentResults.value = []
  studentSearch.value = ''
}

async function handleSubmit() {
  errorMessage.value = ''
  loading.value = true
  try {
    await convivencia.createRecord(form as Parameters<typeof convivencia.createRecord>[0])
    router.push('/convivencia')
  } catch (e: unknown) {
    const err = e as { data?: { message?: string } }
    errorMessage.value = err?.data?.message || 'Error al guardar el registro'
  } finally {
    loading.value = false
  }
}
</script>
