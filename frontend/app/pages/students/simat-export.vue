<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Exportación SIMAT">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <UPageCard variant="subtle">
          <template #header>
            <h3 class="font-semibold">
              Exportar datos SIMAT
            </h3>
          </template>

          <div class="space-y-4">
            <p class="text-sm text-muted">
              Genera un archivo CSV con los datos requeridos por el SIMAT (Sistema Integrado de Matrícula). Este archivo puede ser importado directamente en la plataforma del MEN.
            </p>

            <UFormField
              label="Año Académico"
              required
            >
              <USelectMenu
                v-model="selectedYearId"
                :items="yearOptions"
                placeholder="Seleccionar año"
                value-key="value"
                label-key="label"
                class="w-full"
              />
            </UFormField>

            <UAlert
              color="info"
              variant="soft"
              icon="i-lucide-info"
              title="Campos incluidos en el archivo"
              description="Código DANE, NIT, nombre institución, datos del estudiante, grado, grupo, municipio de residencia, estrato, discapacidad, etnia y EPS."
            />

            <UButton
              icon="i-lucide-download"
              :loading="downloading"
              :disabled="!selectedYearId"
              @click="handleExport"
            >
              Descargar CSV SIMAT
            </UButton>
          </div>
        </UPageCard>

        <UPageCard variant="subtle">
          <template #header>
            <h3 class="font-semibold">
              Completitud de datos
            </h3>
          </template>
          <p class="text-sm text-muted">
            Para una exportación completa, asegúrese de que cada estudiante tenga registrados los campos SIMAT (código SIMAT, estrato, EPS, etnia, municipio). Puede actualizar estos datos en el perfil de cada estudiante.
          </p>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>

<script setup lang="ts">
import type { AcademicYear } from '~/types/school'

const api = useApi()
const auth = useAuthStore()

const selectedYearId = ref<number | undefined>(undefined)
const downloading = ref(false)
const years = ref<AcademicYear[]>([])

const yearOptions = computed(() => years.value.map(y => ({ label: y.name, value: y.id })))

async function loadYears() {
  try {
    const res = await api.get<{ data: AcademicYear[] }>('/academic-years')
    years.value = res.data ?? []
    const active = years.value.find((y: AcademicYear) => y.is_active)
    if (active) selectedYearId.value = active.id ?? undefined
  } catch { /* ignore */ }
}

async function handleExport() {
  if (!selectedYearId.value) return
  const yearId = selectedYearId.value
  downloading.value = true
  try {
    const config = useRuntimeConfig()
    const response = await fetch(
      `${config.public.apiUrl}/students/simat-export?academic_year_id=${yearId}`,
      { headers: { Authorization: `Bearer ${auth.token}` } }
    )
    if (!response.ok) throw new Error('Error al exportar')
    const blob = await response.blob()
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = `simat_${new Date().toISOString().split('T')[0]}.csv`
    document.body.appendChild(a)
    a.click()
    document.body.removeChild(a)
    window.URL.revokeObjectURL(url)
  } catch (e: unknown) {
    console.error(e)
  } finally {
    downloading.value = false
  }
}

onMounted(loadYears)
</script>
