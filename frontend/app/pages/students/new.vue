<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const router = useRouter()
const academicStore = useAcademicStore()
const { createStudent } = useAcademic()
const toast = useToast()

const saving = ref(false)

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
  enrollment_date: new Date().toISOString().split('T')[0]
})

const documentTypes = [
  { value: 'CC', label: 'Cedula de Ciudadania' },
  { value: 'CE', label: 'Cedula de Extranjeria' },
  { value: 'TI', label: 'Tarjeta de Identidad' },
  { value: 'NUIP', label: 'NUIP' },
  { value: 'RC', label: 'Registro Civil' },
  { value: 'PAS', label: 'Pasaporte' }
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
    await createStudent(formData.value)
    toast.add({ title: 'Éxito', description: 'Estudiante creado correctamente', color: 'primary' })
    router.push('/students')
  } catch (error: any) {
    toast.add({
      title: 'Error',
      description: error?.message || 'No se pudo crear el estudiante',
      color: 'error'
    })
  } finally {
    saving.value = false
  }
}

onMounted(async () => {
  await academicStore.fetchGroups()
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Nuevo Estudiante">
        <template #leading>
          <UButton
            to="/students"
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
            @click="handleSave"
          />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
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
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
