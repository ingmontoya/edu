<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

const academicStore = useAcademicStore()
const toast = useToast()

const loading = ref(true)
const selectedGroup = ref<number | undefined>(undefined)
const selectedPeriod = ref<number | undefined>(undefined)

// Transform items for USelectMenu (workaround for label-key bug)
const groupItems = computed(() =>
  academicStore.groups.map(g => ({ value: g.id, label: g.full_name || g.name }))
)
const periodItems = computed(() =>
  academicStore.periods.map(p => ({ value: p.id, label: p.name }))
)

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
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Consolidado de Notas">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Selectors -->
        <UPageCard variant="subtle">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <UFormField label="Grupo" required>
              <USelectMenu
                v-model="selectedGroup"
                :items="groupItems"
                value-key="value"
                placeholder="Seleccionar grupo"
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

        <!-- Instructions -->
        <UPageCard v-if="!loading" variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-file-spreadsheet" class="w-12 h-12 text-primary mx-auto mb-4" />
            <h3 class="text-lg font-semibold mb-2">Consolidado de Notas</h3>
            <p class="text-muted">Seleccione un grupo y periodo para ver el consolidado de notas por estudiante</p>
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
