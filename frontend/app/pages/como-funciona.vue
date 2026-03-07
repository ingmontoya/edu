<script setup lang="ts">
definePageMeta({
  layout: false
})

useSeoMeta({
  title: 'Cómo funciona Aula360 — Ciclo Académico para Colegios Colombianos',
  description: 'Descubre cómo Aula360 gestiona el ciclo académico completo: notas, asistencia, riesgo estudiantil con IA, boletines PDF, SIEE y portal de padres.',
  keywords: 'cómo funciona software gestión académica, ciclo académico colegio colombia, índice riesgo estudiantil IA, boletines PDF automáticos colegio, portal padres colegio colombia, SIEE evaluación logros colombia, SIMAT exportación MEN, asistencia escolar digital, calificaciones escala colombiana, convivencia escolar Ley 1620',
  author: 'Aula360',
  robots: 'index, follow',
  ogTitle: 'Cómo funciona Aula360 — Ciclo Académico para Colegios Colombianos',
  ogDescription: 'Descubre cómo Aula360 gestiona el ciclo académico completo: notas, asistencia, riesgo estudiantil con IA, boletines PDF, SIEE y portal de padres.',
  ogType: 'website',
  ogImage: 'https://aula360.com/og-image.png',
  ogImageWidth: '1200',
  ogImageHeight: '630',
  ogImageAlt: 'Aula360 — Cómo funciona la plataforma de gestión académica para colegios colombianos',
  ogUrl: 'https://aula360.com/como-funciona',
  ogLocale: 'es_CO',
  ogLocaleAlternate: ['es_ES', 'es_419'],
  ogSiteName: 'Aula360',
  twitterCard: 'summary_large_image',
  twitterSite: '@aula360co',
  twitterCreator: '@aula360co',
  twitterTitle: 'Cómo funciona Aula360 — Ciclo Académico para Colegios Colombianos',
  twitterDescription: 'Desde la configuración inicial hasta el boletín PDF. Todo en una sola plataforma para colegios colombianos.',
  twitterImage: 'https://aula360.com/og-image.png'
})

useHead({
  htmlAttrs: { lang: 'es-CO' },
  meta: [
    { name: 'viewport', content: 'width=device-width, initial-scale=1' },
    { name: 'theme-color', content: '#006DB0' },
    { name: 'color-scheme', content: 'light' }
  ],
  link: [
    { rel: 'preconnect', href: 'https://fonts.googleapis.com' },
    { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: '' },
    { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800;900&display=swap' },
    { rel: 'canonical', href: 'https://aula360.com/como-funciona' },
    { rel: 'sitemap', type: 'application/xml', href: 'https://aula360.com/sitemap.xml' }
  ],
  script: [
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'WebPage',
        'name': 'Cómo funciona Aula360',
        'url': 'https://aula360.com/como-funciona',
        'description': 'Flujos detallados de la plataforma Aula360: ciclo académico, índice de riesgo estudiantil con IA, generación de boletines PDF y portal de padres para instituciones educativas colombianas.',
        'inLanguage': 'es-CO',
        'isPartOf': { '@type': 'WebSite', 'url': 'https://aula360.com', 'name': 'Aula360' },
        'about': { '@type': 'SoftwareApplication', 'name': 'Aula360', 'applicationCategory': 'EducationalApplication', 'url': 'https://aula360.com' }
      })
    },
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'BreadcrumbList',
        'itemListElement': [
          { '@type': 'ListItem', 'position': 1, 'name': 'Inicio', 'item': 'https://aula360.com' },
          { '@type': 'ListItem', 'position': 2, 'name': 'Cómo funciona', 'item': 'https://aula360.com/como-funciona' }
        ]
      })
    }
  ]
})

const showDemo = ref(false)

