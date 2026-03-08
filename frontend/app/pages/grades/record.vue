<script setup lang="ts">
import type { GradeActivity, ActivityType, Subject } from '~/types/school'
import { ActivityTypeLabels } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const academicStore = useAcademicStore()
const {
  getActivities, createActivity, deleteActivity,
  getActivityScores, saveActivityScores,
  getPerformanceColor, getPerformanceLabel
} = useGrades()
const { getSubjects } = useAcademic()
const toast = useToast()

// ── Selectors ────────────────────────────────────────────────────────────────

const loading = ref(false)
const saving = ref(false)
const selectedGroup = ref<number | undefined>(undefined)
const selectedSubject = ref<number | undefined>(undefined)
const subjects = ref<Subject[]>([])

const groupItems = computed(() =>
  academicStore.groups.map(g => ({ value: g.id, label: g.full_name || g.name }))
)
const subjectItems = computed(() =>
  subjects.value.map(s => ({ value: s.id, label: s.name }))
)

const activePeriodId = computed(() => academicStore.activePeriod?.id)

const isReady = computed(() =>
  selectedGroup.value && selectedSubject.value && activePeriodId.value
)

// ── Activities ────────────────────────────────────────────────────────────────

const activities = ref<GradeActivity[]>([])

// Map: activityId -> student_id -> score (local editable state)
const scoreMap = ref<Record<number, Record<number, number | null>>>({})

// students list (same order for all activities)
const students = ref<{ student_id: number, student_name: string, document_number: string }[]>([])

// ── New activity modal ───────────────────────────────────────────────────────

const showModal = ref(false)
const newActivity = ref({ name: '', type: 'tarea' as ActivityType, date: '' })
const creatingActivity = ref(false)

const activityTypeItems = Object.entries(ActivityTypeLabels).map(([value, label]) => ({ value, label }))

// ── Computed final grades (preview) ─────────────────────────────────────────

const finalGrades = computed(() => {
  const result: Record<number, number | null> = {}

  for (const student of students.value) {
    const sid = student.student_id
    let weightedSum = 0
    let totalWeight = 0

    for (const activity of activities.value) {
      const score = scoreMap.value[activity.id]?.[sid]
      if (score !== null && score !== undefined) {
        weightedSum += score * activity.weight
        totalWeight += activity.weight
      }
    }

    result[sid] = totalWeight > 0
      ? Math.round((weightedSum / (activities.value.reduce((s, a) => s + a.weight, 0) || 100)) * 10) / 10
      : null
  }

  return result
})

// ── Data fetching ────────────────────────────────────────────────────────────

const fetchSubjects = async () => {
  if (!selectedGroup.value) return

  const group = academicStore.groups.find(g => g.id === selectedGroup.value)
  if (!group) return

  try {
    const response = await getSubjects({ grade_id: group.grade_id, group_id: selectedGroup.value })
    subjects.value = response.data
  } catch {
    toast.add({ title: 'Error', description: 'No se pudieron cargar las asignaturas', color: 'error' })
  }
}

const fetchActivitiesAndScores = async () => {
  if (!isReady.value) {
    activities.value = []
    scoreMap.value = {}
    students.value = []
    return
  }

  loading.value = true
  try {
    activities.value = await getActivities(selectedSubject.value!, activePeriodId.value!)

    if (activities.value.length === 0) {
      students.value = []
      scoreMap.value = {}
      return
    }

    // Load scores from first activity to get student list + all scores
    const allScores: Record<number, Record<number, number | null>> = {}

    for (const activity of activities.value) {
      const res = await getActivityScores(activity.id, selectedGroup.value!)
      if (students.value.length === 0) {
        students.value = res.scores.map(s => ({
          student_id: s.student_id,
          student_name: s.student_name,
          document_number: s.document_number
        }))
      }
      const activityScores: Record<number, number | null> = {}
      for (const s of res.scores) {
        activityScores[s.student_id] = s.score
      }
      allScores[activity.id] = activityScores
    }

    scoreMap.value = allScores
  } catch {
    toast.add({ title: 'Error', description: 'No se pudieron cargar las actividades', color: 'error' })
  } finally {
    loading.value = false
  }
}

// ── Actions ───────────────────────────────────────────────────────────────────

