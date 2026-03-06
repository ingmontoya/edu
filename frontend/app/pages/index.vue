<script setup lang="ts">
definePageMeta({
  layout: false
})

useSeoMeta({
  title: 'Aula360 — Gestión Académica con IA para Colegios Colombianos',
  description: 'Aula360 transforma la gestión académica con Inteligencia Artificial. Índice de riesgo estudiantil, análisis pedagógico con IA, boletines PDF, SIEE y portal de padres. Diseñado para instituciones educativas colombianas.',
  keywords: 'software gestión académica colombia, plataforma educativa colombia, SIEE colombia, boletines PDF colegio, riesgo estudiantil IA, software colegio colombia, calificaciones colegio, inteligencia artificial educación, Aula360',
  author: 'Aula360',
  robots: 'index, follow',
  ogTitle: 'Aula360 — Gestión Académica con IA para Colegios Colombianos',
  ogDescription: 'Detecta el riesgo estudiantil antes de que sea tarde. Análisis pedagógico con IA, boletines PDF, SIEE y portal de padres para colegios colombianos.',
  ogType: 'website',
  ogImage: 'https://aula360.com/og-image.png',
  ogImageWidth: '1200',
  ogImageHeight: '630',
  ogImageAlt: 'Aula360 — Plataforma de gestión académica con IA para colegios colombianos',
  ogUrl: 'https://aula360.com',
  ogLocale: 'es_CO',
  ogLocaleAlternate: ['es_ES', 'es_419'],
  ogSiteName: 'Aula360',
  twitterCard: 'summary_large_image',
  twitterSite: '@aula360co',
  twitterCreator: '@aula360co',
  twitterTitle: 'Aula360 — Gestión Académica con IA para Colegios Colombianos',
  twitterDescription: 'Detecta el riesgo estudiantil antes de que sea tarde. IA aplicada a la educación colombiana.',
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
    { rel: 'canonical', href: 'https://aula360.com' },
    { rel: 'sitemap', type: 'application/xml', href: 'https://aula360.com/sitemap.xml' }
  ],
  script: [
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'SoftwareApplication',
        'name': 'Aula360',
        'url': 'https://aula360.com',
        'applicationCategory': 'EducationalApplication',
        'operatingSystem': 'Web',
        'description': 'Sistema de gestión académica con IA para instituciones educativas colombianas. Incluye índice de riesgo estudiantil, análisis pedagógico con Claude AI, SIEE, boletines PDF y portal de padres.',
        'priceRange': '$490.000 - $2.900.000+ COP/mes',
        'offers': {
          '@type': 'Offer',
          'price': '490000',
          'priceCurrency': 'COP',
          'eligibleRegion': { '@type': 'Country', 'name': 'Colombia' },
          'priceSpecification': {
            '@type': 'UnitPriceSpecification',
            'price': '490000',
            'priceCurrency': 'COP',
            'billingIncrement': 1,
            'unitCode': 'MON'
          }
        },
        'provider': {
          '@type': 'Organization',
          'name': 'Aula360',
          'url': 'https://aula360.com'
        },
        'screenshot': 'https://aula360.com/og-image.png',
        'inLanguage': 'es-CO',
        'featureList': [
          'Índice de Riesgo Estudiantil con IA',
          'Análisis pedagógico por estudiante con Claude AI',
          'Cumplimiento SIEE colombiano',
          'Boletines PDF individuales y masivos',
          'Portal de padres 24/7',
          'Exportación SIMAT (MEN)',
          'Convivencia Escolar Ley 1620/2013',
          'Constancias y certificados PDF',
          'Resumen ejecutivo semanal con IA'
        ]
      })
    },
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'Organization',
        'name': 'Aula360',
        'url': 'https://aula360.com',
        'logo': 'https://aula360.com/logo.svg',
        'description': 'Plataforma SaaS de gestión académica con Inteligencia Artificial para instituciones educativas colombianas.',
        'areaServed': { '@type': 'Country', 'name': 'Colombia' },
        'knowsAbout': [
          'Gestión académica escolar',
          'Inteligencia Artificial en educación',
          'SIEE Colombia',
          'Sistema Integrado de Matrícula SIMAT',
          'Evaluación por logros',
          'Convivencia escolar Ley 1620'
        ],
        'contactPoint': {
          '@type': 'ContactPoint',
          'contactType': 'sales',
          'availableLanguage': { '@type': 'Language', 'name': 'Spanish' }
        }
      })
    },
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'WebSite',
        'name': 'Aula360',
        'url': 'https://aula360.com',
        'description': 'Gestión académica con IA para instituciones educativas colombianas.',
        'inLanguage': 'es-CO'
      })
    },
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'BreadcrumbList',
        'itemListElement': [
          { '@type': 'ListItem', 'position': 1, 'name': 'Inicio', 'item': 'https://aula360.com' }
        ]
      })
    }
  ]
})

