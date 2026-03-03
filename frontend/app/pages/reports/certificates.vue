<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Constancias y Certificados">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <!-- Student selector -->
        <UPageCard variant="subtle">
          <template #header>
            <h3 class="text-base font-semibold">
              Seleccionar Estudiante
            </h3>
          </template>

          <div class="space-y-4">
            <UInput
              v-model="searchQuery"
              placeholder="Buscar por nombre o documento..."
              icon="i-lucide-search"
              @input="searchStudents"
            />

            <div
              v-if="students.length > 0"
              class="space-y-2"
            >
              <div
                v-for="s in students"
                :key="s.id"
                class="flex items-center justify-between p-3 border border-default rounded-lg cursor-pointer hover:bg-elevated/50"
                :class="{ 'ring-2 ring-primary': selectedStudent?.id === s.id }"
                @click="selectedStudent = s"
              >
                <div>
                  <p class="font-medium">
                    {{ s.user?.name }}
                  </p>
                  <p class="text-sm text-muted">
                    {{ s.user?.document_type }} {{ s.user?.document_number }} · {{ s.group?.grade?.name }} {{ s.group?.name }}
                  </p>
                </div>
                <UIcon
                  v-if="selectedStudent?.id === s.id"
                  name="i-lucide-check-circle"
                  class="size-5 text-primary"
                />
              </div>
            </div>

            <p
              v-else-if="searchQuery.length >= 2"
              class="text-sm text-muted text-center py-4"
            >
              No se encontraron estudiantes
            </p>
          </div>
        </UPageCard>

        <!-- Certificate options -->
        <div
          v-if="selectedStudent"
          class="space-y-4"
        >
          <UPageCard variant="subtle">
            <template #header>
              <h3 class="text-base font-semibold">
                Constancia de Matrícula
              </h3>
            </template>
            <p class="text-sm text-muted mb-4">
              Certifica que el estudiante está matriculado en la institución para el año académico activo.
            </p>
            <UButton
              icon="i-lucide-download"
              :loading="downloadingEnrollment"
              @click="handleDownloadEnrollment"
            >
              Descargar constancia de matrícula
            </UButton>
          </UPageCard>

          <UPageCard variant="subtle">
            <template #header>
              <h3 class="text-base font-semibold">
                Constancia de Notas
              </h3>
            </template>
            <p class="text-sm text-muted mb-4">
              Certifica las calificaciones del estudiante.
            </p>

            <div class="flex items-end gap-3">
              <USelectMenu
                v-model="selectedPeriod"
                :items="periodOptions"
                placeholder="Año completo"
                value-key="value"
                label-key="label"
                class="w-48"
              />
              <UButton
                icon="i-lucide-download"
                :loading="downloadingGrades"
                @click="handleDownloadGrades"
              >
                Descargar constancia de notas
              </UButton>
            </div>
          </UPageCard>
        </div>

        <UAlert
          v-if="!selectedStudent"
          color="info"
          variant="soft"
          icon="i-lucide-info"
          title="Selecciona un estudiante"
          description="Busca y selecciona un estudiante para generar sus constancias."
        />
      </div>
    </template>
  </UDashboardPanel>
</template>

<script setup lang="ts">
import type { Student, Period } from '~/types/school'

const api = useApi()
const reports = useReports()

const searchQuery = ref('')
const students = ref<Student[]>([])
const selectedStudent = ref<Student | null>(null)
const selectedPeriod = ref<number | null>(null)
const downloadingEnrollment = ref(false)
const downloadingGrades = ref(false)

const { periods } = storeToRefs(useAcademicStore())

const periodOptions = computed(() => [
  { label: 'Año completo', value: null },
  ...(periods.value?.map((p: Period) => ({ label: p.name, value: p.id })) ?? [])
])

let searchTimeout: ReturnType<typeof setTimeout>

function searchStudents() {
  clearTimeout(searchTimeout)
  if (searchQuery.value.length < 2) {
    students.value = []
    return
  }
  searchTimeout = setTimeout(async () => {
    try {
      const res = await api.get<{ data: Student[] }>(`/students?search=${encodeURIComponent(searchQuery.value)}&per_page=10`)
      students.value = res.data ?? []
    } catch { /* ignore */ }
  }, 300)
}

async function handleDownloadEnrollment() {
  if (!selectedStudent.value) return
  downloadingEnrollment.value = true
  try {
    await reports.downloadEnrollmentCertificate(selectedStudent.value.id)
  } catch (e: unknown) {
    console.error(e)
  } finally {
    downloadingEnrollment.value = false
  }
}

async function handleDownloadGrades() {
  if (!selectedStudent.value) return
  downloadingGrades.value = true
  try {
    await reports.downloadGradesCertificate(selectedStudent.value.id, {
      periodId: selectedPeriod.value ?? undefined
    })
  } catch (e: unknown) {
    console.error(e)
  } finally {
    downloadingGrades.value = false
  }
}
</script>
