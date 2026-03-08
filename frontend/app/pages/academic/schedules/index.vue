<script setup lang="ts">
import type { Schedule, TeacherAssignment } from '~/types/school'
import { DayLabels } from '~/types/school'

definePageMeta({ middleware: 'auth' })

const auth = useAuthStore()
const academicStore = useAcademicStore()
const { getGroupSchedule, getGroupAssignments, createSchedule, updateSchedule, deleteSchedule } = useSchedules()
const toast = useToast()

// Filters
const selectedGroupId = ref<number | undefined>(undefined)
const selectedYearId = ref<number | undefined>(undefined)

// Data
const schedules = ref<Schedule[]>([])
const loading = ref(false)

// Modal state
const showModal = ref(false)
const editingSchedule = ref<Schedule | null>(null)
const formData = ref({
  teacher_assignment_id: undefined as number | undefined,
  day_of_week: 1,
  start_time: '08:00',
  end_time: '09:00',
  classroom: ''
})
const formError = ref('')
const formLoading = ref(false)

// Teacher assignments for the selected group
const groupAssignments = ref<TeacherAssignment[]>([])

// Time slots 07:00 to 18:00
const timeSlots = computed(() => {
  const slots: string[] = []
  for (let h = 7; h <= 18; h++) {
    slots.push(`${String(h).padStart(2, '0')}:00`)
  }
  return slots
})

const days = [1, 2, 3, 4, 5]

const getCell = (day: number, time: string): Schedule | null =>
  schedules.value.find(s => s.day_of_week === day && s.start_time === time) ?? null

const toMins = (t: string): number => {
  const parts = t.split(':')
  return Number(parts[0] ?? 0) * 60 + Number(parts[1] ?? 0)
}

const getRowSpan = (schedule: Schedule): number => {
  const startMins = toMins(schedule.start_time)
  const endMins = toMins(schedule.end_time)
  return Math.max(1, Math.ceil((endMins - startMins) / 60))
}

const isCoveredByPrev = (day: number, time: string): boolean => {
  const timeMins = toMins(time)
  return schedules.value.some((s) => {
    if (s.day_of_week !== day) return false
    const startMins = toMins(s.start_time)
    const endMins = toMins(s.end_time)
    return startMins < timeMins && timeMins < endMins
  })
}

const SUBJECT_COLORS = [
  { bg: 'bg-blue-50 dark:bg-blue-950', border: 'border-blue-200 dark:border-blue-800', text: 'text-blue-900 dark:text-blue-100' },
  { bg: 'bg-green-50 dark:bg-green-950', border: 'border-green-200 dark:border-green-800', text: 'text-green-900 dark:text-green-100' },
  { bg: 'bg-purple-50 dark:bg-purple-950', border: 'border-purple-200 dark:border-purple-800', text: 'text-purple-900 dark:text-purple-100' },
  { bg: 'bg-amber-50 dark:bg-amber-950', border: 'border-amber-200 dark:border-amber-800', text: 'text-amber-900 dark:text-amber-100' },
  { bg: 'bg-pink-50 dark:bg-pink-950', border: 'border-pink-200 dark:border-pink-800', text: 'text-pink-900 dark:text-pink-100' },
  { bg: 'bg-teal-50 dark:bg-teal-950', border: 'border-teal-200 dark:border-teal-800', text: 'text-teal-900 dark:text-teal-100' }
]

const fallbackColor = SUBJECT_COLORS[0]!

const subjectColorMap = computed(() => {
  const map: Record<number, typeof SUBJECT_COLORS[number]> = {}
  let idx = 0
  for (const s of schedules.value) {
    const subId = s.assignment?.subject?.id ?? 0
    if (!(subId in map)) {
      map[subId] = SUBJECT_COLORS[idx % SUBJECT_COLORS.length] ?? fallbackColor
      idx++
    }
  }
  return map
})

const getScheduleColor = (schedule: Schedule): string => {
  const subId = schedule.assignment?.subject?.id ?? 0
  const color = subjectColorMap.value[subId] ?? fallbackColor
  return `${color.bg} ${color.border} ${color.text}`
}

// Select menu items using academicStore (already cached)
const groupItems = computed(() =>
  academicStore.groups.map(g => ({ value: g.id, label: g.full_name || g.name }))
)

const yearItems = computed(() =>
  academicStore.academicYears.map(y => ({ value: y.id, label: y.name }))
)

const assignmentOptions = computed(() =>
  groupAssignments.value.map(a => ({
    label: `${a.subject?.name ?? 'Sin materia'} — ${a.teacher?.user?.name ?? 'Sin docente'}`,
    value: a.id
  }))
)

const dayOptions = [1, 2, 3, 4, 5].map(d => ({ label: DayLabels[d] ?? '', value: d }))

const timeOptions = computed(() => {
  const opts = []
  for (let h = 7; h <= 18; h++) {
    opts.push({ label: `${String(h).padStart(2, '0')}:00`, value: `${String(h).padStart(2, '0')}:00` })
  }
  return opts
})

