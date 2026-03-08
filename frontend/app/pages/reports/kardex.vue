<script setup lang="ts">
import type { Enrollment, Student, AcademicYear } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const academicStore = useAcademicStore()
const auth = useAuthStore()
const { getEnrollments } = useEnrollments()
const { getStudents, getAcademicYears } = useAcademic()
const { downloadGradesCertificate } = useReports()
const toast = useToast()

if (import.meta.client && !auth.isAdmin && !auth.isCoordinator) {
  await navigateTo('/dashboard')
}

const loading = ref(true)
const academicYears = ref<AcademicYear[]>([])
const selectedAcademicYear = ref<number | undefined>(undefined)
const selectedGrade = ref<number | null>(null)

// Raw data
const allEnrollments = ref<Enrollment[]>([])
const allStudents = ref<Student[]>([])

// Download state
const downloadingStudentId = ref<number | null>(null)

const academicYearItems = computed(() => [
  { value: undefined, label: 'Seleccionar año académico' },
  ...academicYears.value.map(y => ({ value: y.id, label: y.name }))
])

const gradeItems = computed(() => [
  { value: null, label: 'Todos los programas' },
  ...academicStore.grades.map(g => ({ value: g.id, label: g.name }))
])

// Group enrollments by student
const studentEnrollmentMap = computed<Map<number, Enrollment[]>>(() => {
  const map = new Map<number, Enrollment[]>()
  for (const e of allEnrollments.value) {
    const sid = e.student_id
    if (!map.has(sid)) map.set(sid, [])
    map.get(sid)!.push(e)
  }
  return map
})

// Find student object by id from allStudents
const studentMap = computed<Map<number, Student>>(() => {
  const map = new Map<number, Student>()
  for (const s of allStudents.value) map.set(s.id, s)
  return map
})

// Build display rows — one per student
const studentRows = computed(() => {
  const rows: {
    student: Student
    enrollments: Enrollment[]
    totalCredits: number
    approvedCredits: number
    avgGrade: number | null
    programName: string
  }[] = []

  for (const [studentId, enrollments] of studentEnrollmentMap.value) {
    const student = studentMap.value.get(studentId)
    if (!student) continue

    // Determine program from enrollments (grade_id of the subject)
    const gradeId = enrollments[0]?.subject?.grade_id ?? null

    // Filter by selected grade if any
    if (selectedGrade.value !== null && gradeId !== selectedGrade.value) continue

    const programName = academicStore.grades.find(g => g.id === gradeId)?.name ?? '—'
    const totalCredits = enrollments.reduce((sum, e) => sum + (e.subject?.credits ?? 0), 0)
    const graded = enrollments.filter(e => e.final_grade != null)
    const approvedCredits = enrollments
      .filter(e => e.status === 'completed')
      .reduce((sum, e) => sum + (e.subject?.credits ?? 0), 0)
    // PAPA: promedio ponderado por créditos Σ(nota×créditos) / Σ(créditos)
    const totalGradedCredits = graded.reduce((sum, e) => sum + (e.subject?.credits ?? 1), 0)
    const avgGrade = graded.length && totalGradedCredits > 0
      ? graded.reduce((sum, e) => sum + (e.final_grade ?? 0) * (e.subject?.credits ?? 1), 0) / totalGradedCredits
      : null

    rows.push({ student, enrollments, totalCredits, approvedCredits, avgGrade, programName })
  }

  // Sort by student name
  rows.sort((a, b) => (a.student.user?.name ?? '').localeCompare(b.student.user?.name ?? ''))
  return rows
})

const summaryStats = computed(() => ({
  totalStudents: studentRows.value.length,
  totalEnrollments: allEnrollments.value.length,
  avgCredits: studentRows.value.length
    ? Math.round(studentRows.value.reduce((s, r) => s + r.totalCredits, 0) / studentRows.value.length)
    : 0
}))

const statusColors: Record<string, 'primary' | 'success' | 'neutral' | 'error'> = {
  enrolled: 'primary',
  completed: 'success',
  withdrawn: 'neutral',
  failed: 'error'
}

const statusLabels: Record<string, string> = {
  enrolled: 'Matriculado',
  completed: 'Aprobado',
  failed: 'Reprobado',
  withdrawn: 'Retirado'
}

const gradeColor = (grade: number | null) => {
  if (grade == null) return 'text-muted'
  if (grade >= 4.0) return 'text-success font-semibold'
  if (grade >= 3.0) return 'text-warning font-semibold'
  return 'text-error font-semibold'
}

const fetchData = async () => {
  if (!selectedAcademicYear.value) return
  loading.value = true
  try {
    const [enrollmentsRes, studentsRes] = await Promise.all([
      getEnrollments({ academic_year_id: selectedAcademicYear.value }),
      getStudents({ per_page: 500, status: 'active' })
    ])
    allEnrollments.value = enrollmentsRes
    allStudents.value = studentsRes.data
  } catch {
    toast.add({ title: 'Error', description: 'No se pudieron cargar los datos', color: 'error' })
  } finally {
    loading.value = false
  }
}

const handleDownload = async (studentId: number) => {
  downloadingStudentId.value = studentId
  try {
    await downloadGradesCertificate(studentId, { academicYearId: selectedAcademicYear.value })
    toast.add({ title: 'Listo', description: 'Certificado de notas descargado', color: 'success' })
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo generar el kardex', color: 'error' })
  } finally {
    downloadingStudentId.value = null
  }
}

onMounted(async () => {
  const [yearsRes] = await Promise.all([
    getAcademicYears({ per_page: 50 }),
    academicStore.fetchGrades()
  ])
  academicYears.value = yearsRes.data

  // Auto-select active/first year
  const active = yearsRes.data.find((y: AcademicYear) => y.is_active) ?? yearsRes.data[0]
  if (active) {
    selectedAcademicYear.value = active.id
    await fetchData()
  } else {
    loading.value = false
  }
})

