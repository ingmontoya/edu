<script setup lang="ts">
import type { Enrollment, EnrollmentStatus, AcademicYear, Student, Subject } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const auth = useAuthStore()
const { getEnrollments, createEnrollment, updateEnrollment, deleteEnrollment, bulkCreateEnrollment, calculateFinalGrades } = useEnrollments()
const { getAcademicYears, getStudents, getSubjects } = useAcademic()
const toast = useToast()

// Access control (client-only: auth store is not hydrated on the server)
if (import.meta.client && !auth.isAdmin && !auth.isCoordinator) {
  await navigateTo('/dashboard')
}

const loading = ref(true)
const enrollments = ref<Enrollment[]>([])

// Filters
const filterAcademicYear = ref<number | undefined>(undefined)
const filterSemester = ref<number | undefined>(undefined)
const filterStatus = ref<string | undefined>(undefined)

// Form state
const showForm = ref(false)
const saving = ref(false)
const formData = ref({
  student_id: undefined as number | undefined,
  subject_id: undefined as number | undefined,
  academic_year_id: undefined as number | undefined,
  semester_number: undefined as number | undefined
})

// Data for selects
const academicYears = ref<AcademicYear[]>([])
const students = ref<Student[]>([])
const subjects = ref<Subject[]>([])

const semesterOptions = [
  { value: undefined, label: 'Todos los semestres' },
  { value: 1, label: 'Semestre 1' },
  { value: 2, label: 'Semestre 2' }
]

const semesterSelectOptions = [
  { value: 1, label: 'Semestre 1' },
  { value: 2, label: 'Semestre 2' }
]

const statusFilterOptions = [
  { value: undefined, label: 'Todos los estados' },
  { value: 'enrolled', label: 'Matriculado' },
  { value: 'completed', label: 'Completado' },
  { value: 'failed', label: 'Reprobado' },
  { value: 'withdrawn', label: 'Retirado' }
]

const academicYearFilterOptions = computed(() => [
  { value: undefined, label: 'Todos los años' },
  ...academicYears.value.map(y => ({ value: y.id, label: y.name }))
])

const academicYearSelectOptions = computed(() =>
  academicYears.value.map(y => ({ value: y.id, label: y.name }))
)

const studentSelectOptions = computed(() =>
  students.value.map(s => ({ value: s.id, label: s.user?.name ?? `Estudiante ${s.id}` }))
)

const subjectSelectOptions = computed(() =>
  subjects.value.map(s => ({
    value: s.id,
    label: s.credits ? `${s.name} (${s.credits} cr.)` : s.name
  }))
)

const statusColors: Record<EnrollmentStatus, 'primary' | 'success' | 'neutral' | 'error'> = {
  enrolled: 'primary',
  completed: 'success',
  withdrawn: 'neutral',
  failed: 'error'
}

const statusLabels: Record<EnrollmentStatus, string> = {
  enrolled: 'Matriculado',
  completed: 'Completado',
  failed: 'Reprobado',
  withdrawn: 'Retirado'
}

const fetchEnrollments = async () => {
  loading.value = true
  try {
    const params: Record<string, number | string | undefined> = {}
    if (filterAcademicYear.value) params.academic_year_id = filterAcademicYear.value
    if (filterSemester.value) params.semester_number = filterSemester.value
    if (filterStatus.value) params.status = filterStatus.value
    enrollments.value = await getEnrollments(params)
  } catch {
    toast.add({ title: 'Error', description: 'No se pudieron cargar las matrículas', color: 'error' })
  } finally {
    loading.value = false
  }
}

const handleCreate = async () => {
  if (!formData.value.student_id || !formData.value.subject_id || !formData.value.academic_year_id || !formData.value.semester_number) {
    toast.add({ title: 'Error', description: 'Complete todos los campos requeridos', color: 'error' })
    return
  }
  saving.value = true
  try {
    await createEnrollment(formData.value)
    toast.add({ title: 'Éxito', description: 'Matrícula creada correctamente', color: 'success' })
    showForm.value = false
    formData.value = { student_id: undefined, subject_id: undefined, academic_year_id: undefined, semester_number: undefined }
    await fetchEnrollments()
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo crear la matrícula', color: 'error' })
  } finally {
    saving.value = false
  }
}

