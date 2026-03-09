<script setup lang="ts">
import type { Achievement, Student, StudentAchievement } from '~/types/school'

definePageMeta({ middleware: 'auth' })

const academicStore = useAcademicStore()
const { getAchievements, bulkRecordAchievements, getAchievementStatusLabel, getAchievementStatusColor } = useSiee()
const { getStudents } = useAcademic()
const toast = useToast()

// State
const loading = ref(true)
const saving = ref(false)
const achievements = ref<Achievement[]>([])
const students = ref<Student[]>([])
const records = ref<Record<number, Record<number, { status: string, observations: string }>>>({})

// Filters
const selectedGrade = ref<number | undefined>()
const selectedSubject = ref<number | undefined>()
const activePeriodId = computed(() => academicStore.activePeriod?.id)
const selectedGroup = ref<number | undefined>()
const selectedAchievement = ref<number | undefined>()

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

const groupItems = computed(() => {
  if (!selectedGrade.value) return []
  return academicStore.groups
    .filter(g => Number(g.grade_id) === Number(selectedGrade.value))
    .map(g => ({ value: g.id, label: g.full_name || `${g.grade?.name} ${g.name}` }))
})

const achievementItems = computed(() =>
  achievements.value.map(a => ({ value: a.id, label: `${a.code || ''} - ${a.description.substring(0, 50)}...` }))
)

const statusOptions = [
  { value: 'pending', label: 'Pendiente' },
  { value: 'in_progress', label: 'En Progreso' },
  { value: 'achieved', label: 'Alcanzado' },
  { value: 'not_achieved', label: 'No Alcanzado' }
]

const currentAchievement = computed(() =>
  achievements.value.find(a => a.id === selectedAchievement.value)
)

// Stats
const stats = computed(() => {
  if (!selectedAchievement.value) return null
  const achievementRecords = records.value[selectedAchievement.value] || {}
  const total = students.value.length
  const achieved = Object.values(achievementRecords).filter(r => r.status === 'achieved').length
  const notAchieved = Object.values(achievementRecords).filter(r => r.status === 'not_achieved').length
  const inProgress = Object.values(achievementRecords).filter(r => r.status === 'in_progress').length
  const pending = total - achieved - notAchieved - inProgress

  return { total, achieved, notAchieved, inProgress, pending }
})

// Methods
const fetchAchievements = async () => {
  if (!selectedSubject.value || !activePeriodId.value) {
    achievements.value = []
    loading.value = false
    return
  }

  loading.value = true
  try {
    achievements.value = await getAchievements({
      subject_id: selectedSubject.value,
      period_id: activePeriodId.value
    })
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron cargar los logros', color: 'error' })
  } finally {
    loading.value = false
  }
}

const fetchStudents = async () => {
  if (!selectedGroup.value) {
    students.value = []
    return
  }

  loading.value = true
  try {
    const studentsResponse = await getStudents({ group_id: selectedGroup.value })
    students.value = studentsResponse.data || studentsResponse

    // Initialize records for current achievements
    achievements.value.forEach((a) => {
      if (!records.value[a.id]) {
        records.value[a.id] = {}
      }
      students.value.forEach((s) => {
        if (!records.value[a.id]![s.id]) {
          records.value[a.id]![s.id] = { status: 'pending', observations: '' }
        }
      })
    })
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron cargar los estudiantes', color: 'error' })
  } finally {
    loading.value = false
  }
}

const setAllStatus = (status: string) => {
  if (!selectedAchievement.value) return
  students.value.forEach((s) => {
    records.value[selectedAchievement.value!]![s.id]!.status = status
  })
}

const handleSave = async () => {
  if (!selectedAchievement.value) {
    toast.add({ title: 'Error', description: 'Seleccione un logro', color: 'error' })
    return
  }

  saving.value = true
  try {
    const achievementRecords = records.value[selectedAchievement.value] ?? {}
    const recordsToSave = Object.entries(achievementRecords).map(([studentId, data]) => ({
      student_id: parseInt(studentId),
      status: data.status,
      observations: data.observations || undefined
    }))

    await bulkRecordAchievements({
      achievement_id: selectedAchievement.value,
      records: recordsToSave
    })

    toast.add({ title: 'Exito', description: 'Logros registrados correctamente', color: 'success' })
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron guardar los registros', color: 'error' })
  } finally {
    saving.value = false
  }
}

// Watchers
watch(selectedGrade, () => {
  selectedSubject.value = undefined
  selectedGroup.value = undefined
  selectedAchievement.value = undefined
  achievements.value = []
  students.value = []
})

watch([selectedSubject], () => {
  selectedAchievement.value = undefined
  // Only fetch if both values are set
  if (selectedSubject.value && activePeriodId.value) {
    fetchAchievements()
  } else {
    achievements.value = []
  }
})

watch(selectedGroup, () => {
  if (selectedGroup.value) {
    fetchStudents()
  } else {
    students.value = []
  }
})

