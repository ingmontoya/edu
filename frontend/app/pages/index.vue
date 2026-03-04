<script setup lang="ts">
definePageMeta({
  layout: false
})

useSeoMeta({
  title: 'Aula360 — Gestión Académica con IA para Colegios Colombianos',
  description: 'Aula360 transforma la gestión académica con Inteligencia Artificial. Índice de riesgo estudiantil, análisis pedagógico con IA, boletines PDF, SIEE y portal de padres. Diseñado para instituciones educativas colombianas.',
  keywords: 'gestión académica colombia, software educativo colombia, SIEE, boletines PDF, inteligencia artificial educación, riesgo estudiantil, calificaciones colegio, plataforma educativa, Aula360',
  author: 'Aula360',
  robots: 'index, follow',
  ogTitle: 'Aula360 — Gestión Académica con IA para Colegios Colombianos',
  ogDescription: 'Detecta el riesgo estudiantil antes de que sea tarde. Análisis pedagógico con IA, boletines PDF, SIEE y portal de padres para colegios colombianos.',
  ogType: 'website',
  ogImage: '/og-image.png',
  ogUrl: 'https://aula360.com',
  ogLocale: 'es_CO',
  ogSiteName: 'Aula360',
  twitterCard: 'summary_large_image',
  twitterTitle: 'Aula360 — Gestión Académica con IA',
  twitterDescription: 'Detecta el riesgo estudiantil antes de que sea tarde. IA aplicada a la educación colombiana.',
  twitterImage: '/og-image.png'
})

useHead({
  htmlAttrs: { lang: 'es-CO' },
  link: [{ rel: 'canonical', href: 'https://aula360.com' }],
  script: [
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'SoftwareApplication',
        'name': 'Aula360',
        'applicationCategory': 'EducationalApplication',
        'operatingSystem': 'Web',
        'description': 'Sistema de gestión académica con IA para instituciones educativas colombianas.',
        'offers': { '@type': 'Offer', 'price': '0', 'priceCurrency': 'COP' },
        'featureList': [
          'Índice de Riesgo Estudiantil con IA',
          'Análisis pedagógico por estudiante con Claude AI',
          'Cumplimiento SIEE colombiano',
          'Boletines PDF individuales y masivos',
          'Portal de padres',
          'Exportación SIMAT',
          'Convivencia Escolar Ley 1620/2013'
        ]
      })
    }
  ]
})

const navLinks = [
  { label: 'Inteligencia Artificial', href: '#ia' },
  { label: 'Módulos', href: '#modulos' },
  { label: 'Beneficios', href: '#beneficios' },
  { label: 'Comparativo', href: '#comparativo' },
  { label: 'Contacto', href: '#contacto' }
]

const aiFeatures = [
  {
    icon: 'i-lucide-shield-alert',
    title: 'Índice de Riesgo Estudiantil',
    description: 'Calcula un puntaje 0–100 por estudiante combinando materias reprobadas, porcentaje de asistencia, incidentes disciplinarios, nivelaciones pendientes y tendencia de notas. Clasifica automáticamente en Riesgo Alto, Moderado o Bajo para que actúes a tiempo.',
    accentFrom: 'from-red-500',
    accentTo: 'to-orange-500',
    glowBg: 'bg-red-500/15',
    glowBlur: 'bg-red-500',
    iconColor: 'text-red-400'
  },
  {
    icon: 'i-lucide-brain',
    title: 'Análisis Pedagógico por Estudiante',
    description: 'Genera narrativas completas y recomendaciones pedagógicas personalizadas por estudiante y por período usando Claude AI. Analiza calificaciones, logros, asistencia, nivelaciones e historial disciplinario en un informe accionable.',
    accentFrom: 'from-violet-500',
    accentTo: 'to-purple-500',
    glowBg: 'bg-violet-500/15',
    glowBlur: 'bg-violet-500',
    iconColor: 'text-violet-400'
  },
  {
    icon: 'i-lucide-trending-up',
    title: 'Evolución y Seguimiento Histórico',
    description: 'Cada análisis queda guardado. Visualiza la evolución del riesgo entre períodos: delta de puntaje, cambio de nivel, comparativo anterior vs. actual. El docente ve si sus intervenciones están funcionando con evidencia real.',
    accentFrom: 'from-indigo-500',
    accentTo: 'to-blue-500',
    glowBg: 'bg-indigo-500/15',
    glowBlur: 'bg-indigo-500',
    iconColor: 'text-indigo-400'
  },
  {
    icon: 'i-lucide-layout-dashboard',
    title: 'Resumen Ejecutivo Semanal IA',
    description: 'La IA genera automáticamente un resumen ejecutivo para coordinadores con la situación de riesgo de toda la institución: cuántos estudiantes en alerta, tendencias por grado, grupos que requieren atención prioritaria.',
    accentFrom: 'from-emerald-500',
    accentTo: 'to-green-500',
    glowBg: 'bg-emerald-500/15',
    glowBlur: 'bg-emerald-500',
    iconColor: 'text-emerald-400'
  }
]

const metrics = [
  { value: '0–100', label: 'Escala de Índice de Riesgo por estudiante', color: 'text-orange-400' },
  { value: '5 señales', label: 'Variables analizadas por IA para calcular riesgo', color: 'text-violet-400' },
  { value: '100%', label: 'Cumplimiento SIEE y normativa colombiana', color: 'text-indigo-400' },
  { value: '70%', label: 'Reducción proyectada en carga administrativa', color: 'text-green-400' }
]

const moduleCategories = [
  { key: 'Académico', label: 'Académico', icon: 'i-lucide-graduation-cap' },
  { key: 'Reportes', label: 'Reportes', icon: 'i-lucide-file-text' },
  { key: 'Normativa', label: 'Normativa', icon: 'i-lucide-shield' },
  { key: 'Comunicación', label: 'Comunicación', icon: 'i-lucide-home' },
  { key: 'IA', label: 'Inteligencia Artificial', icon: 'i-lucide-sparkles' }
]