const handleStatusChange = async (enrollment: Enrollment, status: EnrollmentStatus) => {
  try {
    await updateEnrollment(enrollment.id, { status })
    toast.add({ title: 'Éxito', description: 'Estado actualizado', color: 'success' })
    await fetchEnrollments()
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo actualizar el estado', color: 'error' })
  }
}

const handleDelete = async (enrollment: Enrollment) => {
  const name = enrollment.student?.user?.name ?? `Matrícula #${enrollment.id}`
  if (!confirm(`¿Está seguro que desea eliminar la matrícula de ${name}?`)) return
  try {
    await deleteEnrollment(enrollment.id)
    toast.add({ title: 'Éxito', description: 'Matrícula eliminada', color: 'success' })
    await fetchEnrollments()
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo eliminar la matrícula', color: 'error' })
  }
}

const openCreate = async () => {
  formData.value = { student_id: undefined, subject_id: undefined, academic_year_id: undefined, semester_number: undefined }
  showForm.value = true
  await fetchRefData()
}

// ── Bulk enrollment ────────────────────────────────────────────────────────
const showBulkForm = ref(false)
const savingBulk = ref(false)
const bulkData = ref({
  student_id: undefined as number | undefined,
  academic_year_id: undefined as number | undefined,
  semester_number: undefined as number | undefined,
  subject_ids: [] as number[]
})

const openBulk = async () => {
  bulkData.value = { student_id: undefined, academic_year_id: undefined, semester_number: undefined, subject_ids: [] }
  showBulkForm.value = true
  await fetchRefData()
}

const handleBulkCreate = async () => {
  const { student_id, academic_year_id, semester_number, subject_ids } = bulkData.value
  if (!student_id || !academic_year_id || !semester_number || subject_ids.length === 0) {
    toast.add({ title: 'Error', description: 'Completa todos los campos y selecciona al menos una materia', color: 'error' })
    return
  }
  savingBulk.value = true
  try {
    const result = await bulkCreateEnrollment({ student_id, academic_year_id, semester_number, subject_ids })
    toast.add({
      title: 'Matrículas creadas',
      description: `${result.created_count} matrículas creadas${result.skipped_count ? `, ${result.skipped_count} ya existían` : ''}`,
      color: 'success'
    })
    showBulkForm.value = false
    await fetchEnrollments()
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo completar la matrícula masiva', color: 'error' })
  } finally {
    savingBulk.value = false
  }
}

// ── Calculate finals ───────────────────────────────────────────────────────
const calculatingFinals = ref(false)

const handleCalculateFinals = async () => {
  if (!filterAcademicYear.value) {
    toast.add({ title: 'Error', description: 'Selecciona un año académico primero', color: 'error' })
    return
  }
  if (!confirm('¿Calcular notas finales para todas las matrículas activas en este año académico? Esta acción actualizará el estado a Completado o Reprobado.')) return
  calculatingFinals.value = true
  try {
    const result = await calculateFinalGrades(filterAcademicYear.value)
    toast.add({ title: 'Notas calculadas', description: result.message, color: 'success' })
    await fetchEnrollments()
  } catch {
    toast.add({ title: 'Error', description: 'No se pudieron calcular las notas finales', color: 'error' })
  } finally {
    calculatingFinals.value = false
  }
}

watch([filterAcademicYear, filterSemester, filterStatus], () => {
  fetchEnrollments()
})

const fetchRefData = async () => {
  if (students.value.length > 0) return // already loaded
  try {
    const [studentsRes, subjectsRes] = await Promise.all([
      getStudents({ per_page: 200 }),
      getSubjects({ per_page: 200 })
    ])
    students.value = studentsRes.data
    subjects.value = subjectsRes.data
  } catch {
    toast.add({ title: 'Error', description: 'No se pudieron cargar los datos de referencia', color: 'error' })
  }
}

