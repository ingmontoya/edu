<script setup lang="ts">
import type { RemedialActivity, StudentRemedial, Student } from '~/types/school'

definePageMeta({ middleware: 'auth' })

const route = useRoute()
const router = useRouter()
const { getRemedial, gradeStudentRemedial, getRemedialStatusLabel, getRemedialStatusColor } = useSiee()
const { getStudents } = useAcademic()
const toast = useToast()

// State
const loading = ref(true)
const saving = ref(false)
const remedial = ref<RemedialActivity | null>(null)
const students = ref<Student[]>([])
const grades = ref<Record<number, { grade: number | null; observations: string; status: string }>>({})

// Computed
const remedialId = computed(() => Number(route.params.id))

const statusOptions = [
  { value: 'pending', label: 'Pendiente' },
  { value: 'in_progress', label: 'En Progreso' },
  { value: 'completed', label: 'Completado' },
  { value: 'passed', label: 'Aprobado' },
  { value: 'failed', label: 'Reprobado' }
]

const assignedStudents = computed(() => {
  if (!remedial.value?.student_remedials) return []
  return remedial.value.student_remedials.map(sr => ({
    ...sr,
    student: students.value.find(s => s.id === sr.student_id)
  }))
})

const stats = computed(() => {
  if (!remedial.value?.student_remedials) return null
  const total = remedial.value.student_remedials.length
  const passed = remedial.value.student_remedials.filter(sr => grades.value[sr.student_id]?.status === 'passed').length
  const failed = remedial.value.student_remedials.filter(sr => grades.value[sr.student_id]?.status === 'failed').length
  const pending = total - passed - failed

  return { total, passed, failed, pending }
})

// Methods
const fetchData = async () => {
  loading.value = true
  try {
    remedial.value = await getRemedial(remedialId.value)

    // Get unique student IDs
    const studentIds = remedial.value.student_remedials?.map(sr => sr.student_id) || []
    if (studentIds.length > 0) {
      // For now, fetch all students - in production you'd have a batch endpoint
      const studentsResponse = await getStudents({})
      students.value = studentsResponse.data || studentsResponse
    }

    // Initialize grades from existing data
    remedial.value.student_remedials?.forEach(sr => {
      grades.value[sr.student_id] = {
        grade: sr.grade,
        observations: sr.observations || '',
        status: sr.status
      }
    })
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo cargar la actividad', color: 'error' })
    router.push('/siee/remedials')
  } finally {
    loading.value = false
  }
}

const handleGradeChange = (studentId: number) => {
  const data = grades.value[studentId]
  if (!data || !remedial.value) return

  // Auto-determine status based on grade
  if (data.grade !== null) {
    const passingGrade = remedial.value.max_grade * 0.6 // 60% to pass
    if (data.grade >= passingGrade) {
      data.status = 'passed'
    } else {
      data.status = 'failed'
    }
  }
}

const saveGrade = async (studentRemedialId: number, studentId: number) => {
  const data = grades.value[studentId]
  if (!data) return

  saving.value = true
  try {
    await gradeStudentRemedial(studentRemedialId, {
      grade: data.grade,
      observations: data.observations,
      status: data.status
    })
    toast.add({ title: 'Guardado', description: 'Calificacion guardada', color: 'success' })
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo guardar', color: 'error' })
  } finally {
    saving.value = false
  }
}

const saveAllGrades = async () => {
  if (!remedial.value?.student_remedials) return

  saving.value = true
  try {
    for (const sr of remedial.value.student_remedials) {
      const data = grades.value[sr.student_id]
      if (data) {
        await gradeStudentRemedial(sr.id, {
          grade: data.grade,
          observations: data.observations,
          status: data.status
        })
      }
    }
    toast.add({ title: 'Exito', description: 'Todas las calificaciones guardadas', color: 'success' })
  } catch (error) {
    toast.add({ title: 'Error', description: 'Error al guardar algunas calificaciones', color: 'error' })
  } finally {
    saving.value = false
  }
}