const handleCreateActivity = async () => {
  if (!newActivity.value.name.trim()) {
    toast.add({ title: 'Error', description: 'El nombre es requerido', color: 'error' })
    return
  }

  creatingActivity.value = true
  try {
    await createActivity({
      subject_id: selectedSubject.value!,
      period_id: activePeriodId.value!,
      name: newActivity.value.name.trim(),
      type: newActivity.value.type,
      date: newActivity.value.date || undefined
    })
    newActivity.value = { name: '', type: 'tarea', date: '' }
    showModal.value = false
    await fetchActivitiesAndScores()
    toast.add({ title: 'Actividad creada', color: 'primary' })
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo crear la actividad', color: 'error' })
  } finally {
    creatingActivity.value = false
  }
}

const handleDeleteActivity = async (id: number) => {
  try {
    await deleteActivity(id)
    await fetchActivitiesAndScores()
    toast.add({ title: 'Actividad eliminada', color: 'primary' })
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo eliminar la actividad', color: 'error' })
  }
}

const updateScore = (activityId: number, studentId: number, value: string) => {
  if (!scoreMap.value[activityId]) {
    scoreMap.value[activityId] = {}
  }
  const num = parseFloat(value)
  scoreMap.value[activityId][studentId] = isNaN(num) ? null : Math.min(5, Math.max(1, num))
}

const handleSave = async () => {
  if (!isReady.value || activities.value.length === 0) return

  saving.value = true
  try {
    for (const activity of activities.value) {
      const scores = students.value.map(s => ({
        student_id: s.student_id,
        score: scoreMap.value[activity.id]?.[s.student_id] ?? null
      }))
      await saveActivityScores(activity.id, selectedGroup.value!, scores)
    }
    toast.add({ title: 'Notas guardadas correctamente', color: 'primary' })
  } catch {
    toast.add({ title: 'Error', description: 'No se pudieron guardar las notas', color: 'error' })
  } finally {
    saving.value = false
  }
}

// ── Lifecycle ─────────────────────────────────────────────────────────────────

onMounted(async () => {
  await Promise.all([
    academicStore.fetchGroups(),
    academicStore.fetchPeriods()
  ])
})

watch(selectedGroup, () => {
  selectedSubject.value = undefined
  activities.value = []
  scoreMap.value = {}
  students.value = []
  fetchSubjects()
})

