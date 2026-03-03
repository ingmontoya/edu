<script setup lang="ts">
import type { Student } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const route = useRoute()
const { getStudent } = useAcademic()
const toast = useToast()

const loading = ref(true)
const student = ref<Student | null>(null)

const studentId = computed(() => Number(route.params.id))

const statusColors: Record<string, 'primary' | 'secondary' | 'success' | 'info' | 'warning' | 'error' | 'neutral'> = {
  active: 'success',
  inactive: 'neutral',
  withdrawn: 'error',
  graduated: 'info'
}

const statusLabels: Record<string, string> = {
  active: 'Activo',
  inactive: 'Inactivo',
  withdrawn: 'Retirado',
  graduated: 'Graduado'
}

const formatDate = (date?: string) => {
  if (!date) return '-'
  return new Date(date).toLocaleDateString('es-CO', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

onMounted(async () => {
  try {
    student.value = await getStudent(studentId.value)
  } catch (error) {
    toast.add({ title: 'Error', description: 'No se pudo cargar el estudiante', color: 'error' })
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar :title="student?.user?.name || 'Estudiante'">
        <template #leading>
          <UButton to="/students" color="neutral" variant="ghost" size="sm">
            <UIcon name="i-lucide-arrow-left" class="w-4 h-4 mr-2" />
            Volver
          </UButton>
        </template>

        <template #right>
          <UButton :to="`/students/${studentId}/edit`" color="primary">
            <UIcon name="i-lucide-edit" class="w-4 h-4 mr-2" />
            Editar
          </UButton>
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <div v-if="loading" class="flex items-center justify-center py-12">
          <UIcon name="i-lucide-loader-2" class="w-8 h-8 animate-spin text-primary" />
        </div>

        <template v-else-if="student">
          <!-- Header Card -->
          <UPageCard variant="subtle">
            <div class="flex items-start gap-6">
              <UAvatar :alt="student.user?.name" size="xl" />
              <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                  <h2 class="text-2xl font-bold">{{ student.user?.name }}</h2>
                  <UBadge :color="statusColors[student.status]" variant="subtle">
                    {{ statusLabels[student.status] }}
                  </UBadge>
                </div>
                <p class="text-muted mb-1">
                  {{ student.user?.document_type }} {{ student.user?.document_number }}
                </p>
                <p v-if="student.group" class="text-muted">
                  {{ student.group.full_name }}
                </p>
              </div>
            </div>
          </UPageCard>

          <!-- Personal Information -->
          <UPageCard title="Informacion Personal" variant="subtle">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-muted">Nombre Completo</p>
                <p class="font-medium">{{ student.user?.name || '-' }}</p>
              </div>
              <div>
                <p class="text-sm text-muted">Documento</p>
                <p class="font-medium">{{ student.user?.document_type }} {{ student.user?.document_number }}</p>
              </div>
              <div>
                <p class="text-sm text-muted">Correo Electronico</p>
                <p class="font-medium">{{ student.user?.email || '-' }}</p>
              </div>
              <div>
                <p class="text-sm text-muted">Telefono</p>
                <p class="font-medium">{{ student.user?.phone || '-' }}</p>
              </div>
              <div>
                <p class="text-sm text-muted">Fecha de Nacimiento</p>
                <p class="font-medium">{{ formatDate(student.user?.birth_date) }}</p>
              </div>
              <div>
                <p class="text-sm text-muted">Direccion</p>
                <p class="font-medium">{{ student.user?.address || '-' }}</p>
              </div>
            </div>
          </UPageCard>

          <!-- Academic Information -->
          <UPageCard title="Informacion Academica" variant="subtle">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <p class="text-sm text-muted">Grupo Actual</p>
                <p class="font-medium">{{ student.group?.full_name || '-' }}</p>
              </div>
              <div>
                <p class="text-sm text-muted">Codigo de Matricula</p>
                <p class="font-medium">{{ student.enrollment_code || '-' }}</p>
              </div>
              <div>
                <p class="text-sm text-muted">Fecha de Matricula</p>
                <p class="font-medium">{{ formatDate(student.enrollment_date) }}</p>
              </div>
              <div>
                <p class="text-sm text-muted">Estado</p>
                <UBadge :color="statusColors[student.status]" variant="subtle">
                  {{ statusLabels[student.status] }}
                </UBadge>
              </div>
            </div>
          </UPageCard>

          <!-- Guardians -->
          <UPageCard v-if="student.guardians?.length" title="Acudientes" variant="subtle">
            <div class="divide-y">
              <div
                v-for="guardian in student.guardians"
                :key="guardian.id"
                class="py-3 first:pt-0 last:pb-0"
              >
                <div class="flex items-center gap-3">
                  <UAvatar :alt="guardian.user?.name" size="sm" />
                  <div>
                    <p class="font-medium">{{ guardian.user?.name }}</p>
                    <p class="text-sm text-muted">
                      {{ guardian.relationship }} - {{ guardian.user?.phone || 'Sin telefono' }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </UPageCard>

          <!-- Quick Actions -->
          <UPageCard title="Acciones Rapidas" variant="subtle">
            <div class="flex flex-wrap gap-2">
              <UButton to="/grades/record" color="primary" variant="soft">
                <UIcon name="i-lucide-file-edit" class="w-4 h-4 mr-2" />
                Registrar Notas
              </UButton>
              <UButton to="/attendance" color="primary" variant="soft">
                <UIcon name="i-lucide-calendar-check" class="w-4 h-4 mr-2" />
                Asistencia
              </UButton>
              <UButton to="/reports/cards" color="primary" variant="soft">
                <UIcon name="i-lucide-file-text" class="w-4 h-4 mr-2" />
                Boletin
              </UButton>
            </div>
          </UPageCard>
        </template>

        <UPageCard v-else variant="subtle">
          <div class="text-center py-12">
            <UIcon name="i-lucide-user-x" class="w-12 h-12 text-muted mx-auto mb-4" />
            <p class="text-muted">Estudiante no encontrado</p>
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
