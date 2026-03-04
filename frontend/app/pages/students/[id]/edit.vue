<script setup lang="ts">
import type { Student } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const route = useRoute()
const router = useRouter()
const academicStore = useAcademicStore()
const { getStudent, updateStudent } = useAcademic()
const toast = useToast()

const loading = ref(true)
const saving = ref(false)
const student = ref<Student | null>(null)

const studentId = computed(() => Number(route.params.id))

const formData = ref({
  name: '',
  email: '',
  document_type: 'TI',
  document_number: '',
  phone: '',
  birth_date: '',
  address: '',
  group_id: undefined as number | undefined,
  enrollment_code: '',
  enrollment_date: '',
  status: 'active' as 'active' | 'inactive' | 'withdrawn' | 'graduated'
})

const documentTypes = [
  { value: 'CC', label: 'Cedula de Ciudadania' },
  { value: 'CE', label: 'Cedula de Extranjeria' },
  { value: 'TI', label: 'Tarjeta de Identidad' },
  { value: 'NUIP', label: 'NUIP' },
  { value: 'RC', label: 'Registro Civil' },
  { value: 'PAS', label: 'Pasaporte' }
]

const statusOptions = [
  { value: 'active', label: 'Activo' },
  { value: 'inactive', label: 'Inactivo' },
  { value: 'withdrawn', label: 'Retirado' },
  { value: 'graduated', label: 'Graduado' }
]

// Transform items for USelectMenu (workaround for label-key bug)
const groupItems = computed(() =>
  academicStore.groups.map(g => ({ value: g.id, label: g.full_name || g.name }))
)

const handleSave = async () => {
  if (!formData.value.name || !formData.value.document_number || !formData.value.group_id) {
    toast.add({ title: 'Error', description: 'Complete los campos requeridos', color: 'error' })
    return
  }

  saving.value = true
  try {
    await updateStudent(studentId.value, formData.value)
    toast.add({ title: 'Éxito', description: 'Estudiante actualizado correctamente', color: 'primary' })
    router.push(`/students/${studentId.value}`)
  } catch (error: any) {
    toast.add({
      title: 'Error',
      description: error?.message || 'No se pudo actualizar el estudiante',
      color: 'error'
    })
  } finally {
    saving.value = false
  }
}

onMounted(async () => {
  await academicStore.fetchGroups()

  try {
    student.value = await getStudent(studentId.value)
    if (student.value) {
      formData.value = {
        name: student.value.user?.name || '',
        email: student.value.user?.email || '',
        document_type: student.value.user?.document_type || 'TI',
        document_number: student.value.user?.document_number || '',
        phone: student.value.user?.phone || '',
        birth_date: student.value.user?.birth_date || '',
        address: student.value.user?.address || '',
        group_id: student.value.group_id,
        enrollment_code: student.value.enrollment_code || '',
        enrollment_date: student.value.enrollment_date || '',
        status: student.value.status
      }
    }
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo cargar el estudiante', color: 'error' })
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Editar Estudiante">
        <template #leading>
          <UButton
            :to="`/students/${studentId}`"
            icon="i-lucide-arrow-left"
            label="Volver"
            color="neutral"
            variant="ghost"
            size="sm"
          />
        </template>

        <template #right>
          <UButton
            icon="i-lucide-save"
            label="Guardar"
            color="primary"
            :loading="saving"
            :disabled="loading"
            @click="handleSave"
          />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <div v-if="loading" class="flex items-center justify-center py-12">
          <UIcon name="i-lucide-loader-2" class="w-8 h-8 animate-spin text-primary" />
        </div>

        <template v-else>
          <!-- Personal Information -->
          <UPageCard
            title="Informacion Personal"
            description="Datos personales del estudiante."
            variant="subtle"
          >
            <div class="space-y-4">
              <UFormField label="Nombre Completo" required>
                <UInput
                  v-model="formData.name"
                  placeholder="Nombre completo del estudiante"
                />
              </UFormField>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <UFormField label="Tipo Documento" required>
                  <USelectMenu
                    v-model="formData.document_type"
                    :items="documentTypes"
                    value-key="value"
                  />
                </UFormField>

                <UFormField label="Numero Documento" required>
                  <UInput
                    v-model="formData.document_number"
                    placeholder="Numero de documento"
                  />
                </UFormField>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <UFormField label="Fecha de Nacimiento">
                  <UInput
                    v-model="formData.birth_date"
                    type="date"
                  />
                </UFormField>

                <UFormField label="Telefono">
                  <UInput
                    v-model="formData.phone"
                    placeholder="Numero de telefono"
                  />
                </UFormField>
              </div>

              <UFormField label="Correo Electronico">
                <UInput
                  v-model="formData.email"
                  type="email"
                  placeholder="correo@ejemplo.com"
                />
              </UFormField>

              <UFormField label="Direccion">
                <UInput
                  v-model="formData.address"
                  placeholder="Direccion de residencia"
                />
              </UFormField>
            </div>
          </UPageCard>

          <!-- Academic Information -->
          <UPageCard
            title="Informacion Academica"
            description="Datos de matricula del estudiante."
            variant="subtle"
          >
            <div class="space-y-4">
              <UFormField label="Grupo" required>
                <USelectMenu
                  v-model="formData.group_id"
                  :items="groupItems"
                  value-key="value"
                  placeholder="Seleccionar grupo"
                />
              </UFormField>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <UFormField label="Codigo de Matricula">
                  <UInput
                    v-model="formData.enrollment_code"
                    placeholder="Codigo unico de matricula"
                  />
                </UFormField>

                <UFormField label="Fecha de Matricula">
                  <UInput
                    v-model="formData.enrollment_date"
                    type="date"
                  />
                </UFormField>
              </div>

              <UFormField label="Estado">
                <USelectMenu
                  v-model="formData.status"
                  :items="statusOptions"
                  value-key="value"
                />
              </UFormField>
            </div>
          </UPageCard>
        </template>
      </div>
    </template>
  </UDashboardPanel>
</template>