const canEdit = computed(() => auth.isAdmin || auth.isCoordinator)

async function loadSchedules() {
  if (!selectedGroupId.value) return
  loading.value = true
  try {
    const [schedulesRes, assignmentsRes] = await Promise.all([
      getGroupSchedule(selectedGroupId.value, selectedYearId.value),
      getGroupAssignments(selectedGroupId.value, selectedYearId.value)
    ])
    schedules.value = schedulesRes.data ?? []
    groupAssignments.value = assignmentsRes.data ?? []
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo cargar el horario', color: 'error' })
  } finally {
    loading.value = false
  }
}

function openCreate(day: number, time: string) {
  editingSchedule.value = null
  formData.value = {
    teacher_assignment_id: undefined,
    day_of_week: day,
    start_time: time,
    end_time: `${String(Number(time.split(':')[0]) + 1).padStart(2, '0')}:00`,
    classroom: ''
  }
  formError.value = ''
  showModal.value = true
}

function openEdit(schedule: Schedule) {
  editingSchedule.value = schedule
  formData.value = {
    teacher_assignment_id: schedule.teacher_assignment_id,
    day_of_week: schedule.day_of_week,
    start_time: schedule.start_time,
    end_time: schedule.end_time,
    classroom: schedule.classroom ?? ''
  }
  formError.value = ''
  showModal.value = true
}

async function submitForm() {
  if (!formData.value.teacher_assignment_id) {
    formError.value = 'Seleccione una asignación docente'
    return
  }
  formLoading.value = true
  formError.value = ''
  try {
    const payload = {
      teacher_assignment_id: formData.value.teacher_assignment_id,
      day_of_week: formData.value.day_of_week,
      start_time: formData.value.start_time,
      end_time: formData.value.end_time,
      classroom: formData.value.classroom || null
    }
    if (editingSchedule.value) {
      await updateSchedule(editingSchedule.value.id, payload)
      toast.add({ title: 'Guardado', description: 'Bloque actualizado correctamente', color: 'primary' })
    } else {
      await createSchedule(payload)
      toast.add({ title: 'Creado', description: 'Bloque de horario creado', color: 'primary' })
    }
    showModal.value = false
    await loadSchedules()
  } catch (e: unknown) {
    const err = e as { data?: { message?: string } }
    formError.value = err?.data?.message ?? 'Error al guardar el horario'
  } finally {
    formLoading.value = false
  }
}

async function handleDelete() {
  if (!editingSchedule.value) return
  formLoading.value = true
  try {
    await deleteSchedule(editingSchedule.value.id)
    toast.add({ title: 'Eliminado', description: 'Bloque eliminado correctamente', color: 'primary' })
    showModal.value = false
    await loadSchedules()
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo eliminar el bloque', color: 'error' })
  } finally {
    formLoading.value = false
  }
}

watch([selectedGroupId, selectedYearId], () => {
  if (selectedGroupId.value) loadSchedules()
  else schedules.value = []
})

