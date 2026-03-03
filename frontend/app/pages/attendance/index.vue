<script setup lang="ts">
import type { AttendanceInput } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const academicStore = useAcademicStore()
const { getAttendance, saveAttendance } = useAttendance()
const toast = useToast()

const loading = ref(true)
const saving = ref(false)
const selectedGroup = ref<number | undefined>(undefined)
const selectedDate = ref(new Date().toISOString().split('T')[0])
const records = ref<any[]>([])

const statusOptions = [
  { value: 'present', label: 'Presente', short: 'P', activeClass: 'bg-green-500 text-white' },
  { value: 'absent', label: 'Ausente', short: 'A', activeClass: 'bg-red-500 text-white' },
  { value: 'late', label: 'Tardanza', short: 'T', activeClass: 'bg-yellow-500 text-white' },
  { value: 'excused', label: 'Excusa', short: 'E', activeClass: 'bg-blue-500 text-white' }
]

// Transform items for USelectMenu (workaround for label-key bug)
const groupItems = computed(() =>
  academicStore.groups.map(g => ({ value: g.id, label: g.full_name || g.name }))
)

const fetchRecords = async () => {
  if (!selectedGroup.value || !selectedDate.value) {
    records.value = []
    return
  }

  loading.value = true
  try {
    records.value = await getAttendance(selectedGroup.value, selectedDate.value)
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo cargar la asistencia', color: 'error' })
  } finally {
    loading.value = false
  }
}

const handleSave = async () => {
  if (!selectedGroup.value || !academicStore.activePeriod) return

  saving.value = true
  try {
    const attendanceRecords: AttendanceInput[] = records.value
      .filter(r => r.status)
      .map(r => ({
        student_id: r.student_id,
        status: r.status,
        observation: r.observation
      }))

    if (!attendanceRecords.length) {
      toast.add({ title: 'Atención', description: 'No hay registros para guardar', color: 'warning' })
      saving.value = false
      return
    }

    await saveAttendance(
      selectedGroup.value,
      academicStore.activePeriod.id,
      selectedDate.value!,
      attendanceRecords
    )
    toast.add({ title: 'Éxito', description: 'Asistencia guardada correctamente', color: 'primary' })
    fetchRecords()
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo guardar la asistencia', color: 'error' })
  } finally {
    saving.value = false
  }
}

const setAllStatus = (status: string) => {
  records.value = records.value.map(r => ({ ...r, status }))
}

const summary = computed(() => {
  const total = records.value.length
  const present = records.value.filter(r => r.status === 'present').length
  const absent = records.value.filter(r => r.status === 'absent').length
  const late = records.value.filter(r => r.status === 'late').length
  const excused = records.value.filter(r => r.status === 'excused').length
  const pending = records.value.filter(r => !r.status).length

  return { total, present, absent, late, excused, pending }
})

onMounted(async () => {
  await Promise.all([
    academicStore.fetchGroups(),
    academicStore.fetchPeriods()
  ])
  loading.value = false
})

watch([selectedGroup, selectedDate], () => {
  fetchRecords()
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Control de Asistencia">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>

        <template #right>
          <UButton
            icon="i-lucide-save"
            label="Guardar Asistencia"
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

            <UFormField label="Fecha" required>
              <UInput
                v-model="selectedDate"
                type="date"
              />
            </UFormField>

            <UFormField label="Periodo Activo">
              <UInput
                :model-value="academicStore.activePeriod?.name || 'Sin periodo activo'"
                disabled
              />
            </UFormField>
          </div>
        </UPageCard>

        <!-- Quick Actions -->
        <div v-if="records.length" class="flex flex-wrap gap-2">
          <button
            v-for="option in statusOptions"
            :key="option.value"
            type="button"
            class="px-3 py-1.5 rounded-md text-sm font-medium transition-all"
            :class="option.activeClass"
            @click="setAllStatus(option.value)"
          >
            Todos {{ option.label }}
          </button>
        </div>

        <!-- Summary -->
        <div v-if="records.length" class="grid grid-cols-2 md:grid-cols-6 gap-4 sticky top-0 z-10 bg-gray-50 dark:bg-gray-900 py-2 -mx-6 px-6">
          <UPageCard variant="subtle" class="text-center">
            <p class="text-2xl font-bold">{{ summary.total }}</p>
            <p class="text-sm text-muted">Total</p>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <p class="text-2xl font-bold text-green-500">{{ summary.present }}</p>
            <p class="text-sm text-muted">Presentes</p>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <p class="text-2xl font-bold text-red-500">{{ summary.absent }}</p>
            <p class="text-sm text-muted">Ausentes</p>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <p class="text-2xl font-bold text-yellow-500">{{ summary.late }}</p>
            <p class="text-sm text-muted">Tardanzas</p>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <p class="text-2xl font-bold text-blue-500">{{ summary.excused }}</p>
            <p class="text-sm text-muted">Excusas</p>
          </UPageCard>
          <UPageCard variant="subtle" class="text-center">
            <p class="text-2xl font-bold text-gray-500">{{ summary.pending }}</p>
            <p class="text-sm text-muted">Pendientes</p>
          </UPageCard>
        </div>

        <!-- Attendance Table -->
        <UPageCard v-if="records.length" variant="subtle">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="border-b">
                  <th class="text-left p-3 font-medium">#</th>
                  <th class="text-left p-3 font-medium">Estudiante</th>
                  <th class="text-center p-3 font-medium">Estado</th>
                  <th class="text-left p-3 font-medium">Observación</th>
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
                  <td class="p-3">
                    <div class="flex justify-center gap-1">
                      <UTooltip
                        v-for="option in statusOptions"
                        :key="option.value"
                        :text="option.label"
                      >
                        <button
                          type="button"
                          class="w-8 h-8 rounded-md font-bold text-sm transition-all"
                          :class="[
                            record.status === option.value
                              ? option.activeClass
                              : 'bg-gray-100 dark:bg-gray-800 text-gray-500 hover:bg-gray-200 dark:hover:bg-gray-700'
                          ]"
                          @click="record.status = option.value"
                        >
                          {{ option.short }}
                        </button>
                      </UTooltip>
                    </div>
                  </td>
                  <td class="p-3">
                    <UInput
                      v-model="record.observation"
                      placeholder="Observación..."
                      size="sm"
                    />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </UPageCard>

        <!-- Empty State -->
        <UPageCard v-else-if="!loading && selectedGroup" variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-users" class="w-12 h-12 text-muted mx-auto mb-4" />
            <p class="text-muted">No hay estudiantes en este grupo</p>
          </div>
        </UPageCard>

        <!-- Instructions -->
        <UPageCard v-else-if="!loading" variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-calendar-check" class="w-12 h-12 text-primary mx-auto mb-4" />
            <h3 class="text-lg font-semibold mb-2">Control de Asistencia</h3>
            <p class="text-muted">Seleccione un grupo y fecha para registrar la asistencia</p>
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