watch([selectedGroup, selectedSubject], () => {
  fetchActivitiesAndScores()
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Registrar Notas">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>

        <template #right>
          <UButton
            icon="i-lucide-save"
            label="Guardar Notas"
            color="primary"
            :loading="saving"
            :disabled="!isReady || activities.length === 0"
            @click="handleSave"
          />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Selectors -->
        <UPageCard variant="subtle">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <UFormField label="Grupo" required class="w-full">
              <USelectMenu
                v-model="selectedGroup"
                :items="groupItems"
                value-key="value"
                placeholder="Seleccionar grupo"
                class="w-full"
              />
            </UFormField>

            <UFormField label="Asignatura" required class="w-full">
              <USelectMenu
                v-model="selectedSubject"
                :items="subjectItems"
                value-key="value"
                placeholder="Seleccionar asignatura"
                :disabled="!selectedGroup"
                class="w-full"
              />
            </UFormField>
          </div>
        </UPageCard>

        <!-- Activities bar -->
        <template v-if="isReady">
          <UPageCard variant="subtle">
            <div class="flex flex-wrap items-center gap-2">
              <span class="text-sm font-medium text-muted mr-1">Actividades:</span>

              <template v-if="loading">
                <USkeleton class="h-7 w-28 rounded-full" />
                <USkeleton class="h-7 w-24 rounded-full" />
              </template>

              <template v-else>
                <div
                  v-for="activity in activities"
                  :key="activity.id"
                  class="flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium bg-primary-50 dark:bg-primary-950 text-primary-700 dark:text-primary-300 border border-primary-200 dark:border-primary-800"
                >
                  <span>{{ activity.name }}</span>
                  <span class="text-xs opacity-60">({{ Math.round(activity.weight) }}%)</span>
                  <UButton
                    icon="i-lucide-x"
                    size="xs"
                    color="error"
                    variant="ghost"
                    class="ml-1 -mr-1 h-4 w-4"
                    @click="handleDeleteActivity(activity.id)"
                  />
                </div>

                <UButton
                  icon="i-lucide-plus"
                  label="Nueva Actividad"
                  size="xs"
                  variant="outline"
                  @click="showModal = true"
                />
              </template>
            </div>
          </UPageCard>

          <!-- Gradebook table -->
          <UPageCard v-if="!loading && activities.length > 0 && students.length > 0" variant="subtle">
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="border-b">
                    <th class="text-left p-3 font-medium w-8">
                      #
                    </th>
                    <th class="text-left p-3 font-medium min-w-40">
                      Estudiante
                    </th>
                    <th
                      v-for="activity in activities"
                      :key="activity.id"
                      class="text-center p-3 font-medium min-w-28"
                    >
                      <div class="flex flex-col items-center gap-0.5">
                        <span>{{ activity.name }}</span>
                        <span class="text-xs font-normal text-muted">{{ ActivityTypeLabels[activity.type] }} · {{ Math.round(activity.weight) }}%</span>
                      </div>
                    </th>
                    <th class="text-center p-3 font-medium w-28">
                      Nota Final
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="(student, index) in students"
                    :key="student.student_id"
                    class="border-b hover:bg-gray-50 dark:hover:bg-gray-800"
                  >
                    <td class="p-3 text-muted">
                      {{ index + 1 }}
                    </td>
                    <td class="p-3 font-medium">
                      {{ student.student_name }}
                    </td>
                    <td
                      v-for="activity in activities"
                      :key="activity.id"
                      class="p-2 text-center"
                    >
                      <UInput
                        :model-value="scoreMap[activity.id]?.[student.student_id] ?? undefined"
                        type="number"
                        min="1"
                        max="5"
                        step="0.1"
                        placeholder="—"
                        class="w-20 text-center mx-auto"
                        @update:model-value="updateScore(activity.id, student.student_id, String($event))"
                      />
                    </td>
                    <td class="p-3 text-center">
                      <div class="flex flex-col items-center gap-1">
                        <span class="font-semibold">{{ finalGrades[student.student_id] ?? '—' }}</span>
                        <UBadge
                          v-if="finalGrades[student.student_id] !== null && finalGrades[student.student_id] !== undefined"
                          :color="getPerformanceColor(finalGrades[student.student_id] ?? null)"
                          variant="subtle"
                          size="xs"
                        >
                          {{ getPerformanceLabel(finalGrades[student.student_id] ?? null) }}
                        </UBadge>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </UPageCard>

          <!-- No students -->
          <UPageCard v-else-if="!loading && activities.length > 0 && students.length === 0" variant="subtle">
            <div class="text-center py-12">
              <UIcon name="i-lucide-users" class="w-12 h-12 text-muted mx-auto mb-4" />
              <p class="text-muted">
                No hay estudiantes en este grupo
              </p>
            </div>
          </UPageCard>

          <!-- No activities yet -->
          <UPageCard v-else-if="!loading && activities.length === 0" variant="subtle">
            <div class="text-center py-12">
              <UIcon name="i-lucide-clipboard-list" class="w-12 h-12 text-primary mx-auto mb-4" />
              <h3 class="text-lg font-semibold mb-2">
                Sin actividades evaluativas
              </h3>
              <p class="text-muted mb-4">
                Crea la primera actividad evaluativa con el botón +
              </p>
              <UButton
                icon="i-lucide-plus"
                label="Nueva Actividad"
                color="primary"
                @click="showModal = true"
              />
            </div>
          </UPageCard>
        </template>

        <!-- Initial instructions -->
        <UPageCard v-else variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-file-edit" class="w-12 h-12 text-primary mx-auto mb-4" />
            <h3 class="text-lg font-semibold mb-2">
              Registrar Notas
            </h3>
            <p class="text-muted">
              Seleccione un grupo, asignatura y periodo para comenzar a registrar notas
            </p>
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>

  <!-- New Activity Modal -->
  <UModal v-model:open="showModal" title="Nueva Actividad Evaluativa">
    <template #body>
      <div class="flex flex-col gap-4">
        <UFormField label="Nombre" required>
          <UInput
            v-model="newActivity.name"
            placeholder="Ej: Quiz 1, Tarea parcial..."
            class="w-full"
          />
        </UFormField>

        <UFormField label="Tipo" required>
          <USelectMenu
            v-model="newActivity.type"
            :items="activityTypeItems"
            value-key="value"
            class="w-full"
          />
        </UFormField>

        <UFormField label="Fecha (opcional)">
          <UInput
            v-model="newActivity.date"
            type="date"
            class="w-full"
          />
        </UFormField>
      </div>
    </template>

    <template #footer>
      <div class="flex justify-end gap-2">
        <UButton label="Cancelar" variant="outline" @click="showModal = false" />
        <UButton
          label="Crear"
          color="primary"
          :loading="creatingActivity"
          @click="handleCreateActivity"
        />
      </div>
    </template>
  </UModal>
</template>
