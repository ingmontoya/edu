<script setup lang="ts">
const props = defineProps<{ modelValue: boolean }>()
const emit = defineEmits<{ 'update:modelValue': [value: boolean] }>()

const CAL_URL = 'https://calendly.com/devconsultingco/30min'
const WA_URL = 'https://wa.me/573004774906'

type Step = 'start' | 'problem' | 'students' | 'result'
type LeadTier = 'qualified' | 'warm'

const step = ref<Step>('start')
const direction = ref<'forward' | 'back'>('forward')
const name = ref('')
const institution = ref('')
const problem = ref('')
const students = ref('')
const leadTier = ref<LeadTier>('qualified')

const problemOptions = [
  'Calificaciones y boletines PDF',
  'Control de asistencia diaria',
  'Seguimiento de riesgo estudiantil con IA',
  'Reportes para el MEN / SIMAT',
  'Necesito orientación primero'
]

const studentOptions = ['Menos de 200', '200 a 600', '601 a 1.500', 'Más de 1.500']

const stepIndex = computed((): number => {
  const map: Record<Step, number> = { start: 0, problem: 1, students: 2, result: 3 }
  return map[step.value]
})

const progressPercent = computed(() => {
  if (step.value === 'result') return 100
  return Math.round((stepIndex.value / 3) * 100)
})

const canStart = computed(() => name.value.trim().length >= 2 && institution.value.trim().length >= 2)

function goTo(target: Step, dir: 'forward' | 'back' = 'forward') {
  direction.value = dir
  step.value = target
}

function qualifyLead(): LeadTier {
  if (students.value === 'Menos de 200' || problem.value === 'Necesito orientación primero') return 'warm'
  return 'qualified'
}

const calendlyUrl = computed(() => {
  const content = [
    institution.value ? `Institución: ${institution.value}` : '',
    students.value ? `Estudiantes: ${students.value}` : '',
    problem.value ? `Problema: ${problem.value}` : ''
  ].filter(Boolean).join(' | ')
  const params = new URLSearchParams({ utm_source: 'landing', utm_medium: 'form', utm_campaign: 'demo', utm_content: content })
  return `${CAL_URL}?${params.toString()}`
})

const whatsappUrl = computed(() => {
  const lines = [
    'Hola, vengo del sitio de Aula360.',
    '',
    institution.value ? `*Institución:* ${institution.value}` : '',
    students.value ? `*Estudiantes:* ${students.value}` : '',
    problem.value ? `*Desafío:* ${problem.value}` : '',
    '',
    'Me interesa conocer más sobre la plataforma.'
  ].filter(Boolean)
  return `${WA_URL}?text=${encodeURIComponent(lines.join('\n'))}`
})

function selectProblem(p: string) {
  problem.value = p
  goTo('students')
}

function selectStudents(s: string) {
  students.value = s
  leadTier.value = qualifyLead()
  goTo('result')
}

function close() {
  emit('update:modelValue', false)
}

watch(() => props.modelValue, (v) => {
  if (!v) {
    setTimeout(() => {
      step.value = 'start'
      direction.value = 'forward'
      name.value = ''
      institution.value = ''
      problem.value = ''
      students.value = ''
      leadTier.value = 'qualified'
    }, 300)
  }
})

onMounted(() => {
  const onKey = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && props.modelValue) close()
  }
  document.addEventListener('keydown', onKey)
  onUnmounted(() => document.removeEventListener('keydown', onKey))
})
</script>