const cycleSteps = [
  {
    number: '01',
    icon: 'i-lucide-settings-2',
    title: 'Configuración institucional',
    description: 'Define el año académico, períodos con sus pesos porcentuales, grados, grupos y asignaturas. Una vez configurado, el sistema opera de forma autónoma.',
    color: 'blue'
  },
  {
    number: '02',
    icon: 'i-lucide-users',
    title: 'Docentes y estudiantes',
    description: 'Registra el equipo docente, importa o crea estudiantes con sus acudientes. Cada rol accede solo a lo que le corresponde.',
    color: 'indigo'
  },
  {
    number: '03',
    icon: 'i-lucide-clipboard-list',
    title: 'Logros y asignaturas',
    description: 'Define los logros SIEE por asignatura y período: cognitivos, procedimentales y actitudinales. Importación CSV o copia entre períodos.',
    color: 'violet'
  },
  {
    number: '04',
    icon: 'i-lucide-pencil',
    title: 'Notas y asistencia',
    description: 'Los docentes registran calificaciones (escala colombiana) y asistencia diaria. Promedios ponderados y rankings calculados automáticamente.',
    color: 'purple'
  },
  {
    number: '05',
    icon: 'i-lucide-brain',
    title: 'Análisis con IA',
    description: 'El motor de IA calcula el índice de riesgo por estudiante y genera recomendaciones pedagógicas personalizadas con IA.',
    color: 'cyan'
  },
  {
    number: '06',
    icon: 'i-lucide-file-text',
    title: 'Boletines y portal',
    description: 'Genera boletines PDF individuales o masivos con un clic. Los acudientes acceden al portal 24/7 para ver notas, asistencia y descargar documentos.',
    color: 'emerald'
  }
]

const riskInputs = [
  { icon: 'i-lucide-x-circle', label: 'Materias reprobadas', sub: 'Conteo y peso por asignatura', color: 'text-red-500', bg: 'bg-red-50 border-red-200' },
  { icon: 'i-lucide-calendar-x', label: 'Porcentaje de asistencia', sub: 'Inasistencias vs. días hábiles', color: 'text-orange-500', bg: 'bg-orange-50 border-orange-200' },
  { icon: 'i-lucide-message-square-warning', label: 'Incidentes disciplinarios', sub: 'Ley 1620 — tipo y frecuencia', color: 'text-amber-500', bg: 'bg-amber-50 border-amber-200' },
  { icon: 'i-lucide-refresh-cw', label: 'Nivelaciones pendientes', sub: 'Sin calificar o reprobadas', color: 'text-yellow-600', bg: 'bg-yellow-50 border-yellow-200' },
  { icon: 'i-lucide-trending-down', label: 'Tendencia de notas', sub: 'Comparativo período anterior', color: 'text-rose-500', bg: 'bg-rose-50 border-rose-200' }
]

const riskLevels = [
  { range: '70 – 100', label: 'Riesgo Alto', desc: 'Requiere intervención inmediata', bg: 'bg-red-600', text: 'text-white', border: 'border-red-600' },
  { range: '35 – 69', label: 'Riesgo Moderado', desc: 'Seguimiento cercano recomendado', bg: 'bg-amber-500', text: 'text-white', border: 'border-amber-500' },
  { range: '0 – 34', label: 'Sin riesgo', desc: 'Desempeño estable', bg: 'bg-emerald-600', text: 'text-white', border: 'border-emerald-600' }
]

const boletinSteps = [
  { icon: 'i-lucide-pencil', title: 'Docente registra notas', desc: 'Calificación por logro o nota directa. Promedio ponderado automático.', bg: 'bg-blue-50', iconColor: 'text-blue-600' },
  { icon: 'i-lucide-calculator', title: 'Sistema calcula', desc: 'Promedio del período, puesto en el grupo, nivel de desempeño (Bajo/Básico/Alto/Superior).', bg: 'bg-indigo-50', iconColor: 'text-indigo-600' },
  { icon: 'i-lucide-message-circle', title: 'Observaciones del docente', desc: 'Comentario cualitativo por estudiante vinculado al boletín.', bg: 'bg-violet-50', iconColor: 'text-violet-600' },
  { icon: 'i-lucide-file-text', title: 'Generación del PDF', desc: 'Logo institucional, datos del estudiante, notas por asignatura, puesto y observaciones.', bg: 'bg-purple-50', iconColor: 'text-purple-600' },
  { icon: 'i-lucide-download', title: 'Descarga individual o masiva', desc: 'Un clic para descargar todos los boletines del grupo en un archivo ZIP.', bg: 'bg-emerald-50', iconColor: 'text-emerald-600' }
]