onMounted(async () => {
  const [, yearsRes] = await Promise.all([
    fetchEnrollments(),
    getAcademicYears({ per_page: 100 }).catch(() => ({ data: [] as AcademicYear[] }))
  ])
  academicYears.value = yearsRes.data
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Matrículas">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <div class="flex items-center gap-2">
            <UButton
              icon="i-lucide-calculator"
              label="Calcular Notas Finales"
              color="neutral"
              variant="outline"
              :loading="calculatingFinals"
              @click="handleCalculateFinals"
            />
            <UButton
              icon="i-lucide-list-plus"
              label="Matrícula Semestre"
              color="neutral"
              variant="outline"
              @click="openBulk"
            />
            <UButton
              icon="i-lucide-plus"
              label="Nueva Matrícula"
              color="primary"
              @click="openCreate"
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
            <UFormField label="Año Académico" class="w-48">
              <USelectMenu
                v-model="filterAcademicYear"
                :items="academicYearFilterOptions"
                value-key="value"
                placeholder="Todos los años"
              />
            </UFormField>
            <UFormField label="Semestre" class="w-44">
              <USelectMenu
                v-model="filterSemester"
                :items="semesterOptions"
                value-key="value"
                placeholder="Todos"
              />
            </UFormField>
            <UFormField label="Estado" class="w-44">
              <USelectMenu
                v-model="filterStatus"
                :items="statusFilterOptions"
                value-key="value"
                placeholder="Todos"
              />
            </UFormField>
          </div>
        </UPageCard>

        <!-- New enrollment inline form -->
        <UPageCard v-if="showForm" variant="subtle">
          <template #header>
            <div class="flex items-center justify-between">
              <h3 class="font-semibold text-base">
                Nueva Matrícula
              </h3>
              <UButton
                icon="i-lucide-x"
                color="neutral"
                variant="ghost"
                size="xs"
                @click="showForm = false"
              />
            </div>
          </template>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <UFormField label="Estudiante" required>
              <USelectMenu
                v-model="formData.student_id"
                :items="studentSelectOptions"
                value-key="value"
                placeholder="Seleccionar estudiante"
                searchable
              />
            </UFormField>
            <UFormField label="Materia" required>
              <USelectMenu
                v-model="formData.subject_id"
                :items="subjectSelectOptions"
                value-key="value"
                placeholder="Seleccionar materia"
                searchable
              />
            </UFormField>
            <UFormField label="Año Académico" required>
              <USelectMenu
                v-model="formData.academic_year_id"
                :items="academicYearSelectOptions"
                value-key="value"
                placeholder="Seleccionar año"
              />
            </UFormField>
            <UFormField label="Semestre" required>
              <USelectMenu
                v-model="formData.semester_number"
                :items="semesterSelectOptions"
                value-key="value"
                placeholder="Seleccionar semestre"
              />
            </UFormField>
          </div>
          <template #footer>
            <div class="flex gap-2 justify-end">
              <UButton color="neutral" variant="ghost" @click="showForm = false">
                Cancelar
              </UButton>
              <UButton
                color="primary"
                :loading="saving"
                icon="i-lucide-save"
                @click="handleCreate"
              >
                Guardar Matrícula
              </UButton>
            </div>
          </template>
        </UPageCard>

        <!-- Bulk enrollment form -->
        <UPageCard v-if="showBulkForm" variant="subtle">
          <template #header>
            <div class="flex items-center justify-between">
              <h3 class="font-semibold text-base">
                Matrícula de Semestre Completo
              </h3>
              <UButton
                icon="i-lucide-x"
                color="neutral"
                variant="ghost"
                size="xs"
                @click="showBulkForm = false"
              />
            </div>
          </template>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
            <UFormField label="Estudiante" required>
              <USelectMenu
                v-model="bulkData.student_id"
                :items="studentSelectOptions"
                value-key="value"
                placeholder="Seleccionar estudiante"
                searchable
              />
            </UFormField>
            <UFormField label="Año Académico" required>
              <USelectMenu
                v-model="bulkData.academic_year_id"
                :items="academicYearSelectOptions"
                value-key="value"
                placeholder="Seleccionar año"
              />
            </UFormField>
            <UFormField label="Semestre" required>
              <USelectMenu
                v-model="bulkData.semester_number"
                :items="semesterSelectOptions"
                value-key="value"
                placeholder="Seleccionar semestre"
              />
            </UFormField>
          </div>
          <UFormField label="Materias a matricular" required>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2 mt-2">
              <label
                v-for="subject in subjects"
                :key="subject.id"
                class="flex items-center gap-2 p-2 rounded-lg border border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800/50 text-sm"
              >
                <input
                  v-model="bulkData.subject_ids"
                  type="checkbox"
                  :value="subject.id"
                  class="rounded"
                >
                <span class="truncate">{{ subject.name }}</span>
                <span v-if="subject.credits" class="text-xs text-muted ml-auto shrink-0">{{ subject.credits }}cr</span>
              </label>
            </div>
          </UFormField>
          <template #footer>
            <div class="flex gap-2 justify-end">
              <UButton color="neutral" variant="ghost" @click="showBulkForm = false">
                Cancelar
              </UButton>
              <UButton
                color="primary"
                :loading="savingBulk"
                icon="i-lucide-save"
                @click="handleBulkCreate"
              >
                Matricular {{ bulkData.subject_ids.length ? `(${bulkData.subject_ids.length} materias)` : '' }}
              </UButton>
            </div>
          </template>
        </UPageCard>

        <!-- Table -->
        <UPageCard variant="subtle">
          <div v-if="loading" class="py-12 text-center text-muted">
            Cargando matrículas...
          </div>
          <div v-else-if="enrollments.length === 0" class="py-12 text-center text-muted">
            No hay matrículas registradas con los filtros seleccionados.
          </div>
          <div v-else class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                  <th class="text-left py-3 px-4 font-medium text-gray-600 dark:text-gray-400">
                    Estudiante
                  </th>
                  <th class="text-left py-3 px-4 font-medium text-gray-600 dark:text-gray-400">
                    Materia
                  </th>
                  <th class="text-left py-3 px-4 font-medium text-gray-600 dark:text-gray-400">
                    Semestre
                  </th>
                  <th class="text-left py-3 px-4 font-medium text-gray-600 dark:text-gray-400">
                    Estado
                  </th>
                  <th class="text-left py-3 px-4 font-medium text-gray-600 dark:text-gray-400">
                    Nota Final
                  </th>
                  <th class="py-3 px-4" />
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="enrollment in enrollments"
                  :key="enrollment.id"
                  class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50"
                >
                  <td class="py-3 px-4">
                    <span class="font-medium">{{ enrollment.student?.user?.name ?? '—' }}</span>
                  </td>
                  <td class="py-3 px-4">
                    <div class="flex items-center gap-2">
                      <span>{{ enrollment.subject?.name ?? '—' }}</span>
                      <UBadge
                        v-if="enrollment.subject?.credits"
                        variant="subtle"
                        color="neutral"
                        size="xs"
                      >
                        {{ enrollment.subject.credits }} cr.
                      </UBadge>
                    </div>
                  </td>
                  <td class="py-3 px-4">
                    Semestre {{ enrollment.semester_number }}
                  </td>
                  <td class="py-3 px-4">
                    <UBadge :color="statusColors[enrollment.status]" variant="subtle">
                      {{ statusLabels[enrollment.status] }}
                    </UBadge>
                  </td>
                  <td class="py-3 px-4">
                    <span v-if="enrollment.final_grade != null" class="font-mono font-medium">
                      {{ Number(enrollment.final_grade).toFixed(1) }}
                    </span>
                    <span v-else class="text-muted">—</span>
                  </td>
                  <td class="py-3 px-4">
                    <div class="flex gap-2 justify-end">
                      <USelectMenu
                        :items="([
                          { value: 'enrolled', label: 'Matriculado' },
                          { value: 'completed', label: 'Completado' },
                          { value: 'failed', label: 'Reprobado' },
                          { value: 'withdrawn', label: 'Retirado' }
                        ] as { value: EnrollmentStatus, label: string }[])"
                        value-key="value"
                        placeholder="Cambiar estado"
                        class="w-36"
                        @update:model-value="(val: EnrollmentStatus) => handleStatusChange(enrollment, val)"
                      />
                      <UButton
                        v-if="enrollment.status === 'enrolled'"
                        icon="i-lucide-trash"
                        color="error"
                        variant="ghost"
                        size="xs"
                        @click="handleDelete(enrollment)"
                      />
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
