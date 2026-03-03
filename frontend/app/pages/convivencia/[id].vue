<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar :title="`Registro de Convivencia #${id}`">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
        <template #right>
          <UButton
            variant="ghost"
            label="← Volver"
            to="/convivencia"
          />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div
        v-if="loading"
        class="flex items-center justify-center py-12"
      >
        <UIcon
          name="i-lucide-loader-2"
          class="size-8 animate-spin text-muted"
        />
      </div>

      <div
        v-else-if="record"
        class="grid grid-cols-1 lg:grid-cols-3 gap-6 p-6"
      >
        <!-- Main record -->
        <div class="lg:col-span-2 space-y-4">
          <UPageCard variant="subtle">
            <template #header>
              <div class="flex items-center justify-between">
                <h3 class="font-semibold">
                  Detalles
                </h3>
                <div class="flex gap-2">
                  <UBadge
                    :color="typeColor(record.type)"
                    variant="subtle"
                  >
                    {{ typeLabel(record.type) }}
                  </UBadge>
                  <UBadge
                    :color="statusColor(record.status)"
                    variant="subtle"
                  >
                    {{ statusLabel(record.status) }}
                  </UBadge>
                </div>
              </div>
            </template>

            <div class="space-y-3 text-sm">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <span class="text-muted">Estudiante:</span>
                  <span class="font-medium"> {{ record.student?.user?.name }}</span>
                </div>
                <div>
                  <span class="text-muted">Categoría:</span>
                  <span> {{ categoryLabel(record.category) }}</span>
                </div>
                <div>
                  <span class="text-muted">Fecha:</span>
                  <span> {{ formatDate(record.date) }}</span>
                </div>
                <div>
                  <span class="text-muted">Lugar:</span>
                  <span> {{ record.location || '—' }}</span>
                </div>
                <div>
                  <span class="text-muted">Reportado por:</span>
                  <span> {{ record.reporter?.name || '—' }}</span>
                </div>
                <div>
                  <span class="text-muted">Período:</span>
                  <span> {{ record.period?.name || '—' }}</span>
                </div>
              </div>

              <div>
                <p class="text-muted mb-1">
                  Descripción:
                </p>
                <p class="bg-elevated/30 rounded p-3">
                  {{ record.description }}
                </p>
              </div>

              <div v-if="record.witnesses">
                <p class="text-muted mb-1">
                  Testigos:
                </p>
                <p>{{ record.witnesses }}</p>
              </div>

              <div v-if="record.action_taken">
                <p class="text-muted mb-1">
                  Acción tomada:
                </p>
                <p class="bg-elevated/30 rounded p-3">
                  {{ record.action_taken }}
                </p>
              </div>

              <div v-if="record.commitment">
                <p class="text-muted mb-1">
                  Compromiso:
                </p>
                <p class="bg-elevated/30 rounded p-3">
                  {{ record.commitment }}
                </p>
              </div>
            </div>
          </UPageCard>

          <!-- Update status -->
          <UPageCard variant="subtle">
            <template #header>
              <h3 class="font-semibold">
                Actualizar Estado
              </h3>
            </template>
            <form
              class="space-y-4"
              @submit.prevent="handleUpdate"
            >
              <div class="grid grid-cols-2 gap-4">
                <UFormField label="Nuevo estado">
                  <USelectMenu
                    v-model="updateForm.status"
                    :items="statusOptions"
                    value-key="value"
                    label-key="label"
                    class="w-full"
                  />
                </UFormField>
              </div>
              <UFormField label="Resolución / Seguimiento">
                <UTextarea
                  v-model="updateForm.resolution"
                  :rows="3"
                  class="w-full"
                  placeholder="Describe las acciones de seguimiento y resolución..."
                />
              </UFormField>
              <UButton
                type="submit"
                :loading="updating"
                size="sm"
              >
                Guardar cambios
              </UButton>
            </form>
          </UPageCard>
        </div>

        <!-- Student history sidebar -->
        <div>
          <UPageCard variant="subtle">
            <template #header>
              <h3 class="font-semibold">
                Historial del Estudiante
              </h3>
            </template>
            <div class="space-y-2">
              <div
                v-for="h in history"
                :key="h.id"
                class="p-2 rounded border border-default text-xs"
                :class="{ 'border-primary': h.id === record.id }"
              >
                <div class="flex items-center justify-between mb-1">
                  <UBadge
                    :color="typeColor(h.type)"
                    variant="subtle"
                    size="xs"
                  >
                    {{ typeLabel(h.type) }}
                  </UBadge>
                  <UBadge
                    :color="statusColor(h.status)"
                    variant="subtle"
                    size="xs"
                  >
                    {{ statusLabel(h.status) }}
                  </UBadge>
                </div>
                <p class="text-muted">
                  {{ formatDate(h.date) }}
                </p>
                <NuxtLink
                  v-if="h.id !== record.id"
                  :to="`/convivencia/${h.id}`"
                  class="text-primary hover:underline text-xs"
                >
                  Ver detalle →
                </NuxtLink>
              </div>
              <p
                v-if="history.length === 0"
                class="text-sm text-muted text-center py-2"
              >
                Sin historial previo
              </p>
            </div>
          </UPageCard>
        </div>
      </div>
    </template>
  </UDashboardPanel>