const portalCards = [
  { icon: 'i-lucide-star', title: 'Notas en tiempo real', desc: 'El acudiente ve las calificaciones del período actual tan pronto el docente las registra.', color: 'text-blue-600', bg: 'bg-blue-50' },
  { icon: 'i-lucide-calendar', title: 'Historial de asistencia', desc: 'Vista completa de presencias, ausencias, tardanzas y excusas por período.', color: 'text-indigo-600', bg: 'bg-indigo-50' },
  { icon: 'i-lucide-file-down', title: 'Descarga de documentos', desc: 'Boletines de notas y constancias (matrícula, calificaciones) disponibles 24/7.', color: 'text-violet-600', bg: 'bg-violet-50' },
  { icon: 'i-lucide-clipboard-check', title: 'Tareas y entregas', desc: 'Consulta las tareas asignadas y el estado de entrega de cada trabajo.', color: 'text-emerald-600', bg: 'bg-emerald-50' }
]

const colorMap: Record<string, { dot: string; connector: string; number: string }> = {
  blue: { dot: 'bg-blue-600', connector: 'bg-blue-200', number: 'text-blue-600 bg-blue-50 border-blue-200' },
  indigo: { dot: 'bg-indigo-600', connector: 'bg-indigo-200', number: 'text-indigo-600 bg-indigo-50 border-indigo-200' },
  violet: { dot: 'bg-violet-600', connector: 'bg-violet-200', number: 'text-violet-600 bg-violet-50 border-violet-200' },
  purple: { dot: 'bg-purple-600', connector: 'bg-purple-200', number: 'text-purple-600 bg-purple-50 border-purple-200' },
  cyan: { dot: 'bg-cyan-600', connector: 'bg-cyan-200', number: 'text-cyan-600 bg-cyan-50 border-cyan-200' },
  emerald: { dot: 'bg-emerald-600', connector: 'bg-emerald-200', number: 'text-emerald-600 bg-emerald-50 border-emerald-200' }
}
</script>

