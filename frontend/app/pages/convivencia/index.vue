<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Convivencia Escolar">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton
            icon="i-lucide-plus"
            label="Nuevo Registro"
            to="/convivencia/new"
          />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Filters -->
        <div class="flex flex-wrap gap-3">
          <USelectMenu
            v-model="filters.type"
            :items="typeOptions"
            placeholder="Tipo de situación"
            value-key="value"
            label-key="label"
            class="w-52"
            @change="loadRecords"
          />
          <USelectMenu
            v-model="filters.status"
            :items="statusOptions"
            placeholder="Estado"
            value-key="value"
            label-key="label"
            class="w-40"
            @change="loadRecords"
          />
          <UInput
            v-model="filters.date_from"
            type="date"
            class="w-40"
            @change="loadRecords"
          />
          <UInput
            v-model="filters.date_to"
            type="date"
            class="w-40"
            @change="loadRecords"
          />
          <UButton
            variant="ghost"
            icon="i-lucide-x"
            label="Limpiar"
            @click="clearFilters"
          />
        </div>

        <!-- Table -->
        <UPageCard variant="subtle">
          <UTable
            :data="records"
            :columns="columns"
            :loading="loading"
            :ui="{ tr: 'cursor-pointer hover:bg-elevated/50' }"
            @select="(_e, row) => navigateTo(`/convivencia/${row.original.id}`)"
          >
            <template #type-cell="{ row }">
              <UBadge
                :color="typeColor(row.original.type)"
                variant="subtle"
                size="sm"
              >
                {{ typeLabel(row.original.type) }}
              </UBadge>
            </template>

            <template #status-cell="{ row }">
              <UBadge
                :color="statusColor(row.original.status)"
                variant="subtle"
                size="sm"
              >
                {{ statusLabel(row.original.status) }}
              </UBadge>
            </template>

            <template #student-cell="{ row }">
              <span>{{ row.original.student?.user?.name ?? '—' }}</span>
            </template>

            <template #date-cell="{ row }">
              <span>{{ formatDate(row.original.date) }}</span>
            </template>

            <template #actions-cell="{ row }">
              <UButton
                variant="ghost"
                icon="i-lucide-eye"
                size="xs"
                :to="`/convivencia/${row.original.id}`"
              />
            </template>
          </UTable>

          <!-- Pagination -->
          <div
            v-if="meta.last_page > 1"
            class="flex justify-end mt-4"
          >
            <UPagination
              v-model="page"
              :page-count="meta.per_page"
              :total="meta.total"
              @update:model-value="loadRecords"
            />
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>

<script setup lang="ts">
import type { DisciplinaryRecord } from '~/types/school'
import { DisciplinaryStatusLabels, DisciplinaryStatusColors } from '~/types/school'

type BadgeColor = 'error' | 'primary' | 'secondary' | 'success' | 'info' | 'warning' | 'neutral'

const convivencia = useConvivencia()

const loading = ref(false)
const records = ref<DisciplinaryRecord[]>([])
const page = ref(1)
const meta = ref({ last_page: 1, per_page: 15, total: 0 })

const filters = reactive({
  type: null as string | null,
  status: null as string | null,
  date_from: '',
  date_to: ''
})

const columns = [
  { accessorKey: 'date', header: 'Fecha' },
  { accessorKey: 'student', header: 'Estudiante' },
  { accessorKey: 'type', header: 'Tipo' },
  { accessorKey: 'status', header: 'Estado' },
  { accessorKey: 'actions', header: '' }
]

const typeOptions = [
  { label: 'Todos', value: null },
  { label: 'Tipo 1 — Conflicto', value: 'type1' },
  { label: 'Tipo 2 — Agresión/Acoso', value: 'type2' },
  { label: 'Tipo 3 — Vulneración', value: 'type3' }
]

const statusOptions = [
  { label: 'Todos', value: null },
  { label: 'Abierto', value: 'open' },
  { label: 'En Proceso', value: 'in_process' },
  { label: 'Resuelto', value: 'resolved' },
  { label: 'Escalado', value: 'escalated' }
]

function typeLabel(type: string) {
  const labels: Record<string, string> = { type1: 'Tipo 1', type2: 'Tipo 2', type3: 'Tipo 3' }
  return labels[type] ?? type
}

function typeColor(type: string): BadgeColor {
  const colors: Record<string, BadgeColor> = { type1: 'info', type2: 'warning', type3: 'error' }
  return colors[type] ?? 'neutral'
}

function statusLabel(status: string) {
  return DisciplinaryStatusLabels[status as keyof typeof DisciplinaryStatusLabels] ?? status
}

function statusColor(status: string): BadgeColor {
  return (DisciplinaryStatusColors[status as keyof typeof DisciplinaryStatusColors] ?? 'neutral') as BadgeColor
}

function formatDate(date: string) {
  return new Date(date).toLocaleDateString('es-CO')
}

function clearFilters() {
  filters.type = null
  filters.status = null
  filters.date_from = ''
  filters.date_to = ''
  page.value = 1
  loadRecords()
}

async function loadRecords() {
  loading.value = true
  try {
    const res = await convivencia.getRecords({
      ...(filters.type ? { type: filters.type } : {}),
      ...(filters.status ? { status: filters.status } : {}),
      ...(filters.date_from ? { date_from: filters.date_from } : {}),
      ...(filters.date_to ? { date_to: filters.date_to } : {}),
      page: page.value
    })
    records.value = res.data
    meta.value = res.meta
  } catch { /* ignore */ } finally {
    loading.value = false
  }
}

onMounted(loadRecords)
</script>