const activeModuleCategory = ref('Académico')
const activeCategoryKey = computed(() => activeModuleCategory.value)
const setModuleCategory = (key: string) => {
  activeModuleCategory.value = key
}

const modules = [
  { category: 'IA', icon: 'i-lucide-shield-alert', title: 'Riesgo Estudiantil con IA', description: 'Detecta estudiantes vulnerables con un índice multivariable y accede a recomendaciones generadas por IA para cada caso.', badge: 'IA', pill: 'bg-red-500/15', iconColor: 'text-red-400' },
  { category: 'Académico', icon: 'i-lucide-graduation-cap', title: 'Gestión Académica', description: 'Configura años académicos, períodos con pesos porcentuales, grados, grupos y asignaturas desde preescolar hasta media.', badge: null, pill: 'bg-blue-500/15', iconColor: 'text-blue-400' },
  { category: 'Académico', icon: 'i-lucide-users', title: 'Estudiantes y Docentes', description: 'Registro completo con historial académico, estados de matrícula, exportación SIMAT y asignación de acudientes.', badge: null, pill: 'bg-indigo-500/15', iconColor: 'text-indigo-400' },
  { category: 'Académico', icon: 'i-lucide-file-check', title: 'Calificaciones Colombianas', description: 'Escala Superior/Alto/Básico/Bajo, promedios ponderados, planilla consolidada con ranking y observaciones por estudiante.', badge: null, pill: 'bg-emerald-500/15', iconColor: 'text-emerald-400' },
  { category: 'Académico', icon: 'i-lucide-calendar-check', title: 'Control de Asistencia', description: 'Registro diario con estados Presente/Ausente/Tardanza/Excusa, alertas automáticas y reportes de ausentismo por período.', badge: null, pill: 'bg-cyan-500/15', iconColor: 'text-cyan-400' },
  { category: 'Académico', icon: 'i-lucide-clipboard-list', title: 'Tareas y Entregas', description: 'Asigna trabajos al grupo con instrucciones y PDF adjunto. Los acudientes consultan el estado de entrega de su hijo en tiempo real.', badge: null, pill: 'bg-sky-500/15', iconColor: 'text-sky-400' },
  { category: 'Reportes', icon: 'i-lucide-file-text', title: 'Boletines PDF', description: 'Generación individual y masiva de boletines con logo institucional, notas, promedio, puesto y observaciones del docente.', badge: null, pill: 'bg-orange-500/15', iconColor: 'text-orange-400' },
  { category: 'Reportes', icon: 'i-lucide-file-badge', title: 'Constancias y Certificados', description: 'Generación automática de constancias de matrícula y de notas en PDF con datos y logo institucional.', badge: null, pill: 'bg-teal-500/15', iconColor: 'text-teal-400' },
  { category: 'Reportes', icon: 'i-lucide-download', title: 'Exportación SIMAT', description: 'Exporta el registro estudiantil en el formato oficial requerido por el MEN para el Sistema Integrado de Matrícula.', badge: null, pill: 'bg-slate-500/15', iconColor: 'text-slate-400' },
  { category: 'Normativa', icon: 'i-lucide-target', title: 'SIEE — Evaluación por Logros', description: 'Logros cognitivos, procedimentales y actitudinales con indicadores de desempeño. Importación CSV y copia entre períodos.', badge: null, pill: 'bg-rose-500/15', iconColor: 'text-rose-400' },
  { category: 'Normativa', icon: 'i-lucide-message-square', title: 'Convivencia Escolar', description: 'Registro de incidentes disciplinarios conforme a la Ley 1620/2013. Historial por estudiante vinculado al índice de riesgo.', badge: null, pill: 'bg-violet-500/15', iconColor: 'text-violet-400' },
  { category: 'Normativa', icon: 'i-lucide-refresh-cw', title: 'Nivelaciones y Recuperaciones', description: 'Asignación automática de actividades remediales a estudiantes con bajo desempeño. Calificación con actualización de nota original.', badge: null, pill: 'bg-yellow-500/15', iconColor: 'text-yellow-400' },
  { category: 'Comunicación', icon: 'i-lucide-home', title: 'Portal de Padres', description: 'Acceso seguro para acudientes: notas, asistencia, descarga de boletines y constancias. Disponible 24/7 desde cualquier dispositivo.', badge: null, pill: 'bg-pink-500/15', iconColor: 'text-pink-400' }
]

const filteredModules = computed(() =>
  modules.filter(m => m.category === activeModuleCategory.value)
)

const benefitsInstitution = [
  'Visión completa del estado académico de toda la institución en tiempo real',
  'Resumen ejecutivo semanal generado por IA con alertas prioritarias',
  'Diseñado para reducir la carga de procesos administrativos en hasta un 70%',
  'Cumplimiento garantizado con SIEE y normativa MEN',
  'Exportación SIMAT lista para reportar al gobierno',
  'Reportes de convivencia escolar alineados a la Ley 1620/2013'
]

const benefitsTeachers = [
  'Registro de notas rápido, individual o masivo, desde cualquier dispositivo',
  'Índice de riesgo visible por estudiante con factores desglosados',
  'Análisis pedagógico con IA que sugiere intervenciones concretas',
  'Control de asistencia digital con alertas automáticas',
  'Gestión de logros SIEE con indicadores cognitivos, procedimentales y actitudinales',
  'Historial de evolución para ver el impacto real de tus intervenciones'
]

const benefitsParents = [
  'Acceso 24/7 a las notas de sus hijos desde el celular o computador',
  'Descarga inmediata de boletines en PDF sin ir al colegio',
  'Consulta del historial de asistencia por período',
  'Constancias de matrícula y de notas disponibles en línea',
  'Información siempre actualizada y segura, sin instalar aplicaciones'
]

