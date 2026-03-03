<script setup lang="ts">
import type { GradeRecordInput } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const academicStore = useAcademicStore()
const { getGradeRecords, saveGradeRecords, getPerformanceColor, getPerformanceLabel } = useGrades()
const { getSubjects } = useAcademic()
const toast = useToast()

const loading = ref(true)
const saving = ref(false)
const selectedGroup = ref<number | undefined>(undefined)
const selectedSubject = ref<number | undefined>(undefined)
const selectedPeriod = ref<number | undefined>(undefined)
const subjects = ref<any[]>([])
const records = ref<any[]>([])

// Transform items for USelectMenu (workaround for label-key bug)
const groupItems = computed(() =>
  academicStore.groups.map(g => ({ value: g.id, label: g.full_name || g.name }))
)
const subjectItems = computed(() =>
  subjects.value.map(s => ({ value: s.id, label: s.name }))
)
const periodItems = computed(() =>
  academicStore.periods.map(p => ({ value: p.id, label: p.name }))
)

const fetchSubjects = async () => {
  if (!selectedGroup.value) return

  const group = academicStore.groups.find(g => g.id === selectedGroup.value)
  if (!group) return

  try {
    // Pasar group_id para filtrar por asignaciones del docente
    subjects.value = await getSubjects({ grade_id: group.grade_id, group_id: selectedGroup.value })
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron cargar las asignaturas', color: 'error' })
  }
}

const fetchRecords = async () => {
  if (!selectedGroup.value || !selectedSubject.value || !selectedPeriod.value) {
    records.value = []
    return
  }

  loading.value = true
  try {
    records.value = await getGradeRecords(selectedGroup.value, selectedSubject.value, selectedPeriod.value)
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron cargar las notas', color: 'error' })
  } finally {
    loading.value = false
  }
}

const handleSave = async () => {
  if (!selectedSubject.value || !selectedPeriod.value) return

  saving.value = true
  try {
    const gradeRecords: GradeRecordInput[] = records.value.map(r => ({
      student_id: r.student_id,
      grade: r.grade,
      observations: r.observations,
      recommendations: r.recommendations
    }))

    await saveGradeRecords(selectedSubject.value, selectedPeriod.value, gradeRecords)
    toast.add({ title: 'Éxito', description: 'Notas guardadas correctamente', color: 'primary' })
    fetchRecords()
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudieron guardar las notas', color: 'error' })
  } finally {
    saving.value = false
  }
}

const updateGrade = (index: number, value: string) => {
  const numValue = parseFloat(value)
  if (isNaN(numValue)) {
    records.value[index].grade = null
  } else {
    records.value[index].grade = Math.min(5, Math.max(1, numValue))
  }
}

onMounted(async () => {
  await Promise.all([
    academicStore.fetchGroups(),
    academicStore.fetchPeriods()
  ])

  if (academicStore.activePeriod) {
    selectedPeriod.value = academicStore.activePeriod.id
  }

  loading.value = false
})

watch(selectedGroup, () => {
  selectedSubject.value = undefined
  records.value = []
  fetchSubjects()
})

watch([selectedGroup, selectedSubject, selectedPeriod], () => {
  fetchRecords()
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
            :disabled="!records.length"
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
            <UFormField label="Grupo" required>
              <USelectMenu
                v-model="selectedGroup"
                :items="groupItems"
                value-key="value"
                placeholder="Seleccionar grupo"
              />
            </UFormField>

            <UFormField label="Asignatura" required>
              <USelectMenu
                v-model="selectedSubject"
                :items="subjectItems"
                value-key="value"
                placeholder="Seleccionar asignatura"
                :disabled="!selectedGroup"
              />
            </UFormField>

            <UFormField label="Periodo" required>
              <USelectMenu
                v-model="selectedPeriod"
                :items="periodItems"
                value-key="value"
                placeholder="Seleccionar periodo"
              />
            </UFormField>
          </div>
        </UPageCard>

        <!-- Grade Entry Table -->
        <UPageCard v-if="records.length" variant="subtle">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b">
                  <th class="text-left p-3 font-medium">#</th>
                  <th class="text-left p-3 font-medium">Estudiante</th>
                  <th class="text-left p-3 font-medium">Documento</th>
                  <th class="text-center p-3 font-medium w-32">Nota</th>
                  <th class="text-center p-3 font-medium">Desempeño</th>
                  <th class="text-left p-3 font-medium">Observaciones</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(record, index) in records"
                  :key="record.student_id"
                  class="border-b hover:bg-gray-50 dark:hover:bg-gray-800"
                >
                  <td class="p-3 text-muted">{{ index + 1 }}</td>
                  <td class="p-3 font-medium">{{ record.student_name }}</td>
                  <td class="p-3 text-muted">{{ record.document_number }}</td>
                  <td class="p-3">
                    <UInput
                      :model-value="record.grade"
                      type="number"
                      min="1"
                      max="5"
                      step="0.1"
                      class="w-24 text-center"
                      @update:model-value="updateGrade(index, $event)"
                    />
                  </td>
                  <td class="p-3 text-center">
                    <UBadge
                      v-if="record.grade"
                      :color="getPerformanceColor(record.grade)"
                      variant="subtle"
                    >
                      {{ getPerformanceLabel(record.grade) }}
                    </UBadge>
                    <span v-else class="text-muted">-</span>
                  </td>
                  <td class="p-3">
                    <UInput
                      v-model="record.observations"
                      placeholder="Observaciones..."
                      class="w-full"
                    />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </UPageCard>

        <!-- Empty State -->
        <UPageCard v-else-if="!loading && selectedGroup && selectedSubject && selectedPeriod" variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-users" class="w-12 h-12 text-muted mx-auto mb-4" />
            <p class="text-muted">No hay estudiantes en este grupo</p>
          </div>
        </UPageCard>

        <!-- Instructions -->
        <UPageCard v-else-if="!loading" variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-file-edit" class="w-12 h-12 text-primary mx-auto mb-4" />
            <h3 class="text-lg font-semibold mb-2">Registrar Notas</h3>
            <p class="text-muted">Seleccione un grupo, asignatura y periodo para comenzar a registrar notas</p>
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