<template>
  <div class="min-h-screen bg-white" style="font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;">
    <!-- NAVBAR -->
    <PublicNavbar @open-demo="showDemo = true" />

    <main class="pt-16">

      <!-- ============ HERO ============ -->
      <section class="bg-gradient-to-br from-slate-50 via-white to-blue-50/30 py-20 px-4 sm:px-6 lg:px-8 border-b border-slate-200">
        <div class="max-w-3xl mx-auto text-center">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-medium tracking-wide mb-6">
            <UIcon name="i-lucide-map" class="w-3.5 h-3.5" />
            Cómo funciona
          </div>
          <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 leading-tight tracking-tight mb-5">
            Así opera Aula360,<br>
            <span class="text-blue-600">de principio a fin</span>
          </h1>
          <p class="text-lg text-slate-500 leading-relaxed max-w-2xl mx-auto">
            Desde la configuración inicial hasta que el padre descarga el boletín de su hijo — todo en una sola plataforma, sin piezas sueltas.
          </p>
        </div>
      </section>

      <!-- ============ CICLO COMPLETO ============ -->
      <section class="py-24 px-4 sm:px-6 lg:px-8 bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto">
          <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
              El ciclo académico completo
            </h2>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto">
              Seis pasos que cubren todo el año escolar. Cada uno se apoya en el anterior.
            </p>
          </div>

          <!-- Flow: vertical on mobile, timeline on desktop -->
          <div class="relative">
            <!-- Vertical connector line (desktop) -->
            <div class="hidden lg:block absolute left-1/2 top-8 bottom-8 w-0.5 bg-slate-100 -translate-x-1/2" />

            <div class="space-y-6 lg:space-y-0">
              <div
                v-for="(step, i) in cycleSteps"
                :key="step.number"
                class="relative lg:grid lg:grid-cols-2 lg:gap-12 lg:items-center"
                :class="i % 2 === 0 ? '' : 'lg:flex-row-reverse'"
              >
                <!-- Content: alternates left/right on desktop -->
                <div
                  class="bg-white rounded-2xl border border-slate-200 p-7 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200"
                  :class="i % 2 !== 0 ? 'lg:col-start-2' : ''"
                >
                  <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                      :class="{
                        'bg-blue-50': step.color === 'blue',
                        'bg-indigo-50': step.color === 'indigo',
                        'bg-violet-50': step.color === 'violet',
                        'bg-purple-50': step.color === 'purple',
                        'bg-cyan-50': step.color === 'cyan',
                        'bg-emerald-50': step.color === 'emerald',
                      }"
                    >
                      <UIcon :name="step.icon" class="size-5"
                        :class="{
                          'text-blue-600': step.color === 'blue',
                          'text-indigo-600': step.color === 'indigo',
                          'text-violet-600': step.color === 'violet',
                          'text-purple-600': step.color === 'purple',
                          'text-cyan-600': step.color === 'cyan',
                          'text-emerald-600': step.color === 'emerald',
                        }"
                      />
                    </div>
                    <div>
                      <div class="flex items-center gap-2 mb-1.5">
                        <span class="text-xs font-bold tracking-widest"
                          :class="{
                            'text-blue-500': step.color === 'blue',
                            'text-indigo-500': step.color === 'indigo',
                            'text-violet-500': step.color === 'violet',
                            'text-purple-500': step.color === 'purple',
                            'text-cyan-500': step.color === 'cyan',
                            'text-emerald-500': step.color === 'emerald',
                          }"
                        >PASO {{ step.number }}</span>
                      </div>
                      <h3 class="text-base font-bold text-slate-900 mb-1.5">{{ step.title }}</h3>
                      <p class="text-sm text-slate-500 leading-relaxed">{{ step.description }}</p>
                    </div>
                  </div>
                </div>

                <!-- Center dot on desktop -->
                <div class="hidden lg:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-5 h-5 rounded-full border-4 border-white z-10 shadow"
                  :class="colorMap[step.color].dot"
                />

                <!-- Empty col for alternating layout -->
                <div :class="i % 2 !== 0 ? 'lg:col-start-1 lg:row-start-1' : ''" />
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ============ FLUJO RIESGO IA ============ -->
      <section class="py-24 px-4 sm:px-6 lg:px-8 bg-slate-50 border-b border-slate-200">
        <div class="max-w-5xl mx-auto">
          <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-medium tracking-wide mb-4">
              <UIcon name="i-lucide-shield-alert" class="w-3.5 h-3.5" />
              Motor de IA
            </div>
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
              ¿Cómo se calcula el índice de riesgo?
            </h2>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto">
              El sistema combina cinco variables académicas en un puntaje único de 0 a 100. Cuanto mayor el puntaje, mayor el riesgo.
            </p>
          </div>

          <div class="grid lg:grid-cols-3 gap-8 items-start">
            <!-- Inputs -->
            <div class="space-y-3">
              <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-5">Entradas al modelo</p>
              <div
                v-for="input in riskInputs"
                :key="input.label"
                class="flex items-start gap-3 rounded-xl border p-4 bg-white shadow-sm"
                :class="input.bg"
              >
                <UIcon :name="input.icon" class="size-4 shrink-0 mt-0.5" :class="input.color" />
                <div>
                  <p class="text-sm font-semibold text-slate-800">{{ input.label }}</p>
                  <p class="text-xs text-slate-500">{{ input.sub }}</p>
                </div>
              </div>
            </div>

            <!-- Arrow + Score -->
            <div class="flex flex-col items-center justify-center gap-6 py-4">
              <!-- Arrow -->
              <div class="hidden lg:flex flex-col items-center gap-1 text-slate-300">
                <UIcon name="i-lucide-arrow-right" class="size-8" />
              </div>

              <!-- Score display -->
              <div class="w-full rounded-2xl bg-white border border-blue-200 shadow-lg p-7 text-center">
                <UIcon name="i-lucide-brain" class="size-8 text-blue-600 mx-auto mb-3 animate-pulse" />
                <p class="text-xs font-semibold uppercase tracking-widest text-blue-500 mb-2">IA analiza y pondera</p>
                <div class="text-6xl font-black text-slate-900 leading-none mb-2">0–100</div>
                <p class="text-sm text-slate-400">Puntaje de riesgo por estudiante</p>

                <div class="mt-5 h-3 w-full rounded-full overflow-hidden bg-slate-100">
                  <div class="h-full w-full rounded-full risk-gradient" />
                </div>
                <div class="flex justify-between text-xs text-slate-400 mt-1.5">
                  <span>Sin riesgo</span>
                  <span>Riesgo Alto</span>
                </div>
              </div>

              <!-- Down arrow mobile -->
              <UIcon name="i-lucide-arrow-down" class="size-6 text-slate-300 lg:hidden" />
            </div>

            <!-- Output: levels -->
            <div class="space-y-3">
              <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-5">Clasificación y acción</p>
              <div
                v-for="level in riskLevels"
                :key="level.label"
                class="rounded-xl border p-4 shadow-sm"
                :class="[level.bg, level.border]"
              >
                <div class="flex items-center justify-between mb-1">
                  <p class="text-sm font-bold" :class="level.text">{{ level.label }}</p>
                  <span class="text-xs font-mono font-semibold px-2 py-0.5 rounded-full bg-white/20" :class="level.text">{{ level.range }}</span>
                </div>
                <p class="text-xs" :class="level.text" style="opacity:0.85">{{ level.desc }}</p>
              </div>

              <!-- AI narrative output -->
              <div class="rounded-xl border border-violet-200 bg-violet-50 p-4 shadow-sm mt-2">
                <div class="flex items-center gap-2 mb-2">
                  <UIcon name="i-lucide-sparkles" class="size-3.5 text-violet-600" />
                  <p class="text-sm font-bold text-violet-800">Narrativa pedagógica IA</p>
                </div>
                <p class="text-xs text-violet-700 leading-relaxed">
                  La IA genera un informe personalizado con análisis de causas y recomendaciones concretas de intervención para el docente.
                </p>
              </div>
            </div>
          </div>

          <!-- Weekly executive summary callout -->
          <div class="mt-10 rounded-2xl bg-white border border-slate-200 shadow-sm p-7 flex flex-col sm:flex-row items-start sm:items-center gap-5">
            <div class="p-3 rounded-xl bg-purple-50 shrink-0">
              <UIcon name="i-lucide-layout-dashboard" class="size-6 text-purple-600" />
            </div>
            <div>
              <p class="text-base font-bold text-slate-900 mb-1">Resumen ejecutivo semanal</p>
              <p class="text-sm text-slate-500 leading-relaxed">
                Cada semana, la IA consolida el estado de riesgo de toda la institución en un reporte ejecutivo para coordinadores: cuántos estudiantes en alerta, tendencias por grado y grupos prioritarios.
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- ============ FLUJO BOLETINES ============ -->
      <section class="py-24 px-4 sm:px-6 lg:px-8 bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto">
          <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium tracking-wide mb-4">
              <UIcon name="i-lucide-file-text" class="w-3.5 h-3.5" />
              Boletines PDF
            </div>
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
              Del registro de notas al PDF
            </h2>
            <p class="text-slate-500 text-lg max-w-2xl mx-auto">
              El boletín se genera automáticamente. El docente no toca Word ni diseña nada.
            </p>
          </div>

          <!-- Horizontal flow -->
          <div class="relative">
            <!-- Connector line desktop -->
            <div class="hidden sm:block absolute top-10 left-10 right-10 h-0.5 bg-slate-100" style="top: 2.5rem;" />

            <div class="grid grid-cols-1 sm:grid-cols-5 gap-4 sm:gap-3">
              <div
                v-for="(step, i) in boletinSteps"
                :key="step.title"
                class="relative flex flex-col items-center text-center"
              >
                <!-- Step dot -->
                <div class="relative z-10 w-12 h-12 rounded-xl flex items-center justify-center mb-4 border border-slate-200 shadow-sm" :class="step.bg">
                  <UIcon :name="step.icon" class="size-5" :class="step.iconColor" />
                </div>
                <!-- Step number -->
                <span class="text-xs font-bold text-slate-300 mb-1.5">0{{ i + 1 }}</span>
                <p class="text-sm font-semibold text-slate-900 mb-1.5 leading-snug">{{ step.title }}</p>
                <p class="text-xs text-slate-500 leading-relaxed">{{ step.desc }}</p>

                <!-- Arrow between steps -->
                <div v-if="i < boletinSteps.length - 1" class="hidden sm:flex absolute right-0 top-10 -translate-y-1/2 translate-x-1/2 z-20">
                  <UIcon name="i-lucide-chevron-right" class="size-4 text-slate-300" />
                </div>
              </div>
            </div>
          </div>

          <!-- Boletin preview mock -->
          <div class="mt-14 rounded-2xl border border-slate-200 bg-slate-50 p-8 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 text-center mb-6">Vista previa del boletín generado</p>
            <div class="max-w-md mx-auto bg-white rounded-xl border border-slate-200 shadow-md overflow-hidden">
              <!-- Header institucional -->
              <div class="bg-blue-600 px-6 py-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                  <UIcon name="i-lucide-school" class="size-5 text-white" />
                </div>
                <div>
                  <p class="text-white font-bold text-sm leading-tight">Institución Educativa Ejemplo</p>
                  <p class="text-blue-200 text-xs">Boletín Académico — Período 2 · 2024</p>
                </div>
              </div>
              <!-- Student info -->
              <div class="px-6 py-4 border-b border-slate-100">
                <div class="flex items-center justify-between">
                  <div>
                    <p class="text-sm font-bold text-slate-900">Valentina García</p>
                    <p class="text-xs text-slate-500">Grado 9° · Grupo A · Puesto <span class="font-semibold text-slate-700">#3 de 32</span></p>
                  </div>
                  <div class="text-right">
                    <p class="text-2xl font-black text-blue-600">4.2</p>
                    <p class="text-xs text-slate-400">Promedio</p>
                  </div>
                </div>
              </div>
              <!-- Grades -->
              <div class="px-6 py-4 space-y-2">
                <div v-for="(subject, idx) in [
                  { name: 'Matemáticas', grade: 3.8, level: 'Básico' },
                  { name: 'Lengua Castellana', grade: 4.5, level: 'Alto' },
                  { name: 'Ciencias Naturales', grade: 4.6, level: 'Superior' },
                  { name: 'Inglés', grade: 3.2, level: 'Básico' }
                ]" :key="idx" class="flex items-center justify-between py-1.5">
                  <p class="text-xs text-slate-700 font-medium">{{ subject.name }}</p>
                  <div class="flex items-center gap-2">
                    <span class="text-xs px-1.5 py-0.5 rounded text-slate-500"
                      :class="{
                        'bg-red-50 text-red-600': subject.grade < 3,
                        'bg-yellow-50 text-yellow-600': subject.grade >= 3 && subject.grade < 4,
                        'bg-blue-50 text-blue-600': subject.grade >= 4 && subject.grade < 4.6,
                        'bg-emerald-50 text-emerald-600': subject.grade >= 4.6,
                      }"
                    >{{ subject.level }}</span>
                    <span class="text-sm font-bold text-slate-900 w-8 text-right">{{ subject.grade }}</span>
                  </div>
                </div>
              </div>
              <!-- Observacion -->
              <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                <p class="text-xs text-slate-400 mb-1 font-medium">Observación del docente</p>
                <p class="text-xs text-slate-600 italic leading-relaxed">
                  "Valentina muestra fortaleza en ciencias. Se recomienda refuerzo en matemáticas, especialmente en álgebra."
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ============ PORTAL DE PADRES ============ -->
      <section class="py-24 px-4 sm:px-6 lg:px-8 bg-slate-50 border-b border-slate-200">
        <div class="max-w-5xl mx-auto">
          <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
              <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-50 border border-violet-200 text-violet-700 text-xs font-medium tracking-wide mb-6">
                <UIcon name="i-lucide-home" class="w-3.5 h-3.5" />
                Portal de Padres
              </div>
              <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-5 leading-tight">
                El acudiente siempre informado,<br>
                <span class="text-violet-600">sin llamar al colegio</span>
              </h2>
              <p class="text-slate-500 text-lg leading-relaxed mb-8">
                El portal es un acceso privado por acudiente. Sin instalar aplicaciones: funciona desde el navegador del celular o computador.
              </p>
              <ul class="space-y-4">
                <li
                  v-for="card in portalCards"
                  :key="card.title"
                  class="flex items-start gap-4"
                >
                  <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mt-0.5" :class="card.bg">
                    <UIcon :name="card.icon" class="size-4" :class="card.color" />
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-slate-900 mb-0.5">{{ card.title }}</p>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ card.desc }}</p>
                  </div>
                </li>
              </ul>
            </div>

            <!-- Portal mock -->
            <div class="rounded-2xl bg-white border border-slate-200 shadow-xl overflow-hidden">
              <!-- Mobile-style header -->
              <div class="bg-violet-600 px-5 py-5">
                <div class="flex items-center gap-3 mb-4">
                  <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center">
                    <UIcon name="i-lucide-user" class="size-4 text-white" />
                  </div>
                  <div>
                    <p class="text-white text-sm font-semibold">Carolina García</p>
                    <p class="text-violet-200 text-xs">Acudiente — Valentina García</p>
                  </div>
                </div>
                <div class="grid grid-cols-3 gap-2">
                  <div class="rounded-lg bg-white/10 p-2.5 text-center">
                    <p class="text-white text-lg font-black">4.2</p>
                    <p class="text-violet-200 text-xs">Promedio</p>
                  </div>
                  <div class="rounded-lg bg-white/10 p-2.5 text-center">
                    <p class="text-white text-lg font-black">94%</p>
                    <p class="text-violet-200 text-xs">Asistencia</p>
                  </div>
                  <div class="rounded-lg bg-white/10 p-2.5 text-center">
                    <p class="text-white text-lg font-black">#3</p>
                    <p class="text-violet-200 text-xs">Puesto</p>
                  </div>
                </div>
              </div>

              <!-- Content -->
              <div class="p-5 space-y-3">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">Notas recientes</p>
                <div v-for="(row, i) in [
                  { subject: 'Ciencias Naturales', grade: 4.6, delta: '+0.4', up: true },
                  { subject: 'Lengua Castellana', grade: 4.5, delta: '+0.1', up: true },
                  { subject: 'Matemáticas', grade: 3.8, delta: '-0.2', up: false },
                ]" :key="i" class="flex items-center justify-between py-2.5 border-b border-slate-50 last:border-0">
                  <p class="text-sm text-slate-700 font-medium">{{ row.subject }}</p>
                  <div class="flex items-center gap-2">
                    <span class="text-xs" :class="row.up ? 'text-emerald-500' : 'text-red-400'">{{ row.delta }}</span>
                    <span class="text-sm font-bold text-slate-900">{{ row.grade }}</span>
                  </div>
                </div>

                <div class="pt-2 flex gap-2">
                  <button type="button" class="flex-1 flex items-center justify-center gap-1.5 text-xs font-semibold text-violet-600 bg-violet-50 border border-violet-200 rounded-lg py-2.5 hover:bg-violet-100 transition-colors cursor-pointer">
                    <UIcon name="i-lucide-download" class="size-3.5" />
                    Descargar boletín
                  </button>
                  <button type="button" class="flex-1 flex items-center justify-center gap-1.5 text-xs font-semibold text-slate-600 bg-slate-50 border border-slate-200 rounded-lg py-2.5 hover:bg-slate-100 transition-colors cursor-pointer">
                    <UIcon name="i-lucide-file-badge" class="size-3.5" />
                    Constancia
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ============ NORMATIVA ============ -->
      <section class="py-16 px-4 sm:px-6 lg:px-8 bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto">
          <div class="text-center mb-10">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 mb-3">
              Cumplimiento normativo incluido
            </h2>
            <p class="text-slate-500 max-w-xl mx-auto">
              Aula360 fue construido con la normativa colombiana como base, no como un anexo.
            </p>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6">
              <UIcon name="i-lucide-target" class="size-6 text-amber-600 mb-3" />
              <h3 class="text-sm font-bold text-slate-900 mb-1.5">SIEE — Evaluación por Logros</h3>
              <p class="text-xs text-slate-500 leading-relaxed">
                Logros cognitivos, procedimentales y actitudinales con indicadores de desempeño. Escala Bajo / Básico / Alto / Superior según el decreto 1290.
              </p>
            </div>
            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-6">
              <UIcon name="i-lucide-download" class="size-6 text-blue-600 mb-3" />
              <h3 class="text-sm font-bold text-slate-900 mb-1.5">Exportación SIMAT (MEN)</h3>
              <p class="text-xs text-slate-500 leading-relaxed">
                Exporta el registro estudiantil en el formato oficial requerido por el Ministerio de Educación Nacional para el Sistema Integrado de Matrícula.
              </p>
            </div>
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-6">
              <UIcon name="i-lucide-message-square" class="size-6 text-rose-600 mb-3" />
              <h3 class="text-sm font-bold text-slate-900 mb-1.5">Convivencia — Ley 1620/2013</h3>
              <p class="text-xs text-slate-500 leading-relaxed">
                Registro de incidentes disciplinarios clasificados por tipo. El historial alimenta directamente el índice de riesgo estudiantil con IA.
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- ============ CTA ============ -->
      <section class="py-16 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-5xl mx-auto">
          <div class="rounded-3xl px-8 py-16 sm:px-16 sm:py-20 text-center bg-gradient-to-br from-teal-100 via-cyan-50 to-emerald-100">
            <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 mb-6 leading-tight tracking-tight">
              Tu institución merece tomar<br class="hidden sm:block"> decisiones con datos reales.
            </h2>
            <p class="text-lg text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
              Aula360 está lista para tu institución. Sé parte de las primeras instituciones en usar IA para detectar riesgo estudiantil, reducir carga administrativa y mantener a los padres informados.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
              <button
                type="button"
                class="inline-flex items-center px-8 py-3.5 text-base font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl shadow-lg hover:-translate-y-0.5 transition-all duration-200 cursor-pointer"
                @click="showDemo = true"
              >
                Solicitar Demo Gratuita
                <UIcon name="i-lucide-arrow-right" class="size-5 ml-2" />
              </button>
              <a
                href="/login"
                class="inline-flex items-center px-8 py-3.5 text-base font-medium text-slate-900 border-2 border-slate-900 hover:bg-slate-900/10 rounded-xl transition-all duration-200"
              >
                Ingresar al Sistema
                <UIcon name="i-lucide-arrow-right" class="size-5 ml-2" />
              </a>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- FOOTER -->
    <PublicFooter />

    <DemoFormModal v-model="showDemo" />
  </div>
</template>

<style scoped>
.risk-gradient {
  background: linear-gradient(to right, #10b981, #f59e0b, #ef4444);
}


</style>