// Initialize
onMounted(fetchData)
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Calificar Nivelacion" :description="remedial?.title || 'Cargando...'">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <div class="flex gap-2">
            <UButton
              variant="ghost"
              icon="i-lucide-arrow-left"
              label="Volver"
              to="/siee/remedials"
            />
            <UButton
              icon="i-lucide-save"
              label="Guardar Todo"
              :loading="saving"
              @click="saveAllGrades"
            />
          </div>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Loading -->
        <div v-if="loading" class="flex justify-center py-12">
          <UIcon name="i-lucide-loader-2" class="w-8 h-8 animate-spin text-primary" />
        </div>

        <template v-else-if="remedial">
          <!-- Remedial Info -->
          <UPageCard variant="subtle">
            <div class="flex items-start gap-6">
              <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                  <UBadge :color="getRemedialTypeColor(remedial.type)" size="sm">
                    {{ getRemedialStatusLabel(remedial.type) }}
                  </UBadge>
                </div>
                <h3 class="text-lg font-semibold">{{ remedial.title }}</h3>
                <p class="text-muted mt-1">{{ remedial.description }}</p>
                <div class="flex gap-4 mt-3 text-sm text-muted">
                  <span>
                    <strong>Asignatura:</strong> {{ remedial.subject?.name }}
                  </span>
                  <span>
                    <strong>Periodo:</strong> {{ remedial.period?.name }}
                  </span>
                  <span>
                    <strong>Nota Maxima:</strong> {{ remedial.max_grade }}
                  </span>
                  <span>
                    <strong>Fecha Limite:</strong> {{ new Date(remedial.due_date).toLocaleDateString() }}
                  </span>
                </div>
              </div>

              <div v-if="stats" class="grid grid-cols-2 gap-3 text-center">
                <div class="p-3 bg-success-50 rounded-lg">
                  <p class="text-xl font-bold text-success-600">{{ stats.passed }}</p>
                  <p class="text-xs text-success-600">Aprobados</p>
                </div>
                <div class="p-3 bg-error-50 rounded-lg">
                  <p class="text-xl font-bold text-error-600">{{ stats.failed }}</p>
                  <p class="text-xs text-error-600">Reprobados</p>
                </div>
                <div class="p-3 bg-warning-50 rounded-lg col-span-2">
                  <p class="text-xl font-bold text-warning-600">{{ stats.pending }}</p>
                  <p class="text-xs text-warning-600">Pendientes</p>
                </div>
              </div>
            </div>
          </UPageCard>

          <!-- No students -->
          <UPageCard v-if="assignedStudents.length === 0" variant="subtle">
            <div class="flex flex-col items-center justify-center py-12 text-muted">
              <UIcon name="i-lucide-users" class="w-12 h-12 mb-4" />
              <p class="text-lg font-medium mb-2">No hay estudiantes asignados</p>
              <p class="text-sm">Los estudiantes pueden ser asignados automaticamente o manualmente</p>
            </div>
          </UPageCard>

          <!-- Students Table -->
          <UPageCard v-else title="Estudiantes" variant="subtle">
            <div class="overflow-x-auto">
              <table class="w-full">
                <thead>
                  <tr class="border-b">
                    <th class="text-left py-3 px-4 font-medium">Estudiante</th>
                    <th class="text-left py-3 px-4 font-medium w-32">Nota</th>
                    <th class="text-left py-3 px-4 font-medium w-40">Estado</th>
                    <th class="text-left py-3 px-4 font-medium">Observaciones</th>
                    <th class="text-center py-3 px-4 font-medium w-20">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="sr in assignedStudents" :key="sr.id" class="border-b hover:bg-neutral-50">
                    <td class="py-3 px-4">
                      <div class="flex items-center gap-3">
                        <UAvatar :alt="sr.student?.user?.name" size="sm" />
                        <div>
                          <p class="font-medium">{{ sr.student?.user?.name || 'Estudiante' }}</p>
                          <p class="text-xs text-muted">{{ sr.student?.enrollment_code }}</p>
                        </div>
                      </div>
                    </td>
                    <td class="py-3 px-4">
                      <UInput
                        v-model.number="grades[sr.student_id].grade"
                        type="number"
                        :min="0"
                        :max="remedial.max_grade"
                        step="0.1"
                        size="sm"
                        @change="handleGradeChange(sr.student_id)"
                      />
                    </td>
                    <td class="py-3 px-4">
                      <USelectMenu
                        v-model="grades[sr.student_id].status"
                        :items="statusOptions"
                        value-key="value"
                        size="sm"
                      />
                    </td>
                    <td class="py-3 px-4">
                      <UInput
                        v-model="grades[sr.student_id].observations"
                        placeholder="Observaciones..."
                        size="sm"
                      />
                    </td>
                    <td class="py-3 px-4 text-center">
                      <UButton
                        icon="i-lucide-check"
                        variant="ghost"
                        size="xs"
                        :loading="saving"
                        @click="saveGrade(sr.id, sr.student_id)"
                      />
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </UPageCard>
        </template>
      </div>
    </template>
  </UDashboardPanel>
</template>
