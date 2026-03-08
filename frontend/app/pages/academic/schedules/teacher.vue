<script setup lang="ts">
import type { Schedule } from '~/types/school'
import { DayLabels } from '~/types/school'

definePageMeta({ middleware: 'auth' })

const auth = useAuthStore()
const academicStore = useAcademicStore()
const { getTeacherSchedule } = useSchedules()
const toast = useToast()

const schedules = ref<Schedule[]>([])
const loading = ref(false)

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
    return toMins(s.start_time) < timeMins && timeMins < toMins(s.end_time)
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

async function load() {
  if (!auth.user) return
  const teacherId = (auth.user as { teacher?: { id: number } }).teacher?.id
  if (!teacherId) return
  loading.value = true
  try {
    const res = await getTeacherSchedule(teacherId, academicStore.activeYear?.id)
    schedules.value = res.data ?? []
  } catch {
    toast.add({ title: 'Error', description: 'No se pudo cargar el horario', color: 'error' })
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  if (!auth.isStaff) {
    await navigateTo('/guardian')
    return
  }
  await academicStore.fetchAcademicYears()
  await load()
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Mi Horario Semanal">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Loading -->
        <UPageCard v-if="loading" variant="subtle">
          <div class="flex justify-center py-16">
            <UIcon name="i-lucide-loader-2" class="animate-spin w-8 h-8 text-primary" />
          </div>
        </UPageCard>

        <!-- Empty state -->
        <UPageCard v-else-if="!schedules.length" variant="subtle">
          <div class="flex flex-col items-center justify-center py-16 text-center gap-3">
            <UIcon name="i-lucide-calendar-x" class="w-12 h-12 text-muted" />
            <p class="text-muted">
              No tienes clases asignadas aún
            </p>
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
                      :class="['border rounded p-1.5 h-full', getScheduleColor(getCell(day, time)!)]"
                      :style="{ minHeight: `${getRowSpan(getCell(day, time)!) * 64}px` }"
                    >
                      <p class="text-xs font-semibold leading-tight truncate">
                        {{ getCell(day, time)!.assignment?.subject?.name ?? '—' }}
                      </p>
                      <p class="text-xs truncate opacity-75">
                        {{ getCell(day, time)!.assignment?.group?.name ?? '' }}
                        {{ getCell(day, time)!.assignment?.group?.grade?.short_name ? `(${getCell(day, time)!.assignment?.group?.grade?.short_name})` : '' }}
                      </p>
                      <p v-if="getCell(day, time)!.classroom" class="text-xs truncate opacity-60">
                        {{ getCell(day, time)!.classroom }}
                      </p>
                      <p class="text-xs opacity-50 mt-0.5">
                        {{ getCell(day, time)!.start_time }} – {{ getCell(day, time)!.end_time }}
                      </p>
                    </div>
                  </template>

                  <!-- Empty cell (read-only, no add button) -->
                  <template v-else>
                    <div class="w-full h-full min-h-[64px] rounded border border-dashed border-default opacity-20" />
                  </template>
                </div>
              </div>
            </template>
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
