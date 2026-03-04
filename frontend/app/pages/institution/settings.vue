<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const institutionStore = useInstitutionStore()
const toast = useToast()

const loading = ref(true)
const saving = ref(false)

const formData = ref({
  name: '',
  nit: '',
  dane_code: '',
  address: '',
  phone: '',
  email: '',
  city: '',
  department: '',
  rector_name: ''
})

const handleSave = async () => {
  saving.value = true
  try {
    const result = await institutionStore.update(formData.value)
    if (result.success) {
      toast.add({ title: 'Éxito', description: 'Datos actualizados correctamente', color: 'primary' })
    } else {
      toast.add({ title: 'Error', description: result.message || 'Error desconocido', color: 'error' })
    }
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron guardar los datos', color: 'error' })
  } finally {
    saving.value = false
  }
}

onMounted(async () => {
  await institutionStore.fetch()

  if (institutionStore.institution) {
    formData.value = {
      name: institutionStore.institution.name || '',
      nit: institutionStore.institution.nit || '',
      dane_code: institutionStore.institution.dane_code || '',
      address: institutionStore.institution.address || '',
      phone: institutionStore.institution.phone || '',
      email: institutionStore.institution.email || '',
      city: institutionStore.institution.city || '',
      department: institutionStore.institution.department || '',
      rector_name: institutionStore.institution.rector_name || ''
    }
  }

  loading.value = false
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Configuracion de Institucion">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>

        <template #right>
          <UButton
            color="primary"
            :loading="saving"
            @click="handleSave"
          >
            <UIcon name="i-lucide-save" class="w-4 h-4 mr-2" />
            Guardar Cambios
          </UButton>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <div v-if="loading" class="flex items-center justify-center py-12">
          <UIcon name="i-lucide-loader-2" class="w-8 h-8 animate-spin text-primary" />
        </div>

        <template v-else>
          <!-- Basic Info -->
          <UPageCard
            title="Informacion Basica"
            description="Datos principales de la institucion educativa."
            variant="subtle"
          >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <UFormField label="Nombre de la Institucion" required class="md:col-span-2">
                <UInput v-model="formData.name" placeholder="Nombre completo de la institucion" />
              </UFormField>

              <UFormField label="NIT">
                <UInput v-model="formData.nit" placeholder="900.123.456-7" />
              </UFormField>

              <UFormField label="Codigo DANE">
                <UInput v-model="formData.dane_code" placeholder="108001001234" />
              </UFormField>

              <UFormField label="Nombre del Rector" class="md:col-span-2">
                <UInput v-model="formData.rector_name" placeholder="Nombre completo del rector" />
              </UFormField>
            </div>
          </UPageCard>

          <!-- Contact Info -->
          <UPageCard
            title="Informacion de Contacto"
            description="Datos de ubicacion y contacto de la institucion."
            variant="subtle"
          >
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <UFormField label="Direccion" class="md:col-span-2">
                <UInput v-model="formData.address" placeholder="Direccion completa" />
              </UFormField>

              <UFormField label="Ciudad">
                <UInput v-model="formData.city" placeholder="Ciudad" />
              </UFormField>

              <UFormField label="Departamento">
                <UInput v-model="formData.department" placeholder="Departamento" />
              </UFormField>

              <UFormField label="Telefono">
                <UInput v-model="formData.phone" placeholder="(601) 123-4567" />
              </UFormField>

              <UFormField label="Correo Electronico">
                <UInput v-model="formData.email" type="email" placeholder="contacto@colegio.edu.co" />
              </UFormField>
            </div>
          </UPageCard>

          <!-- Grading Scale Info -->
          <UPageCard
            title="Escala de Valoracion (Decreto 1290)"
            description="La escala de valoracion esta definida segun el Decreto 1290 del Ministerio de Educacion Nacional de Colombia."
            variant="subtle"
          >
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div class="p-4 rounded-lg bg-green-50 dark:bg-green-900/20 text-center">
                <p class="text-lg font-bold text-green-600">
                  Superior
                </p>
                <p class="text-sm text-green-600">
                  4.6 - 5.0
                </p>
              </div>
              <div class="p-4 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-center">
                <p class="text-lg font-bold text-blue-600">
                  Alto
                </p>
                <p class="text-sm text-blue-600">
                  4.0 - 4.5
                </p>
              </div>
              <div class="p-4 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 text-center">
                <p class="text-lg font-bold text-yellow-600">
                  Basico
                </p>
                <p class="text-sm text-yellow-600">
                  3.0 - 3.9
                </p>
              </div>
              <div class="p-4 rounded-lg bg-red-50 dark:bg-red-900/20 text-center">
                <p class="text-lg font-bold text-red-600">
                  Bajo
                </p>
                <p class="text-sm text-red-600">
                  1.0 - 2.9
                </p>
              </div>
            </div>
          </UPageCard>
        </template>
      </div>
    </template>
  </UDashboardPanel>
</template>