const aiEvolutionBullets = [
  { icon: 'i-lucide-git-compare', title: 'Comparativo período a período', text: 'Delta de puntaje de riesgo entre el análisis actual y el anterior, con indicador de mejora o deterioro.' },
  { icon: 'i-lucide-arrow-up-down', title: 'Cambio de nivel documentado', text: 'Registra cada transición: de Riesgo Alto a Moderado, de Moderado a Sin Riesgo. Evidencia tangible del progreso.' },
  { icon: 'i-lucide-history', title: 'Historial narrativo completo', text: 'Cada análisis de IA queda guardado con narrativa y recomendaciones. El docente puede leer el historial pedagógico completo.' }
]

const comparison = [
  { feature: 'Índice de Riesgo Estudiantil con IA', aula360: true, others: false },
  { feature: 'Análisis pedagógico personalizado por IA por estudiante', aula360: true, others: false },
  { feature: 'Resumen ejecutivo institucional semanal con IA', aula360: true, others: false },
  { feature: 'Historial de evolución de riesgo por período', aula360: true, others: false },
  { feature: 'Escala colombiana (Superior/Alto/Básico/Bajo)', aula360: true, others: 'Parcial' },
  { feature: 'SIEE integrado — logros cognitivos, procedimentales y actitudinales', aula360: true, others: false },
  { feature: 'Exportación SIMAT oficial MEN', aula360: true, others: 'Adicional' },
  { feature: 'Convivencia Escolar — Ley 1620/2013', aula360: true, others: false },
  { feature: 'Boletines PDF individuales y masivos', aula360: true, others: 'Manual' },
  { feature: 'Portal de padres incluido', aula360: true, others: 'Adicional' },
  { feature: 'Multi-institución con datos completamente aislados', aula360: true, others: false },
  { feature: 'Soporte en español colombiano', aula360: true, others: 'Variable' }
]

const gradeScale = [
  { level: 'Superior', range: '4.6 – 5.0', color: 'text-green-400', bg: 'bg-green-500/10 border-green-500/20' },
  { level: 'Alto', range: '4.0 – 4.5', color: 'text-blue-400', bg: 'bg-blue-500/10 border-blue-500/20' },
  { level: 'Básico', range: '3.0 – 3.9', color: 'text-yellow-400', bg: 'bg-yellow-500/10 border-yellow-500/20' },
  { level: 'Bajo', range: '1.0 – 2.9', color: 'text-red-400', bg: 'bg-red-500/10 border-red-500/20' }
]
</script>

