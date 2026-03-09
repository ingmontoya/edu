<script setup lang="ts">
definePageMeta({ middleware: 'auth' })

const router = useRouter()
const auth = useAuthStore()
const academicStore = useAcademicStore()
const { createRemedial, autoAssignRemedial } = useSiee()
const toast = useToast()

// State
const loading = ref(false)
const assigning = ref(false)
const createdRemedialId = ref<number | null>(null)

const activePeriodId = computed(() => academicStore.activePeriod?.id)

const form = ref({
  subject_id: undefined as number | undefined,
  group_id: undefined as number | undefined,
  title: '',
  description: '',
  type: 'recovery' as 'recovery' | 'reinforcement' | 'leveling',
  assigned_date: new Date().toISOString().split('T')[0],
  due_date: '',
  max_grade: 5.0
})

// Computed
const subjectItems = computed(() =>
  academicStore.subjects.map(s => ({ value: s.id, label: s.name }))
)

const groupItems = computed(() =>
  academicStore.groups.map(g => ({ value: g.id, label: g.full_name || `${g.grade?.name} ${g.name}` }))
)

const typeItems = [
  { value: 'recovery', label: 'Recuperacion', description: 'Para estudiantes que reprobaron' },
  { value: 'reinforcement', label: 'Refuerzo', description: 'Para fortalecer conocimientos' },
  { value: 'leveling', label: 'Nivelacion', description: 'Para nivelar con el grupo' }
]

const isValid = computed(() =>
  form.value.subject_id
  && activePeriodId.value
  && form.value.title.trim()
  && form.value.description.trim()
  && form.value.due_date
)

// Methods
const handleSubmit = async () => {
  if (!isValid.value) {
    toast.add({ title: 'Error', description: 'Complete todos los campos requeridos', color: 'error' })
    return
  }

  loading.value = true
  try {
    const remedial = await createRemedial({
      subject_id: form.value.subject_id!,
      title: form.value.title,
      description: form.value.description,
      type: form.value.type,
      assigned_date: form.value.assigned_date!,
      due_date: form.value.due_date,
      max_grade: form.value.max_grade,
      period_id: activePeriodId.value!,
      teacher_id: auth.user?.id ?? 0
    })
    createdRemedialId.value = remedial.id
    toast.add({ title: 'Exito', description: 'Actividad creada correctamente', color: 'success' })
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo crear la actividad', color: 'error' })
  } finally {
    loading.value = false
  }
}

const handleAutoAssign = async () => {
  if (!createdRemedialId.value || !form.value.group_id) {
    toast.add({ title: 'Error', description: 'Seleccione un grupo para asignar automaticamente', color: 'error' })
    return
  }

  assigning.value = true
  try {
    const result = await autoAssignRemedial(createdRemedialId.value, form.value.group_id)
    toast.add({
      title: 'Exito',
      description: `Se asignaron ${result.assigned_count} estudiantes automaticamente`,
      color: 'success'
    })
    router.push(`/siee/remedials/${createdRemedialId.value}/grade`)
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo asignar automaticamente', color: 'error' })
  } finally {
    assigning.value = false
  }
}

const handleSkipAssign = () => {
  if (createdRemedialId.value) {
    router.push(`/siee/remedials/${createdRemedialId.value}/grade`)
  } else {
    router.push('/siee/remedials')
  }
}

// Initialize
onMounted(async () => {
  await Promise.all([
    academicStore.fetchSubjects(),
    academicStore.fetchGroups(),
    academicStore.fetchPeriods()
  ])
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Nueva Actividad de Nivelacion" description="Crear actividad de recuperacion o refuerzo">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton
            variant="ghost"
            icon="i-lucide-arrow-left"
            label="Volver"
            to="/siee/remedials"
          />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Creation Form -->
        <UPageCard v-if="!createdRemedialId" title="Informacion de la Actividad" variant="subtle">
          <form class="flex flex-col gap-4" @submit.prevent="handleSubmit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <UFormField label="Asignatura" required>
                <USelectMenu
                  v-model="form.subject_id"
                  :items="subjectItems"
                  value-key="value"
                  placeholder="Seleccionar asignatura"
                />
              </UFormField>
            </div>

            <UFormField label="Titulo" required>
              <UInput
                v-model="form.title"
                placeholder="Titulo de la actividad"
              />
            </UFormField>

            <UFormField label="Descripcion" required>
              <UTextarea
                v-model="form.description"
                placeholder="Describa la actividad, objetivos y criterios de evaluacion..."
                :rows="4"
              />
            </UFormField>

            <UFormField label="Tipo de Actividad">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div
                  v-for="type in typeItems"
                  :key="type.value"
                  :class="[
                    'p-3 rounded-lg border-2 cursor-pointer transition-colors',
                    form.type === type.value
                      ? 'border-primary bg-primary-50'
                      : 'border-neutral-200 hover:border-neutral-300'
                  ]"
                  @click="form.type = type.value as 'recovery' | 'reinforcement' | 'leveling'"
                >
                  <p class="font-medium">
                    {{ type.label }}
                  </p>
                  <p class="text-xs text-muted">
                    {{ type.description }}
                  </p>
                </div>
              </div>
            </UFormField>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <UFormField label="Fecha de Asignacion">
                <UInput
                  v-model="form.assigned_date"
                  type="date"
                />
              </UFormField>

              <UFormField label="Fecha Limite" required>
                <UInput
                  v-model="form.due_date"
                  type="date"
                />
              </UFormField>

              <UFormField label="Nota Maxima">
                <UInput
                  v-model.number="form.max_grade"
                  type="number"
                  min="1"
                  max="10"
                  step="0.1"
                />
              </UFormField>
            </div>

            <div class="flex justify-end gap-2 pt-4">
              <UButton
                variant="ghost"
                label="Cancelar"
                to="/siee/remedials"
              />
              <UButton
                type="submit"
                icon="i-lucide-save"
                label="Crear Actividad"
                :loading="loading"
                :disabled="!isValid"
              />
            </div>
          </form>
        </UPageCard>

        <!-- Auto-assign Step -->
        <UPageCard v-else title="Asignar Estudiantes" variant="subtle">
          <div class="flex flex-col gap-4">
            <UAlert
              icon="i-lucide-check-circle"
              color="success"
              title="Actividad creada exitosamente"
              description="Ahora puede asignar estudiantes manualmente o automaticamente"
            />

            <UFormField label="Grupo para Asignacion Automatica">
              <USelectMenu
                v-model="form.group_id"
                :items="groupItems"
                value-key="value"
                placeholder="Seleccionar grupo"
              />
            </UFormField>

            <p class="text-sm text-muted">
              La asignacion automatica agregara todos los estudiantes del grupo seleccionado
              que tengan calificaciones bajas en la asignatura durante el periodo seleccionado.
            </p>

            <div class="flex justify-end gap-2 pt-4">
              <UButton
                variant="ghost"
                label="Omitir y calificar manualmente"
                @click="handleSkipAssign"
              />
              <UButton
                icon="i-lucide-users"
                label="Asignar Automaticamente"
                :loading="assigning"
                :disabled="!form.group_id"
                @click="handleAutoAssign"
              />
            </div>
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