// Initialize
onMounted(async () => {
  await Promise.all([
    academicStore.fetchGrades(),
    academicStore.fetchGroups(),
    academicStore.fetchSubjects(),
    academicStore.fetchPeriods()
  ])
  loading.value = false
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Registro de Logros" description="Registrar alcance de logros por estudiante">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton
            v-if="selectedAchievement"
            icon="i-lucide-save"
            label="Guardar"
            :loading="saving"
            @click="handleSave"
          />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Filters -->
        <UPageCard title="Seleccionar Logro" variant="subtle">
          <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
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

            <UFormField label="Logro">
              <USelectMenu
                v-model="selectedAchievement"
                :items="achievementItems"
                value-key="value"
                placeholder="Seleccionar logro"
                :disabled="achievements.length === 0"
              />
            </UFormField>

            <UFormField label="Grupo">
              <USelectMenu
                v-model="selectedGroup"
                :items="groupItems"
                value-key="value"
                placeholder="Filtrar por grupo"
                :disabled="!selectedGrade"
              />
            </UFormField>
          </div>
        </UPageCard>

        <!-- Achievement Info -->
        <UPageCard v-if="currentAchievement" variant="subtle">
          <div class="flex items-start gap-4">
            <div class="flex-1">
              <h4 class="font-semibold">
                {{ currentAchievement.code }}
              </h4>
              <p class="text-muted mt-1">
                {{ currentAchievement.description }}
              </p>
              <div v-if="currentAchievement.indicators?.length" class="mt-3">
                <p class="text-sm font-medium mb-2">
                  Indicadores:
                </p>
                <ul class="text-sm text-muted space-y-1">
                  <li v-for="ind in currentAchievement.indicators" :key="ind.id">
                    <span class="font-mono">{{ ind.code }}:</span> {{ ind.description }}
                  </li>
                </ul>
              </div>
            </div>
            <div v-if="stats" class="grid grid-cols-2 gap-3 text-center">
              <div class="p-2 bg-success-50 rounded">
                <p class="text-lg font-bold text-success-600">
                  {{ stats.achieved }}
                </p>
                <p class="text-xs text-success-600">
                  Alcanzado
                </p>
              </div>
              <div class="p-2 bg-error-50 rounded">
                <p class="text-lg font-bold text-error-600">
                  {{ stats.notAchieved }}
                </p>
                <p class="text-xs text-error-600">
                  No Alcanzado
                </p>
              </div>
              <div class="p-2 bg-warning-50 rounded">
                <p class="text-lg font-bold text-warning-600">
                  {{ stats.inProgress }}
                </p>
                <p class="text-xs text-warning-600">
                  En Progreso
                </p>
              </div>
              <div class="p-2 bg-neutral-100 rounded">
                <p class="text-lg font-bold text-neutral-600">
                  {{ stats.pending }}
                </p>
                <p class="text-xs text-neutral-600">
                  Pendiente
                </p>
              </div>
            </div>
          </div>
        </UPageCard>

        <!-- No selection message -->
        <UPageCard v-if="!selectedAchievement || !selectedGroup" variant="subtle">
          <div class="flex items-center justify-center py-12 text-muted">
            <UIcon name="i-lucide-target" class="w-6 h-6 mr-2" />
            Seleccione grado, asignatura, periodo, logro y grupo para registrar
          </div>
        </UPageCard>

        <!-- Students Table -->
        <UPageCard v-if="selectedAchievement && selectedGroup" title="Registro por Estudiante" variant="subtle">
          <template #actions>
            <div class="flex gap-2">
              <UButton
                size="xs"
                variant="soft"
                color="success"
                @click="setAllStatus('achieved')"
              >
                Marcar todos Alcanzado
              </UButton>
              <UButton
                size="xs"
                variant="soft"
                color="error"
                @click="setAllStatus('not_achieved')"
              >
                Marcar todos No Alcanzado
              </UButton>
            </div>
          </template>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b">
                  <th class="text-left py-3 px-4 font-medium">
                    Estudiante
                  </th>
                  <th class="text-left py-3 px-4 font-medium w-48">
                    Estado
                  </th>
                  <th class="text-left py-3 px-4 font-medium">
                    Observaciones
                  </th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="student in students" :key="student.id" class="border-b hover:bg-neutral-50">
                  <td class="py-3 px-4">
                    <div class="flex items-center gap-3">
                      <UAvatar :alt="student.user?.name" size="sm" />
                      <div>
                        <p class="font-medium">
                          {{ student.user?.name }}
                        </p>
                        <p class="text-xs text-muted">
                          {{ student.enrollment_code }}
                        </p>
                      </div>
                    </div>
                  </td>
                  <td class="py-3 px-4">
                    <USelectMenu
                      v-model="records[selectedAchievement!]![student.id]!.status"
                      :items="statusOptions"
                      value-key="value"
                      size="sm"
                    />
                  </td>
                  <td class="py-3 px-4">
                    <UInput
                      v-model="records[selectedAchievement!]![student.id]!.observations"
                      placeholder="Observaciones..."
                      size="sm"
                    />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="students.length === 0 && !loading" class="text-center py-8 text-muted">
            No hay estudiantes en este grupo
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