<template>
  <Teleport to="body">
    <Transition name="demo-modal">
      <div
        v-if="modelValue"
        class="fixed inset-0 z-[200] flex items-center justify-center p-4"
        role="dialog"
        aria-modal="true"
        aria-label="Solicitar demo de Aula360"
      >
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="close" />

        <!-- Panel -->
        <div class="relative w-full max-w-md bg-slate-900 border border-white/10 rounded-2xl shadow-2xl overflow-hidden">
          <!-- Top bar -->
          <div class="flex items-start justify-between px-6 pt-6 pb-4 border-b border-white/[0.06]">
            <div>
              <p class="text-xs text-indigo-400 font-semibold uppercase tracking-widest mb-0.5">
                Demo gratuita · Aula360
              </p>
              <h2 class="text-lg font-bold text-white leading-tight">
                <template v-if="step === 'result'">
                  {{ leadTier === 'qualified' ? '¡Listo para su demo!' : '¡Con gusto le orientamos!' }}
                </template>
                <template v-else>
                  Cuéntenos sobre su institución
                </template>
              </h2>
            </div>
            <button
              type="button"
              class="p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/10 transition-colors shrink-0 ml-4 mt-0.5"
              aria-label="Cerrar"
              @click="close"
            >
              <UIcon name="i-lucide-x" class="w-5 h-5" />
            </button>
          </div>

          <!-- Progress bar -->
          <div v-if="step !== 'result'" class="px-6 pt-4 pb-1">
            <div class="flex items-center justify-between text-xs text-slate-600 mb-1.5">
              <span>Paso {{ stepIndex + 1 }} de 3</span>
              <span>{{ progressPercent }}%</span>
            </div>
            <div class="h-1 rounded-full bg-slate-800 overflow-hidden">
              <div
                class="h-full bg-gradient-to-r from-indigo-500 to-violet-500 rounded-full transition-all duration-500 ease-out"
                :style="`width: ${progressPercent}%`"
              />
            </div>
          </div>

          <!-- Step content -->
          <div class="px-6 py-5 relative overflow-hidden min-h-[240px]">
            <Transition :name="direction === 'forward' ? 'step-left' : 'step-right'" mode="out-in">
              <!-- Step: start -->
              <div v-if="step === 'start'" key="start" class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-1.5">Su nombre</label>
                  <input
                    v-model="name"
                    type="text"
                    placeholder="Juan Pérez"
                    class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-white/[0.08] text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition-colors text-sm"
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-slate-300 mb-1.5">Nombre de su institución</label>
                  <input
                    v-model="institution"
                    type="text"
                    placeholder="Colegio San Andrés"
                    class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-white/[0.08] text-white placeholder-slate-600 focus:outline-none focus:border-indigo-500 transition-colors text-sm"
                    @keydown.enter="canStart && goTo('problem')"
                  >
                </div>
                <button
                  type="button"
                  :disabled="!canStart"
                  class="w-full flex items-center justify-center gap-2 py-3 rounded-xl text-sm font-semibold mt-1 transition-all duration-200"
                  :class="canStart ? 'bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/20' : 'bg-slate-800 text-slate-600 cursor-not-allowed'"
                  @click="canStart && goTo('problem')"
                >
                  Continuar
                  <UIcon name="i-lucide-arrow-right" class="w-4 h-4" />
                </button>
              </div>

              <!-- Step: problem -->
              <div v-else-if="step === 'problem'" key="problem" class="space-y-2">
                <p class="text-sm font-medium text-slate-300 mb-3">
                  ¿Cuál es su mayor desafío hoy?
                </p>
                <button
                  v-for="opt in problemOptions"
                  :key="opt"
                  type="button"
                  class="w-full text-left px-4 py-3 rounded-xl border text-sm transition-all duration-150"
                  :class="problem === opt
                    ? 'bg-indigo-500/15 border-indigo-500/50 text-indigo-200'
                    : 'bg-slate-800/60 border-white/[0.08] text-slate-400 hover:border-white/20 hover:text-white'"
                  @click="selectProblem(opt)"
                >
                  {{ opt }}
                </button>
                <button
                  type="button"
                  class="text-sm text-slate-600 hover:text-slate-400 transition-colors pt-1 flex items-center gap-1"
                  @click="goTo('start', 'back')"
                >
                  ← Atrás
                </button>
              </div>

              <!-- Step: students -->
              <div v-else-if="step === 'students'" key="students" class="space-y-2">
                <p class="text-sm font-medium text-slate-300 mb-3">
                  ¿Cuántos estudiantes tiene su institución?
                </p>
                <button
                  v-for="opt in studentOptions"
                  :key="opt"
                  type="button"
                  class="w-full text-left px-4 py-3 rounded-xl border text-sm transition-all duration-150"
                  :class="students === opt
                    ? 'bg-indigo-500/15 border-indigo-500/50 text-indigo-200'
                    : 'bg-slate-800/60 border-white/[0.08] text-slate-400 hover:border-white/20 hover:text-white'"
                  @click="selectStudents(opt)"
                >
                  {{ opt }}
                </button>
                <button
                  type="button"
                  class="text-sm text-slate-600 hover:text-slate-400 transition-colors pt-1 flex items-center gap-1"
                  @click="goTo('problem', 'back')"
                >
                  ← Atrás
                </button>
              </div>

              <!-- Result -->
              <div v-else key="result" class="text-center py-2">
                <div
                  class="inline-flex items-center justify-center w-16 h-16 rounded-2xl mb-5"
                  :class="leadTier === 'qualified' ? 'bg-indigo-500/15' : 'bg-emerald-500/15'"
                >
                  <UIcon
                    :name="leadTier === 'qualified' ? 'i-lucide-calendar-check' : 'i-lucide-message-circle'"
                    class="w-8 h-8"
                    :class="leadTier === 'qualified' ? 'text-indigo-400' : 'text-emerald-400'"
                  />
                </div>
                <p class="text-slate-400 text-sm mb-6 max-w-xs mx-auto leading-relaxed">
                  {{ leadTier === 'qualified'
                    ? 'Agendemos 30 minutos para mostrarle Aula360 con datos reales de su institución. Sin compromiso.'
                    : 'Lo mejor es conversar primero. Escríbanos por WhatsApp y le orientamos sin ningún compromiso.'
                  }}
                </p>
                <a
                  :href="leadTier === 'qualified' ? calendlyUrl : whatsappUrl"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-xl text-sm font-bold transition-all duration-200 hover:-translate-y-0.5"
                  :class="leadTier === 'qualified'
                    ? 'bg-indigo-600 hover:bg-indigo-500 text-white shadow-xl shadow-indigo-600/30'
                    : 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-xl shadow-emerald-600/30'"
                  @click="close"
                >
                  <UIcon :name="leadTier === 'qualified' ? 'i-lucide-calendar' : 'i-lucide-message-circle'" class="w-4 h-4" />
                  {{ leadTier === 'qualified' ? 'Agendar demo ahora' : 'Escribir por WhatsApp' }}
                </a>
                <p class="text-xs text-slate-600 mt-3">
                  O si prefiere,
                  <a
                    :href="leadTier === 'qualified' ? whatsappUrl : calendlyUrl"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="hover:underline"
                    :class="leadTier === 'qualified' ? 'text-emerald-500/70 hover:text-emerald-400' : 'text-indigo-500/70 hover:text-indigo-400'"
                    @click="close"
                  >
                    {{ leadTier === 'qualified' ? 'contactar por WhatsApp' : 'agendar en calendario' }}
                  </a>
                </p>
              </div>
            </Transition>
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<style scoped>
.demo-modal-enter-active,
.demo-modal-leave-active {
  transition: opacity 0.2s ease;
}
.demo-modal-enter-from,
.demo-modal-leave-to {
  opacity: 0;
}

.step-left-enter-active,
.step-left-leave-active,
.step-right-enter-active,
.step-right-leave-active {
  transition: opacity 0.18s ease, transform 0.18s ease;
}
.step-left-enter-from {
  opacity: 0;
  transform: translateX(20px);
}
.step-left-leave-to {
  opacity: 0;
  transform: translateX(-20px);
}
.step-right-enter-from {
  opacity: 0;
  transform: translateX(-20px);
}
.step-right-leave-to {
  opacity: 0;
  transform: translateX(20px);
}
</style>
