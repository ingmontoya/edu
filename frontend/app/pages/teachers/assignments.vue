<script setup lang="ts">
import type { Teacher, Subject, Group, TeacherAssignment } from '~/types/school'

definePageMeta({ middleware: 'auth' })

const academicStore = useAcademicStore()
const { getTeachers, getTeacherAssignments, assignTeacher, unassignTeacher } = useAcademic()
const toast = useToast()

// State
const loading = ref(true)
const saving = ref(false)
const teachers = ref<Teacher[]>([])
const selectedTeacher = ref<Teacher | null>(null)
const assignments = ref<TeacherAssignment[]>([])
const showAssignModal = ref(false)

// Assignment form
const assignForm = ref({
  grade_id: undefined as number | undefined,
  subject_id: undefined as number | undefined,
  group_id: undefined as number | undefined
})

// Computed
const gradeItems = computed(() =>
  academicStore.grades.map(g => ({ value: g.id, label: g.name }))
)

const subjectItems = computed(() => {
  if (!assignForm.value.grade_id) return []
  return academicStore.subjects
    .filter(s => Number(s.grade_id) === Number(assignForm.value.grade_id))
    .map(s => ({ value: s.id, label: s.name }))
})

const groupItems = computed(() => {
  if (!assignForm.value.grade_id) return []
  return academicStore.groups
    .filter(g => Number(g.grade_id) === Number(assignForm.value.grade_id))
    .map(g => ({ value: g.id, label: g.full_name || `${g.grade?.name} ${g.name}` }))
})

// Methods
const fetchTeachers = async () => {
  loading.value = true
  try {
    const response = await getTeachers({ per_page: 200 })
    teachers.value = response.data
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron cargar los docentes', color: 'error' })
  } finally {
    loading.value = false
  }
}

const selectTeacher = async (teacher: Teacher) => {
  selectedTeacher.value = teacher
  loading.value = true
  try {
    assignments.value = await getTeacherAssignments(teacher.id)
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron cargar las asignaciones', color: 'error' })
  } finally {
    loading.value = false
  }
}

const openAssignModal = () => {
  assignForm.value = {
    grade_id: undefined,
    subject_id: undefined,
    group_id: undefined
  }
  showAssignModal.value = true
}

const handleAssign = async () => {
  if (!assignForm.value.subject_id || !assignForm.value.group_id) {
    toast.add({ title: 'Error', description: 'Seleccione asignatura y grupo', color: 'error' })
    return
  }

  if (!selectedTeacher.value || !academicStore.activeYear) return

  saving.value = true
  try {
    await assignTeacher(selectedTeacher.value.id, {
      subject_id: assignForm.value.subject_id,
      group_id: assignForm.value.group_id,
      academic_year_id: academicStore.activeYear.id
    })
    toast.add({ title: 'Exito', description: 'Asignacion creada', color: 'success' })
    showAssignModal.value = false
    selectTeacher(selectedTeacher.value)
  } catch (error: any) {
    toast.add({ title: 'Error', description: error.message || 'No se pudo crear la asignacion', color: 'error' })
  } finally {
    saving.value = false
  }
}

const handleUnassign = async (assignment: TeacherAssignment) => {
  if (!selectedTeacher.value) return
  if (!confirm('¿Eliminar esta asignacion?')) return

  try {
    await unassignTeacher(selectedTeacher.value.id, assignment.id)
    toast.add({ title: 'Exito', description: 'Asignacion eliminada', color: 'success' })
    selectTeacher(selectedTeacher.value)
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo eliminar la asignacion', color: 'error' })
  }
}

// Watchers
watch(() => assignForm.value.grade_id, () => {
  assignForm.value.subject_id = undefined
  assignForm.value.group_id = undefined
})