</template>

<script setup lang="ts">
import type { DisciplinaryRecord, DisciplinaryRecordStatus } from '~/types/school'
import { DisciplinaryStatusLabels, DisciplinaryStatusColors } from '~/types/school'

type BadgeColor = 'error' | 'primary' | 'secondary' | 'success' | 'info' | 'warning' | 'neutral'

const route = useRoute()
const id = computed(() => Number(route.params.id))
const convivencia = useConvivencia()

const loading = ref(true)
const updating = ref(false)
const record = ref<DisciplinaryRecord | null>(null)
const history = ref<DisciplinaryRecord[]>([])

const updateForm = reactive({ status: '', resolution: '' })

const statusOptions = [
  { label: 'Abierto', value: 'open' },
  { label: 'En Proceso', value: 'in_process' },
  { label: 'Resuelto', value: 'resolved' },
  { label: 'Escalado', value: 'escalated' }
]

function typeLabel(type: string) {
  const m: Record<string, string> = { type1: 'Tipo 1', type2: 'Tipo 2', type3: 'Tipo 3' }
  return m[type] ?? type
}

function typeColor(type: string): BadgeColor {
  const colors: Record<string, BadgeColor> = { type1: 'info', type2: 'warning', type3: 'error' }
  return colors[type] ?? 'neutral'
}

function statusLabel(s: string) {
  return DisciplinaryStatusLabels[s as keyof typeof DisciplinaryStatusLabels] ?? s
}

function statusColor(s: string): BadgeColor {
  return (DisciplinaryStatusColors[s as keyof typeof DisciplinaryStatusColors] ?? 'neutral') as BadgeColor
}

function categoryLabel(c: string) {
  const m: Record<string, string> = {
    verbal: 'Verbal',
    physical: 'Físico',
    psychological: 'Psicológico',
    cyberbullying: 'Ciberbullying',
    other: 'Otro'
  }
  return m[c] ?? c
}

function formatDate(d: string) {
  return new Date(d).toLocaleDateString('es-CO')
}

async function load() {
  loading.value = true
  try {
    const res = await convivencia.getRecord(id.value)
    record.value = res.record
    history.value = res.student_history
    updateForm.status = res.record.status
    updateForm.resolution = res.record.resolution ?? ''
  } catch { /* ignore */ } finally {
    loading.value = false
  }
}

async function handleUpdate() {
  updating.value = true
  try {
    const updated = await convivencia.updateRecord(id.value, {
      status: updateForm.status as DisciplinaryRecordStatus,
      resolution: updateForm.resolution
    })
    record.value = updated
  } catch { /* ignore */ } finally {
    updating.value = false
  }
}

onMounted(load)
</script>