watch(selectedAcademicYear, fetchData)
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Historial Académico">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <div class="flex items-center gap-2">
            <USelectMenu
              v-model="selectedAcademicYear"
              :items="academicYearItems"
              value-key="value"
              placeholder="Año académico"
              class="w-44"
            />
            <USelectMenu
              v-model="selectedGrade"
              :items="gradeItems"
              value-key="value"
              placeholder="Programa"
              class="w-52"
            />
          </div>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Empty / no year selected -->
        <div v-if="!selectedAcademicYear" class="py-20 text-center text-muted">
          <UIcon name="i-lucide-file-text" class="w-10 h-10 mx-auto mb-3 opacity-30" />
          <p class="text-sm">
            Selecciona un año académico para ver el kardex
          </p>
        </div>

        <template v-else>
          <!-- Stats summary -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <UPageCard variant="subtle" class="text-center">
              <p class="text-3xl font-bold text-highlighted">
                {{ summaryStats.totalStudents }}
              </p>
              <p class="text-sm text-muted mt-1">
                Estudiantes
              </p>
            </UPageCard>
            <UPageCard variant="subtle" class="text-center">
              <p class="text-3xl font-bold text-highlighted">
                {{ summaryStats.totalEnrollments }}
              </p>
              <p class="text-sm text-muted mt-1">
                Matrículas registradas
              </p>
            </UPageCard>
            <UPageCard variant="subtle" class="text-center">
              <p class="text-3xl font-bold text-highlighted">
                {{ summaryStats.avgCredits }}
              </p>
              <p class="text-sm text-muted mt-1">
                Créditos promedio por estudiante
              </p>
            </UPageCard>
          </div>

          <!-- Loading -->
          <div v-if="loading" class="py-16 text-center text-muted">
            <UIcon name="i-lucide-loader" class="w-8 h-8 mx-auto mb-3 animate-spin opacity-40" />
            <p class="text-sm">
              Cargando kardex...
            </p>
          </div>

          <!-- No data -->
          <div v-else-if="studentRows.length === 0" class="py-16 text-center text-muted">
            <UIcon name="i-lucide-users" class="w-10 h-10 mx-auto mb-3 opacity-30" />
            <p class="text-sm">
              No hay matrículas registradas para este año académico.
            </p>
          </div>

          <!-- Student kardex cards -->
          <div v-else class="space-y-4">
            <UPageCard
              v-for="row in studentRows"
              :key="row.student.id"
              variant="subtle"
            >
              <template #header>
                <div class="flex items-center justify-between gap-4 flex-wrap">
                  <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                      <span class="text-sm font-bold text-primary">
                        {{ (row.student.user?.name ?? '?').charAt(0).toUpperCase() }}
                      </span>
                    </div>
                    <div>
                      <p class="text-sm font-semibold text-highlighted">
                        {{ row.student.user?.name ?? '—' }}
                      </p>
                      <p class="text-xs text-muted">
                        {{ row.programName }} · {{ row.enrollments.length }} materia(s) · {{ row.totalCredits }} créditos
                      </p>
                    </div>
                  </div>
                  <div class="flex items-center gap-3">
                    <div v-if="row.avgGrade != null" class="text-right">
                      <p class="text-xs text-muted">
                        Promedio Ponderado
                      </p>
                      <p class="text-lg font-bold" :class="gradeColor(row.avgGrade)">
                        {{ row.avgGrade.toFixed(2) }}
                      </p>
                    </div>
                    <div class="text-right">
                      <p class="text-xs text-muted">
                        Créditos aprobados
                      </p>
                      <p class="text-base font-semibold text-success">
                        {{ row.approvedCredits }}
                      </p>
                    </div>
                    <UButton
                      icon="i-lucide-download"
                      label="Certificado de Notas"
                      size="sm"
                      color="primary"
                      variant="outline"
                      :loading="downloadingStudentId === row.student.id"
                      @click="handleDownload(row.student.id)"
                    />
                  </div>
                </div>
              </template>

              <!-- Enrollments table -->
              <UTable
                :columns="[
                  { accessorKey: 'subject', header: 'Materia' },
                  { accessorKey: 'credits', header: 'Créditos' },
                  { accessorKey: 'semester_number', header: 'Semestre' },
                  { accessorKey: 'final_grade', header: 'Nota Final' },
                  { accessorKey: 'status', header: 'Estado' }
                ]"
                :data="row.enrollments"
                :ui="{ root: 'text-sm' }"
              >
                <template #subject-cell="{ row: r }">
                  {{ r.original.subject?.name ?? '—' }}
                </template>
                <template #credits-cell="{ row: r }">
                  <UBadge variant="subtle" color="neutral" size="xs">
                    {{ r.original.subject?.credits ?? '—' }} cr.
                  </UBadge>
                </template>
                <template #semester_number-cell="{ row: r }">
                  Semestre {{ r.original.semester_number }}
                </template>
                <template #final_grade-cell="{ row: r }">
                  <span :class="gradeColor(r.original.final_grade ?? null)">
                    {{ r.original.final_grade != null ? Number(r.original.final_grade).toFixed(2) : '—' }}
                  </span>
                </template>
                <template #status-cell="{ row: r }">
                  <UBadge :color="statusColors[r.original.status]" variant="subtle" size="xs">
                    {{ statusLabels[r.original.status] }}
                  </UBadge>
                </template>
              </UTable>
            </UPageCard>
          </div>
        </template>
      </div>
    </template>
  </UDashboardPanel>
</template>