onMounted(async () => {
  if (!auth.isAdmin && !auth.isCoordinator) {
    await navigateTo(auth.isTeacher ? '/academic/schedules/teacher' : '/guardian')
    return
  }
  await Promise.all([
    academicStore.fetchGroups(),
    academicStore.fetchAcademicYears()
  ])
  // Default to active year
  if (academicStore.activeYear && !selectedYearId.value) {
    selectedYearId.value = academicStore.activeYear.id
  }
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Horarios de Clase">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton
            v-if="selectedGroupId && canEdit"
            icon="i-lucide-plus"
            label="Nuevo Bloque"
            color="primary"
            @click="openCreate(1, '08:00')"
          />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Filters -->
        <UPageCard variant="subtle">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <UFormField label="Grupo">
              <USelectMenu
                v-model="selectedGroupId"
                :items="groupItems"
                value-key="value"
                placeholder="Seleccionar grupo..."
              />
            </UFormField>
            <UFormField label="Año Académico">
              <USelectMenu
                v-model="selectedYearId"
                :items="yearItems"
                value-key="value"
                placeholder="Año académico..."
              />
            </UFormField>
          </div>
        </UPageCard>

        <!-- Empty state: no group selected -->
        <UPageCard v-if="!selectedGroupId" variant="subtle">
          <div class="flex flex-col items-center justify-center py-16 text-center gap-3">
            <UIcon name="i-lucide-calendar" class="w-12 h-12 text-muted" />
            <p class="text-muted">
              Selecciona un grupo para ver su horario semanal
            </p>
          </div>
        </UPageCard>

        <!-- Loading -->
        <UPageCard v-else-if="loading" variant="subtle">
          <div class="flex justify-center py-16">
            <UIcon name="i-lucide-loader-2" class="animate-spin w-8 h-8 text-primary" />
          </div>
        </UPageCard>

        <!-- Weekly grid -->
        <UPageCard v-else variant="subtle" class="overflow-x-auto">
          <div class="min-w-[700px]">
            <!-- Header row -->
            <div class="grid grid-cols-6 gap-1 mb-1">
              <div class="w-16" />
              <div
                v-for="day in days"
                :key="day"
                class="text-sm font-semibold text-center py-2 rounded bg-elevated"
              >
                {{ DayLabels[day] }}
              </div>
            </div>

            <!-- Time rows -->
            <template v-for="time in timeSlots" :key="time">
              <div class="grid grid-cols-6 gap-1 mb-1 min-h-[64px]">
                <!-- Time label -->
                <div class="w-16 text-xs text-muted text-right pr-3 pt-1 select-none">
                  {{ time }}
                </div>

                <!-- Day cells -->
                <div
                  v-for="day in days"
                  :key="day"
                  class="relative min-h-[64px]"
                >
                  <!-- Covered by a previous block spanning multiple rows -->
                  <template v-if="isCoveredByPrev(day, time)" />

                  <!-- Schedule block starting at this slot -->
                  <template v-else-if="getCell(day, time)">
                    <div
                      :class="['border rounded p-1.5 cursor-pointer h-full transition hover:opacity-90', getScheduleColor(getCell(day, time)!)]"
                      :style="{ minHeight: `${getRowSpan(getCell(day, time)!) * 64}px` }"
                      @click="canEdit && openEdit(getCell(day, time)!)"
                    >
                      <p class="text-xs font-semibold leading-tight truncate">
                        {{ getCell(day, time)!.assignment?.subject?.name ?? '—' }}
                      </p>
                      <p class="text-xs truncate opacity-75">
                        {{ getCell(day, time)!.assignment?.teacher?.user?.name ?? '' }}
                      </p>
                      <p v-if="getCell(day, time)!.classroom" class="text-xs truncate opacity-60">
                        {{ getCell(day, time)!.classroom }}
                      </p>
                      <p class="text-xs opacity-50 mt-0.5">
                        {{ getCell(day, time)!.start_time }} – {{ getCell(day, time)!.end_time }}
                      </p>
                    </div>
                  </template>

                  <!-- Empty cell -->
                  <template v-else>
                    <button
                      v-if="canEdit"
                      class="w-full h-full min-h-[64px] flex items-center justify-center rounded border border-dashed border-default text-muted hover:border-primary hover:text-primary transition opacity-0 hover:opacity-100"
                      @click="openCreate(day, time)"
                    >
                      <UIcon name="i-lucide-plus" class="w-4 h-4" />
                    </button>
                    <div v-else class="w-full h-full min-h-[64px] rounded border border-dashed border-default opacity-20" />
                  </template>
                </div>
              </div>
            </template>
          </div>
        </UPageCard>

        <!-- Modal -->
        <UModal v-model:open="showModal">
          <template #content>
            <UCard>
              <template #header>
                <h3 class="font-semibold">
                  {{ editingSchedule ? 'Editar bloque' : 'Nuevo bloque de horario' }}
                </h3>
              </template>

              <div class="space-y-4">
                <UAlert v-if="formError" color="error" :title="formError" />

                <UFormField label="Asignación (Docente - Materia)" required>
                  <USelectMenu
                    v-model="formData.teacher_assignment_id"
                    :items="assignmentOptions"
                    value-key="value"
                    placeholder="Seleccionar asignación..."
                    class="w-full"
                  />
                  <p v-if="!assignmentOptions.length" class="text-xs text-warning mt-1">
                    Sin asignaciones cargadas para este grupo.
                  </p>
                </UFormField>

                <UFormField label="Día" required>
                  <USelectMenu
                    v-model="formData.day_of_week"
                    :items="dayOptions"
                    value-key="value"
                    class="w-full"
                  />
                </UFormField>

                <div class="grid grid-cols-2 gap-4">
                  <UFormField label="Hora inicio" required>
                    <USelectMenu
                      v-model="formData.start_time"
                      :items="timeOptions"
                      value-key="value"
                      class="w-full"
                    />
                  </UFormField>
                  <UFormField label="Hora fin" required>
                    <USelectMenu
                      v-model="formData.end_time"
                      :items="timeOptions"
                      value-key="value"
                      class="w-full"
                    />
                  </UFormField>
                </div>

                <UFormField label="Aula (opcional)">
                  <UInput v-model="formData.classroom" placeholder="Ej: Aula 101, Lab Física" class="w-full" />
                </UFormField>
              </div>

              <template #footer>
                <div class="flex justify-between">
                  <UButton
                    v-if="editingSchedule"
                    color="error"
                    variant="ghost"
                    :loading="formLoading"
                    @click="handleDelete"
                  >
                    Eliminar
                  </UButton>
                  <div class="flex gap-2 ml-auto">
                    <UButton color="neutral" variant="ghost" @click="showModal = false">
                      Cancelar
                    </UButton>
                    <UButton color="primary" :loading="formLoading" @click="submitForm">
                      {{ editingSchedule ? 'Guardar' : 'Crear' }}
                    </UButton>
                  </div>
                </div>
              </template>
            </UCard>
          </template>
        </UModal>
      </div>
    </template>
  </UDashboardPanel>
</template>