<template>
  <div class="min-h-screen bg-slate-950">
    <!-- ============ NAVBAR ============ -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-slate-950/80 backdrop-blur-xl border-b border-white/[0.06]">
      <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Navegación principal">
        <div class="flex items-center justify-between h-16">
          <a href="/" class="flex items-center gap-3" aria-label="Aula360 - Ir al inicio">
            <img
              src="/logo.svg"
              alt="Aula360"
              class="h-8 w-8"
              width="32"
              height="32"
            >
            <span class="text-xl font-bold text-white">Aula<span class="text-indigo-400">360</span></span>
          </a>
          <div class="hidden md:flex items-center gap-6">
            <a
              v-for="link in navLinks"
              :key="link.href"
              :href="link.href"
              class="text-sm text-slate-400 hover:text-white transition-colors duration-200"
            >{{ link.label }}</a>
          </div>
          <div class="flex items-center gap-3">
            <a
              href="/login"
              class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors duration-200"
            >
              Iniciar Sesión
            </a>
            <a
              href="/login"
              class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg shadow-lg shadow-indigo-600/30 transition-colors duration-200"
            >
              Solicitar Demo
            </a>
          </div>
        </div>
      </nav>
    </header>

    <main>
      <!-- ============ HERO ============ -->
      <section class="relative overflow-hidden bg-slate-950 pt-32 pb-28 px-4 sm:px-6 lg:px-8" aria-labelledby="hero-title">
        <!-- Ambient gradient orbs -->
        <div aria-hidden="true" class="absolute -top-40 -left-40 w-[600px] h-[600px] rounded-full bg-indigo-600/20 blur-[120px] pointer-events-none" />
        <div aria-hidden="true" class="absolute -top-20 -right-40 w-[500px] h-[500px] rounded-full bg-violet-600/15 blur-[100px] pointer-events-none" />
        <div aria-hidden="true" class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[800px] h-[200px] bg-indigo-500/10 blur-[80px] pointer-events-none" />

        <div class="relative z-10 max-w-7xl mx-auto">
          <div class="text-center max-w-4xl mx-auto">
            <!-- Badge with pulse dot -->
            <div class="inline-flex items-center gap-2.5 px-4 py-1.5 mb-8 rounded-full border border-indigo-500/30 bg-indigo-500/10 text-indigo-300 text-sm font-medium backdrop-blur-sm">
              <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75" />
                <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-400" />
              </span>
              Plataforma en acceso anticipado — para instituciones pioneras
            </div>

            <!-- Headline -->
            <h1 id="hero-title" class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-white leading-[1.05] tracking-tight mb-6">
              Detecta el riesgo estudiantil
              <span class="block bg-gradient-to-r from-indigo-400 via-violet-400 to-purple-400 bg-clip-text text-transparent">
                antes de que sea tarde.
              </span>
            </h1>

            <p class="text-lg sm:text-xl text-slate-400 leading-relaxed max-w-2xl mx-auto mb-10">
              Aula360 es la plataforma académica con IA que analiza el desempeño de cada estudiante en tiempo real, genera recomendaciones pedagógicas personalizadas y ayuda a tu institución a tomar decisiones que transforman vidas.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
              <a
                href="/login"
                class="inline-flex items-center px-8 py-3.5 text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-xl shadow-indigo-600/40 hover:shadow-indigo-500/50 hover:-translate-y-0.5 transition-all duration-200"
              >
                <UIcon name="i-lucide-arrow-right" class="size-5 mr-2" />
                Ingresar al Sistema
              </a>
              <a
                href="#ia"
                class="inline-flex items-center px-8 py-3.5 text-base font-medium text-white border border-white/20 hover:bg-white/10 rounded-xl backdrop-blur-sm transition-colors duration-200"
              >
                Conocer la IA
              </a>
            </div>
          </div>

          <!-- Hero dashboard preview card -->
          <div class="relative mt-16 mx-auto max-w-4xl">
            <div aria-hidden="true" class="absolute -inset-px rounded-2xl bg-gradient-to-r from-indigo-500/50 via-violet-500/50 to-purple-500/50 blur-sm pointer-events-none" />
            <div class="relative rounded-2xl bg-slate-900/90 border border-white/10 backdrop-blur-xl p-6 shadow-2xl">
              <!-- Fake browser chrome -->
              <div class="flex items-center gap-2 mb-5 pb-4 border-b border-white/[0.06]">
                <div class="flex gap-1.5">
                  <div class="w-3 h-3 rounded-full bg-red-500/60" />
                  <div class="w-3 h-3 rounded-full bg-yellow-500/60" />
                  <div class="w-3 h-3 rounded-full bg-green-500/60" />
                </div>
                <div class="flex-1 h-5 rounded bg-slate-800/60 max-w-[200px] mx-auto" />
              </div>

              <!-- Dashboard header -->
              <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2">
                  <UIcon name="i-lucide-brain" class="w-4 h-4 text-indigo-400" />
                  <span class="text-sm font-semibold text-white">Índice de Riesgo IA</span>
                </div>
                <span class="text-xs px-2.5 py-1 rounded-full bg-indigo-500/15 text-indigo-300 border border-indigo-500/20">
                  Período 2 · 2024
                </span>
              </div>

              <!-- Risk summary chips -->
              <div class="grid grid-cols-3 gap-3 mb-5">
                <div class="rounded-xl p-3 bg-red-500/10 border border-red-500/20 text-center">
                  <p class="text-2xl font-bold text-red-400">
                    12
                  </p>
                  <p class="text-xs text-slate-500 mt-0.5">
                    Riesgo Alto
                  </p>
                </div>
                <div class="rounded-xl p-3 bg-yellow-500/10 border border-yellow-500/20 text-center">
                  <p class="text-2xl font-bold text-yellow-400">
                    27
                  </p>
                  <p class="text-xs text-slate-500 mt-0.5">
                    Moderado
                  </p>
                </div>
                <div class="rounded-xl p-3 bg-green-500/10 border border-green-500/20 text-center">
                  <p class="text-2xl font-bold text-green-400">
                    189
                  </p>
                  <p class="text-xs text-slate-500 mt-0.5">
                    Sin Riesgo
                  </p>
                </div>
              </div>

              <!-- Student rows -->
              <div class="space-y-2">
                <div
                  v-for="(s, i) in [
                    { name: 'Valentina García', group: 'Grupo 9-A', score: 78, bar: 'bg-red-500', scoreColor: 'text-red-400' },
                    { name: 'Sebastián Montoya', group: 'Grupo 8-B', score: 51, bar: 'bg-yellow-500', scoreColor: 'text-yellow-400' },
                    { name: 'Isabella Restrepo', group: 'Grupo 7-A', score: 22, bar: 'bg-green-500', scoreColor: 'text-green-400' }
                  ]"
                  :key="i"
                  class="flex items-center gap-3 p-2.5 rounded-xl bg-white/[0.03] border border-white/[0.05]"
                >
                  <div class="w-7 h-7 rounded-full bg-slate-700 shrink-0 flex items-center justify-center">
                    <span class="text-xs text-slate-400 font-medium">{{ s.name[0] }}</span>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-slate-200 truncate">
                      {{ s.name }}
                    </p>
                    <p class="text-xs text-slate-600">
                      {{ s.group }}
                    </p>
                  </div>
                  <div class="flex items-center gap-2">
                    <div class="w-16 h-1.5 rounded-full bg-slate-800 overflow-hidden">
                      <div class="h-full rounded-full" :class="s.bar" :style="`width:${s.score}%`" />
                    </div>
                    <span class="text-xs font-mono font-semibold w-6 text-right" :class="s.scoreColor">{{ s.score }}</span>
                  </div>
                  <div class="flex items-center gap-1 text-xs px-2 py-1 rounded-lg bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 shrink-0 cursor-pointer hover:bg-indigo-500/20 transition-colors">
                    <UIcon name="i-lucide-sparkles" class="w-3 h-3" />
                    IA
                  </div>
                </div>
              </div>

              <!-- AI status bar -->
              <div class="mt-4 flex items-center gap-2.5 px-3 py-2.5 rounded-xl bg-indigo-500/8 border border-indigo-500/15">
                <UIcon name="i-lucide-brain" class="w-3.5 h-3.5 text-indigo-400 animate-pulse shrink-0" />
                <span class="text-xs text-indigo-300/70">Analizando patrones con Claude AI...</span>
                <div class="flex gap-0.5 ml-auto">
                  <span class="w-1 h-1 rounded-full bg-indigo-400 animate-bounce" style="animation-delay:0ms" />
                  <span class="w-1 h-1 rounded-full bg-indigo-400 animate-bounce" style="animation-delay:150ms" />
                  <span class="w-1 h-1 rounded-full bg-indigo-400 animate-bounce" style="animation-delay:300ms" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ============ METRICS BAR ============ -->
      <div class="bg-slate-950 border-y border-white/[0.06] py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-white/[0.06]">
          <div
            v-for="metric in metrics"
            :key="metric.label"
            class="flex flex-col items-center justify-center py-8 md:py-0 px-8 text-center"
          >
            <p class="text-4xl sm:text-5xl font-extrabold tracking-tight" :class="metric.color">
              {{ metric.value }}
            </p>
            <p class="mt-2 text-sm text-slate-500 font-medium uppercase tracking-wide">
              {{ metric.label }}
            </p>
          </div>
        </div>
      </div>

      <!-- ============ AI FEATURES ============ -->
      <section id="ia" class="relative bg-slate-900 py-24 px-4 sm:px-6 lg:px-8 border-b border-white/[0.06]" aria-labelledby="ia-title">
        <div class="max-w-7xl mx-auto">
          <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-300 text-xs font-semibold uppercase tracking-widest mb-4">
              <UIcon name="i-lucide-sparkles" class="w-3.5 h-3.5" />
              Motor de Inteligencia Artificial
            </div>
            <h2 id="ia-title" class="text-3xl sm:text-4xl font-bold text-white mb-4">
              IA que realmente <span class="text-violet-400">impacta el aprendizaje</span>
            </h2>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">
              No es automatización básica. Es análisis profundo impulsado por IA que convierte datos académicos en acciones concretas para cada estudiante.
            </p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <article
              v-for="feature in aiFeatures"
              :key="feature.title"
              class="relative group rounded-2xl bg-slate-800/60 border border-white/[0.08] backdrop-blur-sm p-6 overflow-hidden hover:border-white/[0.15] transition-all duration-300 hover:bg-slate-800/80"
            >
              <!-- Colored left border accent -->
              <div class="absolute left-0 top-6 bottom-6 w-[3px] rounded-full bg-gradient-to-b" :class="`${feature.accentFrom} ${feature.accentTo}`" />

              <!-- Glowing icon -->
              <div class="relative mb-5 w-12 h-12 rounded-xl flex items-center justify-center">
                <div class="absolute inset-0 rounded-xl opacity-20 blur-md" :class="feature.glowBlur" />
                <div class="absolute inset-0 rounded-xl" :class="feature.glowBg" />
                <UIcon :name="feature.icon" class="relative size-5" :class="feature.iconColor" />
              </div>

              <h3 class="text-lg font-semibold text-white mb-2">
                {{ feature.title }}
              </h3>
              <p class="text-sm text-slate-400 leading-relaxed">
                {{ feature.description }}
              </p>
            </article>
          </div>
        </div>
      </section>

      <!-- ============ AI EVOLUTION SPLIT ============ -->
      <section class="relative bg-slate-950 py-24 px-4 sm:px-6 lg:px-8 overflow-hidden border-b border-white/[0.06]" aria-labelledby="evolucion-title">
        <!-- Background grid -->
        <div aria-hidden="true" class="absolute inset-0 pointer-events-none" style="background-image: linear-gradient(rgba(99,102,241,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(99,102,241,0.03) 1px, transparent 1px); background-size: 40px 40px;" />
        <div aria-hidden="true" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[500px] rounded-full bg-indigo-600/8 blur-[150px] pointer-events-none" />

        <div class="relative z-10 max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          <!-- Left: text -->
          <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-semibold uppercase tracking-widest mb-6">
              <UIcon name="i-lucide-history" class="w-3.5 h-3.5" />
              Seguimiento con IA a lo largo del tiempo
            </div>
            <h2 id="evolucion-title" class="text-3xl sm:text-4xl font-bold text-white mb-6 leading-tight">
              No solo detecta riesgo.<br>
              <span class="text-indigo-400">Mide si tus acciones funcionan.</span>
            </h2>
            <p class="text-slate-400 text-lg leading-relaxed mb-10">
              Aula360 persiste cada análisis de IA y construye una línea de tiempo del progreso estudiantil. Los docentes y coordinadores pueden ver, período a período, si las intervenciones pedagógicas están moviendo la aguja — con números, no con intuición.
            </p>
            <div class="space-y-5 mb-10">
              <div v-for="bullet in aiEvolutionBullets" :key="bullet.title" class="flex items-start gap-3">
                <div class="p-2 bg-indigo-500/10 rounded-lg shrink-0 mt-0.5">
                  <UIcon :name="bullet.icon" class="w-4 h-4 text-indigo-400" />
                </div>
                <div>
                  <p class="text-sm text-white font-semibold mb-0.5">
                    {{ bullet.title }}
                  </p>
                  <p class="text-sm text-slate-400 leading-relaxed">
                    {{ bullet.text }}
                  </p>
                </div>
              </div>
            </div>
            <a
              href="/login"
              class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-400 hover:text-indigo-300 transition-colors duration-200"
            >
              Ver historial de análisis IA
              <UIcon name="i-lucide-arrow-right" class="w-4 h-4" />
            </a>
          </div>

          <!-- Right: evolution mockup -->
          <div class="relative">
            <div aria-hidden="true" class="absolute -inset-4 rounded-3xl bg-gradient-to-br from-indigo-600/20 via-violet-600/15 to-purple-600/20 blur-2xl pointer-events-none" />
            <div class="relative rounded-2xl bg-slate-900 border border-white/10 shadow-2xl shadow-black/60 overflow-hidden">
              <!-- Chrome bar -->
              <div class="flex items-center gap-2 px-4 py-3 bg-slate-800/80 border-b border-white/[0.06]">
                <div class="flex gap-1.5">
                  <div class="w-3 h-3 rounded-full bg-red-500/60" />
                  <div class="w-3 h-3 rounded-full bg-yellow-500/60" />
                  <div class="w-3 h-3 rounded-full bg-green-500/60" />
                </div>
                <div class="flex-1 h-4 rounded bg-slate-700/60 max-w-[180px] mx-auto" />
              </div>

              <div class="p-5">
                <!-- Student header -->
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-white/[0.06]">
                  <div class="w-9 h-9 rounded-full bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center shrink-0">
                    <span class="text-sm font-bold text-indigo-300">V</span>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-white">
                      Valentina García
                    </p>
                    <p class="text-xs text-slate-500">
                      Grupo 9-A · Historial IA
                    </p>
                  </div>
                </div>

                <!-- Evolution banner -->
                <div class="flex items-center gap-3 p-3 rounded-xl bg-green-500/10 border border-green-500/20 mb-4">
                  <UIcon name="i-lucide-trending-down" class="w-5 h-5 text-green-400 shrink-0" />
                  <div>
                    <p class="text-xs font-semibold text-white">
                      Alto → Moderado
                    </p>
                    <p class="text-xs text-green-400 font-medium">
                      −12.5 puntos de riesgo — mejora detectada
                    </p>
                  </div>
                </div>

                <!-- Analysis entries -->
                <div class="space-y-2">
                  <div
                    v-for="(entry, i) in [
                      { date: '03/03/2026', period: 'Período 2', level: 'Moderado', score: '45.0', levelColor: 'text-yellow-400', levelBg: 'bg-yellow-500/10 border-yellow-500/20' },
                      { date: '15/02/2026', period: 'Período 2', level: 'Alto', score: '57.5', levelColor: 'text-red-400', levelBg: 'bg-red-500/10 border-red-500/20' }
                    ]"
                    :key="i"
                    class="flex items-center gap-3 p-2.5 rounded-xl bg-white/[0.03] border border-white/[0.05]"
                  >
                    <div class="min-w-0 flex-1">
                      <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-medium text-slate-300">{{ entry.date }}</span>
                        <span class="text-xs text-slate-600">{{ entry.period }}</span>
                      </div>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full border" :class="entry.levelBg">
                      <span :class="entry.levelColor" class="font-medium">{{ entry.level }}</span>
                    </span>
                    <span class="text-xs font-mono font-bold text-slate-400 shrink-0">{{ entry.score }}/100</span>
                  </div>
                </div>

                <!-- AI status -->
                <div class="mt-4 flex items-center gap-2 px-3 py-2 rounded-lg bg-indigo-500/8 border border-indigo-500/15">
                  <UIcon name="i-lucide-brain" class="w-3.5 h-3.5 text-indigo-400 animate-pulse shrink-0" />
                  <span class="text-xs text-indigo-300/70">Narrativa pedagógica generada con Claude AI</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ============ MODULES ============ -->
      <section id="modulos" class="py-24 px-4 sm:px-6 lg:px-8 bg-slate-950 border-b border-white/[0.06]" aria-labelledby="modulos-title">
        <div class="max-w-5xl mx-auto">
          <div class="text-center mb-12">
            <h2 id="modulos-title" class="text-3xl sm:text-4xl font-bold text-white mb-4">
              Todo lo que tu institución necesita,<br class="hidden sm:block"> en un solo lugar
            </h2>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto">
              13 módulos diseñados para el contexto educativo colombiano. Explora por categoría.
            </p>
          </div>

          <!-- Category tab strip -->
          <div class="flex flex-wrap gap-2 justify-center mb-10">
            <button
              v-for="cat in moduleCategories"
              :key="cat.key"
              class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-medium border transition-all duration-200"
              :class="activeCategoryKey === cat.key
                ? 'bg-indigo-500/20 border-indigo-500/40 text-indigo-300'
                : 'bg-white/[0.03] border-white/[0.08] text-slate-400 hover:text-white hover:bg-white/[0.06]'"
              @click="setModuleCategory(cat.key)"
            >
              <UIcon :name="cat.icon" class="w-3.5 h-3.5" />
              {{ cat.label }}
            </button>
          </div>

          <!-- Animated module grid -->
          <Transition name="mod-tab" mode="out-in">
            <div
              :key="activeCategoryKey"
              class="grid grid-cols-1 sm:grid-cols-2 gap-4"
            >
              <article
                v-for="mod in filteredModules"
                :key="mod.title"
                class="group flex items-start gap-4 bg-slate-900 rounded-2xl p-5 border border-white/[0.06] hover:border-white/[0.12] hover:-translate-y-0.5 transition-all duration-200"
              >
                <div class="p-2.5 rounded-xl shrink-0 mt-0.5" :class="mod.pill">
                  <UIcon :name="mod.icon" class="size-5" :class="mod.iconColor" />
                </div>
                <div class="min-w-0">
                  <div class="flex items-center gap-2 mb-1">
                    <h3 class="text-sm font-semibold text-white group-hover:text-indigo-300 transition-colors duration-200">
                      {{ mod.title }}
                    </h3>
                    <span
                      v-if="mod.badge"
                      class="text-xs px-1.5 py-0.5 rounded-md bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 font-semibold"
                    >
                      {{ mod.badge }}
                    </span>
                  </div>
                  <p class="text-xs text-slate-500 leading-relaxed">
                    {{ mod.description }}
                  </p>
                </div>
              </article>
            </div>
          </Transition>

          <!-- Module count hint -->
          <p class="text-center text-xs text-slate-700 mt-8">
            Mostrando {{ filteredModules.length }} de 13 módulos
          </p>
        </div>
      </section>

      <!-- ============ BENEFITS ============ -->
      <section id="beneficios" class="relative py-24 px-4 sm:px-6 lg:px-8 overflow-hidden border-b border-white/[0.06]" aria-labelledby="beneficios-title">
        <div aria-hidden="true" class="absolute inset-0 bg-gradient-to-br from-slate-950 via-indigo-950/50 to-slate-950 pointer-events-none" />
        <div aria-hidden="true" class="absolute top-0 right-0 w-[600px] h-[600px] rounded-full bg-violet-600/10 blur-[120px] pointer-events-none" />

        <div class="relative z-10 max-w-7xl mx-auto">
          <div class="text-center mb-16">
            <h2 id="beneficios-title" class="text-3xl sm:text-4xl font-bold text-white mb-4">
              Diseñado para cada actor de tu institución
            </h2>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto">
              Cada rol tiene exactamente lo que necesita, sin complejidad innecesaria.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Institution -->
            <article class="relative rounded-2xl p-8 overflow-hidden bg-blue-600/10 backdrop-blur-xl border border-blue-500/20 hover:border-blue-400/30 transition-all duration-300 hover:bg-blue-600/15">
              <div aria-hidden="true" class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-blue-500/20 blur-2xl pointer-events-none" />
              <div class="relative">
                <div class="p-3 bg-blue-500/20 rounded-xl border border-blue-400/20 w-fit mb-6">
                  <UIcon name="i-lucide-building-2" class="size-6 text-blue-300" />
                </div>
                <h3 class="text-xl font-bold text-white mb-6">
                  Para Directivos y Coordinadores
                </h3>
                <ul class="space-y-3">
                  <li v-for="b in benefitsInstitution" :key="b" class="flex items-start gap-3">
                    <UIcon name="i-lucide-check-circle" class="size-4 shrink-0 mt-0.5 text-blue-300" />
                    <span class="text-sm text-blue-100/80">{{ b }}</span>
                  </li>
                </ul>
              </div>
            </article>

            <!-- Teachers -->
            <article class="relative rounded-2xl p-8 overflow-hidden bg-emerald-600/10 backdrop-blur-xl border border-emerald-500/20 hover:border-emerald-400/30 transition-all duration-300 hover:bg-emerald-600/15">
              <div aria-hidden="true" class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-emerald-500/20 blur-2xl pointer-events-none" />
              <div class="relative">
                <div class="p-3 bg-emerald-500/20 rounded-xl border border-emerald-400/20 w-fit mb-6">
                  <UIcon name="i-lucide-book-open" class="size-6 text-emerald-300" />
                </div>
                <h3 class="text-xl font-bold text-white mb-6">
                  Para Docentes
                </h3>
                <ul class="space-y-3">
                  <li v-for="b in benefitsTeachers" :key="b" class="flex items-start gap-3">
                    <UIcon name="i-lucide-check-circle" class="size-4 shrink-0 mt-0.5 text-emerald-300" />
                    <span class="text-sm text-emerald-100/80">{{ b }}</span>
                  </li>
                </ul>
              </div>
            </article>

            <!-- Parents -->
            <article class="relative rounded-2xl p-8 overflow-hidden bg-purple-600/10 backdrop-blur-xl border border-purple-500/20 hover:border-purple-400/30 transition-all duration-300 hover:bg-purple-600/15">
              <div aria-hidden="true" class="absolute -top-12 -right-12 w-32 h-32 rounded-full bg-purple-500/20 blur-2xl pointer-events-none" />
              <div class="relative">
                <div class="p-3 bg-purple-500/20 rounded-xl border border-purple-400/20 w-fit mb-6">
                  <UIcon name="i-lucide-heart" class="size-6 text-purple-300" />
                </div>
                <h3 class="text-xl font-bold text-white mb-6">
                  Para Padres de Familia
                </h3>
                <ul class="space-y-3">
                  <li v-for="b in benefitsParents" :key="b" class="flex items-start gap-3">
                    <UIcon name="i-lucide-check-circle" class="size-4 shrink-0 mt-0.5 text-purple-300" />
                    <span class="text-sm text-purple-100/80">{{ b }}</span>
                  </li>
                </ul>
              </div>
            </article>
          </div>
        </div>
      </section>

      <!-- ============ SIEE / GRADING SCALE ============ -->
      <section class="py-24 px-4 sm:px-6 lg:px-8 bg-slate-900 border-b border-white/[0.06]" aria-labelledby="siee-title">
        <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-semibold uppercase tracking-widest mb-6">
              <UIcon name="i-lucide-award" class="w-3.5 h-3.5" />
              Normativa Colombiana
            </div>
            <h2 id="siee-title" class="text-3xl sm:text-4xl font-bold text-white mb-6">
              SIEE — Sistema Institucional de Evaluación
            </h2>
            <p class="text-lg text-slate-400 mb-8 leading-relaxed">
              Cumplimos con los estándares del Sistema Institucional de Evaluación de Estudiantes, permitiendo evaluar por logros cognitivos, procedimentales y actitudinales. La IA de Aula360 integra estos datos para construir un perfil pedagógico completo de cada estudiante.
            </p>
            <div class="space-y-4">
              <div
                v-for="item in [
                  { icon: 'i-lucide-brain', label: 'Cognitivos', sub: 'Conocimiento y comprensión' },
                  { icon: 'i-lucide-wrench', label: 'Procedimentales', sub: 'Habilidades y aplicación' },
                  { icon: 'i-lucide-heart-handshake', label: 'Actitudinales', sub: 'Comportamiento y valores' }
                ]"
                :key="item.label"
                class="flex items-center gap-3"
              >
                <div class="p-2.5 bg-indigo-500/10 rounded-lg border border-indigo-500/20 shrink-0">
                  <UIcon :name="item.icon" class="size-5 text-indigo-400" />
                </div>
                <div>
                  <span class="font-semibold text-white text-sm">{{ item.label }}</span>
                  <span class="text-slate-500 text-sm"> — {{ item.sub }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Grading scale card -->
          <div class="rounded-2xl bg-slate-800/60 border border-white/[0.08] p-8">
            <h3 class="text-lg font-bold text-white mb-6">
              Escala de Valoración Colombiana
            </h3>
            <div class="space-y-3">
              <div
                v-for="grade in gradeScale"
                :key="grade.level"
                class="flex items-center gap-4 p-4 rounded-xl border"
                :class="grade.bg"
              >
                <span class="w-20 font-bold text-sm" :class="grade.color">{{ grade.level }}</span>
                <span class="w-24 font-mono text-sm text-slate-300">{{ grade.range }}</span>
              </div>
            </div>
            <p class="mt-4 text-xs text-slate-500">
              Umbral de aprobación: <span class="text-white font-semibold">3.0</span> — nivel Básico
            </p>
          </div>
        </div>
      </section>

      <!-- ============ COMPARISON ============ -->
      <section id="comparativo" class="py-24 px-4 sm:px-6 lg:px-8 bg-slate-950 border-b border-white/[0.06]" aria-labelledby="comparativo-title">
        <div class="max-w-4xl mx-auto">
          <div class="text-center mb-16">
            <h2 id="comparativo-title" class="text-3xl sm:text-4xl font-bold text-white mb-4">
              Por qué Aula360 es diferente
            </h2>
            <p class="text-lg text-slate-400">
              Compara lo que obtienes frente a las soluciones tradicionales del mercado colombiano.
            </p>
          </div>

          <div class="bg-slate-900 rounded-2xl shadow-xl shadow-black/30 overflow-hidden border border-white/[0.06]">
            <table class="w-full" aria-label="Comparativa Aula360 vs otros sistemas">
              <thead>
                <tr class="bg-slate-800/50">
                  <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-slate-400 uppercase tracking-wide">
                    Característica
                  </th>
                  <th scope="col" class="px-6 py-4 text-center">
                    <div class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-indigo-600 text-white text-sm font-bold shadow-lg shadow-indigo-600/40">
                      <UIcon name="i-lucide-sparkles" class="w-3.5 h-3.5" />
                      Aula360
                    </div>
                  </th>
                  <th scope="col" class="px-6 py-4 text-center text-sm font-semibold text-slate-500">
                    Otros sistemas
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-white/[0.04]">
                <tr v-for="item in comparison" :key="item.feature" class="hover:bg-white/[0.02] transition-colors">
                  <th scope="row" class="px-6 py-4 text-sm text-slate-300 font-medium text-left">
                    {{ item.feature }}
                  </th>
                  <td class="px-6 py-4 text-center bg-indigo-500/[0.04]">
                    <UIcon name="i-lucide-check-circle" class="size-5 text-indigo-400 mx-auto" aria-label="Disponible" />
                  </td>
                  <td class="px-6 py-4 text-center">
                    <UIcon
                      v-if="item.others === false"
                      name="i-lucide-x-circle"
                      class="size-5 text-slate-600 mx-auto"
                      aria-label="No disponible"
                    />
                    <span v-else class="text-sm text-slate-500 italic">{{ item.others }}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- ============ CTA ============ -->
      <section id="contacto" class="relative overflow-hidden py-24 px-4 sm:px-6 lg:px-8" aria-labelledby="cta-title">
        <div aria-hidden="true" class="absolute inset-0 bg-gradient-to-br from-indigo-700 via-indigo-600 to-violet-700 pointer-events-none" />
        <div aria-hidden="true" class="absolute inset-0 opacity-30 pointer-events-none" style="background-image: radial-gradient(circle at 20% 80%, rgba(139,92,246,0.4) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(79,70,229,0.4) 0%, transparent 50%);" />
        <div aria-hidden="true" class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-white/30 to-transparent pointer-events-none" />

        <div class="relative z-10 max-w-4xl mx-auto text-center">
          <h2 id="cta-title" class="text-3xl sm:text-5xl font-extrabold text-white mb-6 leading-tight tracking-tight">
            Tu institución merece tomar<br class="hidden sm:block"> decisiones con datos reales.
          </h2>
          <p class="text-lg text-indigo-200 mb-10 max-w-2xl mx-auto leading-relaxed">
            Aula360 está lista para tu institución. Sé parte de las primeras instituciones colombianas en usar IA para detectar riesgo estudiantil, reducir carga administrativa y mantener a los padres informados.
          </p>
          <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a
              href="/login"
              class="inline-flex items-center px-8 py-3.5 text-base font-bold text-indigo-700 bg-white hover:bg-indigo-50 rounded-xl shadow-xl shadow-black/20 hover:-translate-y-0.5 transition-all duration-200"
            >
              <UIcon name="i-lucide-log-in" class="size-5 mr-2" />
              Ingresar al Sistema
            </a>
            <a
              href="#ia"
              class="inline-flex items-center px-8 py-3.5 text-base font-medium text-white border border-white/30 hover:bg-white/10 hover:border-white/50 backdrop-blur-sm rounded-xl transition-all duration-200"
            >
              Ver funcionalidades IA
            </a>
          </div>
        </div>
      </section>
    </main>

    <!-- ============ FOOTER ============ -->
    <footer class="bg-slate-950 border-t border-white/[0.06] py-12 px-4 sm:px-6 lg:px-8" role="contentinfo">
      <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
        <a href="/" class="flex items-center gap-3" aria-label="Aula360 - Inicio">
          <img
            src="/logo.svg"
            alt="Aula360"
            class="h-8 w-8"
            width="32"
            height="32"
            loading="lazy"
          >
          <span class="text-xl font-bold text-white">Aula<span class="text-indigo-400">360</span></span>
        </a>
        <p class="text-slate-500 text-center italic text-sm">
          Gestión Académica con IA para instituciones educativas colombianas.
        </p>
        <p class="text-slate-600 text-sm">
          © {{ new Date().getFullYear() }} Aula360. Todos los derechos reservados.
        </p>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.mod-tab-enter-active,
.mod-tab-leave-active {
  transition: opacity 0.18s ease, transform 0.18s ease;
}
.mod-tab-enter-from {
  opacity: 0;
  transform: translateY(10px);
}
.mod-tab-leave-to {
  opacity: 0;
  transform: translateY(-6px);
}
</style>