const gradientOverlay = 'linear-gradient(192deg, rgba(255,255,255,0.00) 40%, rgba(255,255,255,0.18) 100%), linear-gradient(0deg, rgba(0,0,0,0.08) 0%, rgba(0,0,0,0.08) 100%)'

const aiFeatures = [
  {
    icon: 'i-lucide-shield-alert',
    title: 'Índice de Riesgo Estudiantil',
    description: 'Calcula un puntaje 0–100 por estudiante combinando materias reprobadas, porcentaje de asistencia, incidentes disciplinarios, nivelaciones pendientes y tendencia de notas. Clasifica automáticamente en Riesgo Alto, Moderado o Bajo para que actúes a tiempo.',
    cardStyle: `background: ${gradientOverlay}, rgba(37,99,235,1)`
  },
  {
    icon: 'i-lucide-brain',
    title: 'Análisis Pedagógico por Estudiante',
    description: 'Genera narrativas completas y recomendaciones pedagógicas personalizadas por estudiante y por período usando Claude AI. Analiza calificaciones, logros, asistencia, nivelaciones e historial disciplinario en un informe accionable.',
    cardStyle: `background: ${gradientOverlay}, rgba(5,150,105,1)`
  },
  {
    icon: 'i-lucide-trending-up',
    title: 'Evolución y Seguimiento Histórico',
    description: 'Cada análisis queda guardado. Visualiza la evolución del riesgo entre períodos: delta de puntaje, cambio de nivel, comparativo anterior vs. actual. El docente ve si sus intervenciones están funcionando con evidencia real.',
    cardStyle: `background: ${gradientOverlay}, rgba(13,148,136,1)`
  },
  {
    icon: 'i-lucide-layout-dashboard',
    title: 'Resumen Ejecutivo Semanal IA',
    description: 'La IA genera automáticamente un resumen ejecutivo para coordinadores con la situación de riesgo de toda la institución: cuántos estudiantes en alerta, tendencias por grado, grupos que requieren atención prioritaria.',
    cardStyle: `background: ${gradientOverlay}, rgba(124,58,237,1)`
  }
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
  { category: 'IA', icon: 'i-lucide-shield-alert', title: 'Riesgo Estudiantil con IA', description: 'Detecta estudiantes vulnerables con un índice multivariable y accede a recomendaciones generadas por IA para cada caso.', badge: 'IA', pill: 'bg-amber-50', iconColor: 'text-amber-500' },
  { category: 'Académico', icon: 'i-lucide-graduation-cap', title: 'Gestión Académica', description: 'Configura años académicos, períodos con pesos porcentuales, grados, grupos y asignaturas desde preescolar hasta media.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' },
  { category: 'Académico', icon: 'i-lucide-users', title: 'Estudiantes y Docentes', description: 'Registro completo con historial académico, estados de matrícula, exportación SIMAT y asignación de acudientes.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' },
  { category: 'Académico', icon: 'i-lucide-file-check', title: 'Calificaciones Colombianas', description: 'Escala Superior/Alto/Básico/Bajo, promedios ponderados, planilla consolidada con ranking y observaciones por estudiante.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' },
  { category: 'Académico', icon: 'i-lucide-calendar-check', title: 'Control de Asistencia', description: 'Registro diario con estados Presente/Ausente/Tardanza/Excusa, alertas automáticas y reportes de ausentismo por período.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' },
  { category: 'Académico', icon: 'i-lucide-clipboard-list', title: 'Tareas y Entregas', description: 'Asigna trabajos al grupo con instrucciones y PDF adjunto. Los acudientes consultan el estado de entrega de su hijo en tiempo real.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' },
  { category: 'Reportes', icon: 'i-lucide-file-text', title: 'Boletines PDF', description: 'Generación individual y masiva de boletines con logo institucional, notas, promedio, puesto y observaciones del docente.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' },
  { category: 'Reportes', icon: 'i-lucide-file-badge', title: 'Constancias y Certificados', description: 'Generación automática de constancias de matrícula y de notas en PDF con datos y logo institucional.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' },
  { category: 'Reportes', icon: 'i-lucide-download', title: 'Exportación SIMAT', description: 'Exporta el registro estudiantil en el formato oficial requerido por el MEN para el Sistema Integrado de Matrícula.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' },
  { category: 'Normativa', icon: 'i-lucide-target', title: 'SIEE — Evaluación por Logros', description: 'Logros cognitivos, procedimentales y actitudinales con indicadores de desempeño. Importación CSV y copia entre períodos.', badge: null, pill: 'bg-amber-50', iconColor: 'text-amber-500' },
  { category: 'Normativa', icon: 'i-lucide-message-square', title: 'Convivencia Escolar', description: 'Registro de incidentes disciplinarios conforme a la Ley 1620/2013. Historial por estudiante vinculado al índice de riesgo.', badge: null, pill: 'bg-amber-50', iconColor: 'text-amber-500' },
  { category: 'Normativa', icon: 'i-lucide-refresh-cw', title: 'Nivelaciones y Recuperaciones', description: 'Asignación automática de actividades remediales a estudiantes con bajo desempeño. Calificación con actualización de nota original.', badge: null, pill: 'bg-amber-50', iconColor: 'text-amber-500' },
  { category: 'Comunicación', icon: 'i-lucide-home', title: 'Portal de Padres', description: 'Acceso seguro para acudientes: notas, asistencia, descarga de boletines y constancias. Disponible 24/7 desde cualquier dispositivo.', badge: null, pill: 'bg-emerald-50', iconColor: 'text-emerald-600' }
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

const includedItems = [
  { icon: 'i-lucide-zap', label: 'Onboarding guiado', desc: 'Lo dejamos listo en 48h' },
  { icon: 'i-lucide-users', label: 'Docentes ilimitados', desc: 'Sin costo por usuario' },
  { icon: 'i-lucide-refresh-cw', label: 'Actualizaciones gratis', desc: 'Siempre la última versión' },
  { icon: 'i-lucide-database', label: 'Sus datos son suyos', desc: 'Exportación en cualquier momento' }
]

const showDemo = ref(false)
</script>

<template>
  <div class="min-h-screen bg-white" style="font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;">
    <!-- ============ NAVBAR ============ -->
    <PublicNavbar @open-demo="showDemo = true" />

    <main>
      <!-- ============ HERO ============ -->
      <section class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-white to-cyan-50/40 pt-28 pb-16 px-4 sm:px-6 lg:px-8" aria-labelledby="hero-title">
        <div class="max-w-7xl mx-auto">
          <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <!-- LEFT: Text -->
            <div>
              <!-- Badge with pulse dot -->
              <div class="inline-flex items-center gap-2.5 px-4 py-1.5 mb-8 rounded-full border border-blue-200 bg-blue-50 text-blue-700 text-sm font-medium">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-500 opacity-75" />
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500" />
                </span>
                Plataforma en acceso anticipado — para instituciones pioneras
              </div>

              <!-- Headline -->
              <h1 id="hero-title" class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-[1.08] tracking-tight mb-6">
                Detecta el riesgo estudiantil
                <span class="block text-blue-600">
                  antes de que sea tarde.
                </span>
              </h1>

              <p class="text-lg text-slate-600 leading-relaxed mb-10 max-w-lg">
                Aula360 es el software de gestión académica que, además de centralizar notas, asistencia y reportes, usa IA para anticipar qué estudiantes necesitan apoyo y darte recomendaciones pedagógicas concretas antes de que sea demasiado tarde.
              </p>

              <div class="flex flex-col sm:flex-row items-start gap-4">
                <a
                  href="/login"
                  class="inline-flex items-center px-8 py-3.5 text-base font-semibold text-white bg-blue-600 hover:bg-blue-500 rounded-xl shadow-lg shadow-blue-600/25 hover:-translate-y-0.5 transition-all duration-200"
                >
                  Ingresar al Sistema
                  <UIcon name="i-lucide-arrow-right" class="size-5 ml-2" />
                </a>
                <a
                  href="#ia"
                  class="inline-flex items-center px-8 py-3.5 text-base font-medium text-slate-700 border border-slate-300 hover:bg-slate-50 hover:border-slate-400 rounded-xl transition-colors duration-200"
                >
                  Conocer la IA
                  <UIcon name="i-lucide-arrow-right" class="size-5 ml-2" />
                </a>
              </div>
            </div>

            <!-- RIGHT: Dashboard mock -->
            <div class="relative">
              <div class="rounded-2xl bg-white border border-slate-200 p-6 shadow-xl shadow-slate-200/60">
                <!-- Fake browser chrome -->
                <div class="flex items-center gap-2 mb-5 pb-4 border-b border-slate-100">
                  <div class="flex gap-1.5">
                    <div class="w-3 h-3 rounded-full bg-red-400" />
                    <div class="w-3 h-3 rounded-full bg-yellow-400" />
                    <div class="w-3 h-3 rounded-full bg-green-400" />
                  </div>
                  <div class="flex-1 h-5 rounded bg-slate-100 max-w-[200px] mx-auto" />
                </div>

                <!-- Dashboard header -->
                <div class="flex items-center justify-between mb-5">
                  <div class="flex items-center gap-2">
                    <UIcon name="i-lucide-brain" class="w-4 h-4 text-blue-600" />
                    <span class="text-sm font-semibold text-slate-900">Índice de Riesgo IA</span>
                  </div>
                  <span class="text-xs px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-200">
                    Período 2 · 2024
                  </span>
                </div>

                <!-- Risk summary chips -->
                <div class="grid grid-cols-3 gap-3 mb-5">
                  <div class="rounded-xl p-3 bg-red-50 border border-red-200 text-center">
                    <p class="text-2xl font-bold text-red-600">
                      12
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5">
                      Riesgo Alto
                    </p>
                  </div>
                  <div class="rounded-xl p-3 bg-amber-50 border border-amber-200 text-center">
                    <p class="text-2xl font-bold text-amber-600">
                      27
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5">
                      Moderado
                    </p>
                  </div>
                  <div class="rounded-xl p-3 bg-emerald-50 border border-emerald-200 text-center">
                    <p class="text-2xl font-bold text-emerald-600">
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
                      { name: 'Valentina García', group: 'Grupo 9-A', score: 78, bar: 'bg-red-500', scoreColor: 'text-red-600' },
                      { name: 'Sebastián Montoya', group: 'Grupo 8-B', score: 51, bar: 'bg-amber-500', scoreColor: 'text-amber-600' },
                      { name: 'Isabella Restrepo', group: 'Grupo 7-A', score: 22, bar: 'bg-emerald-500', scoreColor: 'text-emerald-600' }
                    ]"
                    :key="i"
                    class="flex items-center gap-3 p-2.5 rounded-xl bg-slate-50 border border-slate-100"
                  >
                    <div class="w-7 h-7 rounded-full bg-slate-200 shrink-0 flex items-center justify-center">
                      <span class="text-xs text-slate-600 font-semibold">{{ s.name[0] }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-xs font-medium text-slate-900 truncate">
                        {{ s.name }}
                      </p>
                      <p class="text-xs text-slate-500">
                        {{ s.group }}
                      </p>
                    </div>
                    <div class="flex items-center gap-2">
                      <div class="w-16 h-1.5 rounded-full bg-slate-200 overflow-hidden">
                        <div class="h-full rounded-full" :class="s.bar" :style="`width:${s.score}%`" />
                      </div>
                      <span class="text-xs font-mono font-semibold w-6 text-right" :class="s.scoreColor">{{ s.score }}</span>
                    </div>
                    <div class="flex items-center gap-1 text-xs px-2 py-1 rounded-lg bg-blue-50 text-blue-700 border border-blue-200 shrink-0 cursor-pointer hover:bg-blue-100 transition-colors">
                      <UIcon name="i-lucide-sparkles" class="w-3 h-3" />
                      IA
                    </div>
                  </div>
                </div>

                <!-- AI status bar -->
                <div class="mt-4 flex items-center gap-2.5 px-3 py-2.5 rounded-xl bg-blue-50 border border-blue-200">
                  <UIcon name="i-lucide-brain" class="w-3.5 h-3.5 text-blue-600 animate-pulse shrink-0" />
                  <span class="text-xs text-blue-700">Analizando patrones con Claude AI...</span>
                  <div class="flex gap-0.5 ml-auto ai-dots">
                    <span class="w-1 h-1 rounded-full bg-blue-500 ai-dot" style="animation-delay:0ms" />
                    <span class="w-1 h-1 rounded-full bg-blue-500 ai-dot" style="animation-delay:150ms" />
                    <span class="w-1 h-1 rounded-full bg-blue-500 ai-dot" style="animation-delay:300ms" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ============ SOCIAL PROOF STATS ============ -->
      <section class="bg-white border-y border-slate-200 py-10 px-4 sm:px-6 lg:px-8" aria-label="Estadísticas de la plataforma">
        <div class="max-w-5xl mx-auto">
          <!-- Testimonial trust anchor -->
          <figure class="text-center mb-8 pb-8 border-b border-slate-200">
            <blockquote class="text-slate-700 text-sm italic leading-relaxed max-w-xl mx-auto mb-3">
              "Pasamos de hojas de cálculo a tener todo centralizado. En 48 horas ya teníamos los boletines del primer período listos."
            </blockquote>
            <figcaption class="text-slate-500 text-xs">
              — Coordinadora Académica, Institución Educativa, Bogotá D.C.
            </figcaption>
          </figure>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-0 md:divide-x md:divide-slate-200">
            <div class="flex flex-col items-center text-center md:px-8">
              <p class="text-3xl font-extrabold text-slate-900 mb-1">
                13
              </p>
              <p class="text-sm text-slate-500">
                Módulos integrados
              </p>
            </div>
            <div class="flex flex-col items-center text-center md:px-8">
              <p class="text-3xl font-extrabold text-blue-600 mb-1">
                100%
              </p>
              <p class="text-sm text-slate-500">
                Normativa MEN / SIEE
              </p>
            </div>
            <div class="flex flex-col items-center text-center md:px-8">
              <p class="text-3xl font-extrabold text-slate-900 mb-1">
                48h
              </p>
              <p class="text-sm text-slate-500">
                Onboarding guiado
              </p>
            </div>
            <div class="flex flex-col items-center text-center md:px-8">
              <p class="text-3xl font-extrabold text-emerald-600 mb-1">
                24/7
              </p>
              <p class="text-sm text-slate-500">
                Portal para padres
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- ============ WHAT IS AULA360 ============ -->
      <section class="bg-white py-16 px-4 sm:px-6 lg:px-8" aria-labelledby="que-es-title">
        <div class="max-w-3xl mx-auto text-center">
          <p class="text-xs font-semibold uppercase tracking-widest text-blue-600 mb-4">
            ¿Qué es Aula360?
          </p>
          <h2 id="que-es-title" class="text-2xl sm:text-3xl font-bold text-slate-900 leading-snug mb-5">
            El sistema de gestión académica que reemplaza las hojas de cálculo en los colegios colombianos.
          </h2>
          <p class="text-base sm:text-lg text-slate-500 leading-relaxed max-w-2xl mx-auto mb-8">
            Aula360 es un software SaaS en la nube diseñado para instituciones educativas de preescolar a media: centraliza matrículas, notas, asistencia, convivencia y comunicación con padres en un solo lugar, cumpliendo con el SIEE y las normas del MEN — sin instalar nada, sin Excel, sin papeles.
          </p>
          <a
            href="/como-funciona"
            class="inline-flex items-center px-6 py-3 text-sm font-semibold text-blue-600 border border-blue-200 bg-blue-50 hover:bg-blue-100 hover:border-blue-300 rounded-xl transition-colors duration-200"
          >
            Ver cómo funciona en detalle
            <UIcon name="i-lucide-arrow-right" class="size-4 ml-2" />
          </a>
        </div>
      </section>

      <!-- ============ AI FEATURES ============ -->
      <section id="ia" class="bg-slate-50 py-24 px-4 sm:px-6 lg:px-8 border-b border-slate-200" aria-labelledby="ia-title">
        <div class="max-w-7xl mx-auto">
          <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-xs font-medium tracking-wide mb-4">
              <UIcon name="i-lucide-sparkles" class="w-3.5 h-3.5" />
              Motor de Inteligencia Artificial
            </div>
            <h2 id="ia-title" class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
              IA que realmente <span class="text-blue-600">impacta el aprendizaje</span>
            </h2>
            <p class="text-slate-600 text-lg max-w-2xl mx-auto">
              No es automatización básica. Es análisis profundo impulsado por IA que convierte datos académicos en acciones concretas para cada estudiante.
            </p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <article
              v-for="feature in aiFeatures"
              :key="feature.title"
              class="group rounded-3xl p-10 overflow-hidden hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 cursor-default"
              :style="feature.cardStyle"
            >
              <!-- Icon + Title inline -->
              <div class="flex items-center gap-3 mb-5">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-white/20 shrink-0">
                  <UIcon :name="feature.icon" class="size-5 text-white" />
                </div>
                <h3 class="text-lg font-bold text-white leading-snug">
                  {{ feature.title }}
                </h3>
              </div>
              <p class="text-sm text-white/80 leading-relaxed pl-14">
                {{ feature.description }}
              </p>
            </article>
          </div>
        </div>
      </section>

      <!-- ============ MODULES ============ -->
      <section id="modulos" class="py-24 px-4 sm:px-6 lg:px-8 bg-white border-b border-slate-200" aria-labelledby="modulos-title">
        <div class="max-w-5xl mx-auto">
          <div class="text-center mb-12">
            <h2 id="modulos-title" class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
              Todo lo que tu institución necesita,<br class="hidden sm:block"> en un solo lugar
            </h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
              13 módulos diseñados para el contexto educativo colombiano. Explora por categoría.
            </p>
          </div>

          <!-- Category tab strip -->
          <div class="flex flex-wrap gap-2 justify-center mb-10">
            <button
              v-for="cat in moduleCategories"
              :key="cat.key"
              class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-medium border transition-all duration-200 cursor-pointer"
              :class="activeCategoryKey === cat.key
                ? 'bg-blue-600 border-blue-600 text-white shadow-sm'
                : 'bg-white border-slate-200 text-slate-600 hover:text-slate-900 hover:border-slate-300 hover:bg-slate-50'"
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
                class="group flex items-start gap-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-indigo-300 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200"
              >
                <div class="p-2.5 rounded-xl shrink-0 mt-0.5" :class="mod.pill">
                  <UIcon :name="mod.icon" class="size-5" :class="mod.iconColor" />
                </div>
                <div class="min-w-0">
                  <div class="flex items-center gap-2 mb-1">
                    <h3 class="text-sm font-semibold text-slate-900 group-hover:text-blue-600 transition-colors duration-200">
                      {{ mod.title }}
                    </h3>
                    <span
                      v-if="mod.badge"
                      class="text-xs px-1.5 py-0.5 rounded-md bg-blue-100 text-blue-700 border border-blue-200 font-semibold"
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
          <p class="text-center text-xs text-slate-400 mt-8">
            Mostrando {{ filteredModules.length }} de 13 módulos
          </p>
        </div>
      </section>

      <!-- ============ BENEFITS ============ -->
      <section id="beneficios" class="py-24 px-4 sm:px-6 lg:px-8 bg-slate-50 border-b border-slate-200" aria-labelledby="beneficios-title">
        <div class="max-w-7xl mx-auto">
          <div class="text-center mb-16">
            <h2 id="beneficios-title" class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
              Diseñado para cada actor de tu institución
            </h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
              Cada rol tiene exactamente lo que necesita, sin complejidad innecesaria.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Institution -->
            <article class="rounded-2xl p-10 bg-white border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300">
              <div class="flex items-center gap-3 mb-8">
                <div class="p-2.5 bg-blue-50 rounded-xl shrink-0">
                  <UIcon name="i-lucide-building-2" class="size-5 text-blue-500" />
                </div>
                <h3 class="text-lg font-bold text-slate-900">
                  Para Directivos y Coordinadores
                </h3>
              </div>
              <ul class="space-y-4">
                <li v-for="b in benefitsInstitution" :key="b" class="flex items-start gap-3">
                  <UIcon name="i-lucide-check-circle" class="size-4 shrink-0 mt-0.5 text-blue-400" />
                  <span class="text-sm text-slate-600 leading-relaxed">{{ b }}</span>
                </li>
              </ul>
            </article>

            <!-- Teachers -->
            <article class="rounded-2xl p-10 bg-white border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300">
              <div class="flex items-center gap-3 mb-8">
                <div class="p-2.5 bg-emerald-50 rounded-xl shrink-0">
                  <UIcon name="i-lucide-book-open" class="size-5 text-emerald-500" />
                </div>
                <h3 class="text-lg font-bold text-slate-900">
                  Para Docentes
                </h3>
              </div>
              <ul class="space-y-4">
                <li v-for="b in benefitsTeachers" :key="b" class="flex items-start gap-3">
                  <UIcon name="i-lucide-check-circle" class="size-4 shrink-0 mt-0.5 text-emerald-400" />
                  <span class="text-sm text-slate-600 leading-relaxed">{{ b }}</span>
                </li>
              </ul>
            </article>

            <!-- Parents -->
            <article class="rounded-2xl p-10 bg-white border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300">
              <div class="flex items-center gap-3 mb-8">
                <div class="p-2.5 bg-violet-50 rounded-xl shrink-0">
                  <UIcon name="i-lucide-heart" class="size-5 text-violet-500" />
                </div>
                <h3 class="text-lg font-bold text-slate-900">
                  Para Padres de Familia
                </h3>
              </div>
              <ul class="space-y-4">
                <li v-for="b in benefitsParents" :key="b" class="flex items-start gap-3">
                  <UIcon name="i-lucide-check-circle" class="size-4 shrink-0 mt-0.5 text-violet-400" />
                  <span class="text-sm text-slate-600 leading-relaxed">{{ b }}</span>
                </li>
              </ul>
            </article>
          </div>
        </div>
      </section>

      <!-- ============ INCLUDED BAND ============ -->
      <section class="px-4 sm:px-6 lg:px-8 py-16 border-t border-slate-200 bg-white" aria-labelledby="incluido-title">
        <div class="max-w-7xl mx-auto">
          <h2 id="incluido-title" class="text-center text-slate-500 text-sm font-medium mb-2 uppercase tracking-wider">
            Incluido en todos los planes
          </h2>
          <p class="text-center mb-10">
            <a href="/pricing" class="text-blue-600 hover:text-blue-700 text-sm font-semibold transition-colors">
              Desde $490.000/mes · Ver precios →
            </a>
          </p>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div
              v-for="item in includedItems"
              :key="item.label"
              class="flex flex-col items-center text-center gap-3"
            >
              <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center">
                <UIcon :name="item.icon" class="w-5 h-5 text-blue-600" />
              </div>
              <div>
                <p class="text-slate-900 text-sm font-medium">
                  {{ item.label }}
                </p>
                <p class="text-slate-500 text-xs mt-0.5">
                  {{ item.desc }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ============ CTA ============ -->
      <section id="contacto" class="py-16 px-4 sm:px-6 lg:px-8 bg-white" aria-labelledby="cta-title">
        <div class="max-w-5xl mx-auto">
          <div class="rounded-3xl px-8 py-16 sm:px-16 sm:py-20 text-center bg-gradient-to-br from-teal-100 via-cyan-50 to-emerald-100">
            <h2 id="cta-title" class="text-3xl sm:text-5xl font-extrabold text-slate-900 mb-6 leading-tight tracking-tight">
              Tu institución merece tomar<br class="hidden sm:block"> decisiones con datos reales.
            </h2>
            <p class="text-lg text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
              Aula360 está lista para tu institución. Sé parte de las primeras instituciones colombianas en usar IA para detectar riesgo estudiantil, reducir carga administrativa y mantener a los padres informados.
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

    <!-- ============ FOOTER ============ -->
    <footer class="bg-slate-50 border-t border-slate-200 py-12 px-4 sm:px-6 lg:px-8" role="contentinfo">
      <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
        <a href="/" aria-label="Aula360 - Inicio">
          <span class="text-xl font-bold text-slate-900">Aula<span class="text-blue-600">360</span></span>
        </a>
        <p class="text-slate-500 text-center text-sm">
          Gestión Académica con IA para instituciones educativas colombianas.
        </p>
        <div class="flex flex-col items-center md:items-end gap-1">
          <p class="text-slate-500 text-sm">
            © {{ new Date().getFullYear() }} Aula360. Todos los derechos reservados.
          </p>
          <p class="text-slate-400 text-xs">
            Desarrollado por
            <a
              href="https://mauriciomontoya.co"
              target="_blank"
              rel="noopener noreferrer"
              class="text-slate-500 hover:text-slate-700 transition-colors"
            >Mauricio Montoya</a>
          </p>
        </div>
      </div>
    </footer>

    <DemoFormModal v-model="showDemo" />
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

/* Mobile menu transition */
.mobile-menu-enter-active,
.mobile-menu-leave-active {
  transition: opacity 0.15s ease, transform 0.15s ease;
}
.mobile-menu-enter-from,
.mobile-menu-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}

/* AI typing dots */
.ai-dot {
  animation: ai-bounce 1s ease-in-out infinite;
}
@keyframes ai-bounce {
  0%, 80%, 100% { transform: translateY(0); opacity: 0.6; }
  40% { transform: translateY(-4px); opacity: 1; }
}

/* Respect user's motion preferences */
@media (prefers-reduced-motion: reduce) {
  .mod-tab-enter-active,
  .mod-tab-leave-active,
  .mobile-menu-enter-active,
  .mobile-menu-leave-active {
    transition: opacity 0.1s ease;
  }
  .mod-tab-enter-from,
  .mod-tab-leave-to {
    transform: none;
  }
  .ai-dot {
    animation: none;
    opacity: 0.7;
  }
  .animate-ping {
    animation: none;
  }
  .animate-pulse {
    animation: none;
  }
}
</style>