// Initialize
onMounted(async () => {
  await Promise.all([
    fetchTeachers(),
    academicStore.fetchGrades(),
    academicStore.fetchSubjects(),
    academicStore.fetchGroups()
  ])
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Asignaciones de Docentes" description="Asignar docentes a asignaturas y grupos">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex gap-6 p-6 h-full">
        <!-- Teachers List -->
        <div class="w-80 flex-shrink-0">
          <UPageCard title="Docentes" variant="subtle" class="h-full">
            <div class="space-y-2 max-h-[calc(100vh-250px)] overflow-y-auto">
              <div
                v-for="teacher in teachers"
                :key="teacher.id"
                :class="[
                  'p-3 rounded-lg cursor-pointer transition-colors',
                  selectedTeacher?.id === teacher.id
                    ? 'bg-primary-100 dark:bg-primary-900 border-primary-500 border'
                    : 'hover:bg-neutral-100 dark:hover:bg-neutral-800 border border-transparent'
                ]"
                @click="selectTeacher(teacher)"
              >
                <p class="font-medium">{{ teacher.user?.name }}</p>
                <p class="text-xs text-muted">{{ teacher.specialization || 'Sin especialidad' }}</p>
                <UBadge variant="subtle" size="xs" class="mt-1">
                  {{ teacher.assignments?.length || 0 }} asignaciones
                </UBadge>
              </div>

              <div v-if="teachers.length === 0 && !loading" class="text-center py-8 text-muted">
                No hay docentes registrados
              </div>
            </div>
          </UPageCard>
        </div>

        <!-- Assignments Panel -->
        <div class="flex-1">
          <UPageCard v-if="!selectedTeacher" variant="subtle" class="h-full">
            <div class="flex items-center justify-center h-64 text-muted">
              <div class="text-center">
                <UIcon name="i-lucide-user" class="w-12 h-12 mx-auto mb-3" />
                <p>Seleccione un docente para ver y gestionar sus asignaciones</p>
              </div>
            </div>
          </UPageCard>

          <UPageCard v-else variant="subtle">
            <template #header>
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="font-semibold text-lg">{{ selectedTeacher.user?.name }}</h3>
                  <p class="text-sm text-muted">{{ selectedTeacher.specialization || 'Sin especialidad' }}</p>
                </div>
                <UButton
                  icon="i-lucide-plus"
                  label="Nueva Asignacion"
                  @click="openAssignModal"
                />
              </div>
            </template>

            <div v-if="loading" class="flex justify-center py-8">
              <UIcon name="i-lucide-loader-2" class="w-8 h-8 animate-spin text-primary" />
            </div>

            <div v-else-if="assignments.length === 0" class="text-center py-12 text-muted">
              <UIcon name="i-lucide-book-open" class="w-12 h-12 mx-auto mb-3" />
              <p>Este docente no tiene asignaciones</p>
              <p class="text-sm">Haga clic en "Nueva Asignacion" para agregar</p>
            </div>

            <div v-else class="space-y-3">
              <div
                v-for="assignment in assignments"
                :key="assignment.id"
                class="flex items-center justify-between p-4 border rounded-lg"
              >
                <div>
                  <p class="font-medium">{{ assignment.subject?.name }}</p>
                  <p class="text-sm text-muted">
                    {{ assignment.group?.grade?.name }} {{ assignment.group?.name }}
                    <span v-if="assignment.subject?.area">
                      - {{ assignment.subject.area.name }}
                    </span>
                  </p>
                </div>
                <UButton
                  icon="i-lucide-trash"
                  color="error"
                  variant="ghost"
                  size="sm"
                  @click="handleUnassign(assignment)"
                />
              </div>
            </div>
          </UPageCard>
        </div>
      </div>

      <!-- Assign Modal -->
      <UModal v-model:open="showAssignModal">
        <template #content>
          <UCard>
            <template #header>
              <h3 class="text-lg font-semibold">Nueva Asignacion</h3>
              <p class="text-sm text-muted">Asignar a {{ selectedTeacher?.user?.name }}</p>
            </template>

            <div class="space-y-4">
              <UFormField label="Grado" required>
                <USelectMenu
                  v-model="assignForm.grade_id"
                  :items="gradeItems"
                  value-key="value"
                  placeholder="Seleccionar grado"
                />
              </UFormField>

              <UFormField label="Asignatura" required>
                <USelectMenu
                  v-model="assignForm.subject_id"
                  :items="subjectItems"
                  value-key="value"
                  placeholder="Seleccionar asignatura"
                  :disabled="!assignForm.grade_id"
                />
              </UFormField>

              <UFormField label="Grupo" required>
                <USelectMenu
                  v-model="assignForm.group_id"
                  :items="groupItems"
                  value-key="value"
                  placeholder="Seleccionar grupo"
                  :disabled="!assignForm.grade_id"
                />
              </UFormField>
            </div>

            <template #footer>
              <div class="flex justify-end gap-2">
                <UButton variant="ghost" label="Cancelar" @click="showAssignModal = false" />
                <UButton
                  :loading="saving"
                  label="Asignar"
                  @click="handleAssign"
                />
              </div>
            </template>
          </UCard>
        </template>
      </UModal>
    </template>
  </UDashboardPanel>
</template>
