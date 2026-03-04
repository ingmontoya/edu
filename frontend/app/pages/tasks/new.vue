<script setup lang="ts">
import type { Teacher, TeacherAssignment } from '~/types/school'

definePageMeta({
  middleware: 'auth'
})

const toast = useToast()
const router = useRouter()
const academic = useAcademicStore()
const tasks = useTasks()
const { getTeachers, getTeacherAssignments } = useAcademic()
const auth = useAuthStore()

const saving = ref(false)
const myTeacher = ref<Teacher | null>(null)
const myAssignments = ref<TeacherAssignment[]>([])
const attachmentFile = ref<File | null>(null)

const today = new Date().toISOString().split('T')[0]

const form = ref({
  teacher_id: undefined as number | undefined,
  group_id: undefined as number | undefined,
  subject_id: undefined as number | undefined,
  title: '',
  instructions: '',
  due_date: today,
  is_published: true
})

onMounted(async () => {
  if (auth.isTeacher) {
    // Load only this teacher's profile and assignments
    const res = await getTeachers()
    const found = (res.data as Teacher[]).find(t => t.user_id === auth.user?.id)
    if (found) {
      myTeacher.value = found
      form.value.teacher_id = found.id
      const assignments = await getTeacherAssignments(found.id) as TeacherAssignment[]
      myAssignments.value = assignments
    }
  } else {
    // Admin/coordinator: load all groups and subjects
    await Promise.all([academic.fetchGroups(), academic.fetchSubjects()])
  }
})

// For teachers: unique subjects from assignments
const subjectOptions = computed(() => {
  if (auth.isTeacher) {
    const seen = new Set<number>()
    return myAssignments.value
      .filter((a) => {
        if (!a.subject || seen.has(a.subject_id)) return false
        seen.add(a.subject_id)
        return true
      })
      .map(a => ({ label: a.subject!.name, value: a.subject_id }))
  }
  return academic.subjects.map(s => ({ label: s.name, value: s.id }))
})

// For teachers: groups filtered by selected subject
const groupOptions = computed(() => {
  if (auth.isTeacher) {
    if (!form.value.subject_id) return []
    return myAssignments.value
      .filter(a => a.subject_id === form.value.subject_id && a.group)
      .map(a => ({ label: a.group!.full_name || String(a.group_id), value: a.group_id }))
  }
  return academic.groups.map(g => ({ label: g.full_name || g.name, value: g.id }))
})

// When subject changes, reset group
watch(() => form.value.subject_id, () => {
  form.value.group_id = undefined
})

const handleFile = (event: Event) => {
  const input = event.target as HTMLInputElement
  attachmentFile.value = input.files?.[0] || null
}

const handleSubmit = async () => {
  if (!form.value.teacher_id || !form.value.group_id || !form.value.title || !form.value.instructions || !form.value.due_date) {
    toast.add({ title: 'Complete todos los campos obligatorios', color: 'warning' })
    return
  }

  saving.value = true
  try {
    const task = await tasks.createTask(
      {
        teacher_id: form.value.teacher_id,
        group_id: form.value.group_id,
        subject_id: form.value.subject_id,
        title: form.value.title,
        instructions: form.value.instructions,
        due_date: form.value.due_date,
        is_published: form.value.is_published
      },
      attachmentFile.value
    )
    toast.add({ title: 'Tarea creada correctamente', color: 'success' })
    router.push(`/tasks/${task.id}`)
  } catch (err: unknown) {
    const msg = err instanceof Error ? err.message : 'Error al crear la tarea'
    toast.add({ title: 'Error', description: msg, color: 'error' })
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <UDashboardPanel grow>
    <template #header>
      <UDashboardNavbar title="Nueva Tarea">
        <template #leading>
          <UDashboardSidebarCollapse />
        </template>
      </UDashboardNavbar>
    </template>

    <template #body>
      <div class="flex flex-col gap-6 p-6">
        <UPageCard title="Información de la tarea" variant="subtle">
          <div class="flex flex-col gap-4">
            <UFormField label="Título *" required class="w-full">
              <UInput v-model="form.title" placeholder="Ej. Taller de Matemáticas" class="w-full" />
            </UFormField>

            <div class="grid grid-cols-2 gap-4">
              <UFormField label="Asignatura *" required>
                <USelectMenu
                  v-model="form.subject_id"
                  :items="subjectOptions"
                  value-key="value"
                  placeholder="Seleccionar asignatura"
                  class="w-full"
                />
              </UFormField>

              <UFormField label="Grupo *" required>
                <USelectMenu
                  v-model="form.group_id"
                  :items="groupOptions"
                  value-key="value"
                  placeholder="Seleccionar grupo"
                  class="w-full"
                />
              </UFormField>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <UFormField label="Docente" required>
                <UInput
                  :model-value="myTeacher?.user?.name || auth.user?.name || ''"
                  disabled
                  class="w-full"
                />
              </UFormField>

              <UFormField label="Fecha límite *" required>
                <UInput v-model="form.due_date" type="date" class="w-full" />
              </UFormField>
            </div>

            <UFormField label="Instrucciones *" required class="w-full">
              <UTextarea
                v-model="form.instructions"
                :rows="8"
                placeholder="Describe las instrucciones de la tarea..."
                class="w-full"
              />
            </UFormField>

            <UFormField label="Archivo adjunto (PDF, opcional)" class="w-full">
              <input
                type="file"
                accept=".pdf"
                class="block w-full text-sm text-muted file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-primary/10 file:text-primary hover:file:bg-primary/20"
                @change="handleFile"
              >
              <p v-if="attachmentFile" class="text-xs text-muted mt-1">
                <UIcon name="i-lucide-paperclip" class="w-3 h-3 inline" />
                {{ attachmentFile.name }}
              </p>
            </UFormField>

            <UFormField label="Publicar inmediatamente">
              <UToggle v-model="form.is_published" />
            </UFormField>
            <div class="flex justify-end gap-3 pt-2">
              <UButton variant="ghost" to="/tasks">
                Cancelar
              </UButton>
              <UButton
                icon="i-lucide-save"
                :loading="saving"
                @click="handleSubmit"
              >
                Crear Tarea
              </UButton>
            </div>
          </div>
        </UPageCard>
      </div>
    </template>
  </UDashboardPanel>
</template>
