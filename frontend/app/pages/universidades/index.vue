<script setup lang="ts">
definePageMeta({
  layout: false
})

useSeoMeta({
  title: 'Aula360 para Universidades — Gestión Académica con IA para Educación Superior en Colombia',
  description: 'Software de gestión académica para universidades, tecnológicas e IES colombianas. Programas por créditos, matrículas por materia, historial académico digital y riesgo de deserción con IA. Setup en 48h.',
  keywords: 'software gestión académica universidad colombia, sistema académico universitario, plataforma educación superior colombia, historial academico universitario, certificado notas pdf universidad, matrícula por créditos colombia, riesgo deserción universitaria IA, software IES colombia, gestión programas académicos universidad, administración académica educación superior, SNIES colombia universidad, software tecnológica colombia, Aula360 universidades',
  author: 'Aula360',
  robots: 'index, follow',
  ogTitle: 'Aula360 para Universidades — IA que reduce la deserción estudiantil',
  ogDescription: 'Gestión de programas, créditos y matrículas para IES colombianas. IA que detecta el riesgo de deserción por estudiante. Historial académico digital. Setup en 48 horas.',
  ogType: 'website',
  ogImage: 'https://aula360.com/og-image.png',
  ogImageWidth: '1200',
  ogImageHeight: '630',
  ogImageAlt: 'Aula360 para Universidades — Gestión académica con IA para educación superior colombiana',
  ogUrl: 'https://aula360.com/universidades',
  ogLocale: 'es_CO',
  ogLocaleAlternate: ['es_ES', 'es_419'],
  ogSiteName: 'Aula360',
  twitterCard: 'summary_large_image',
  twitterSite: '@aula360co',
  twitterCreator: '@aula360co',
  twitterTitle: 'Aula360 para Universidades — IA que combate la deserción estudiantil',
  twitterDescription: 'Programas, créditos, matrículas y riesgo de deserción con IA para IES colombianas. Sin Excel, sin papeles.',
  twitterImage: 'https://aula360.com/og-image.png'
})

useHead({
  htmlAttrs: { lang: 'es-CO' },
  meta: [
    { name: 'viewport', content: 'width=device-width, initial-scale=1' },
    { name: 'theme-color', content: '#7C3AED' },
    { name: 'color-scheme', content: 'light' }
  ],
  link: [
    { rel: 'preconnect', href: 'https://fonts.googleapis.com' },
    { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: '' },
    { rel: 'stylesheet', href: 'https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700;800;900&display=swap' },
    { rel: 'canonical', href: 'https://aula360.com/universidades' },
    { rel: 'sitemap', type: 'application/xml', href: 'https://aula360.com/sitemap.xml' }
  ],
  script: [
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'SoftwareApplication',
        'name': 'Aula360 para Educación Superior',
        'url': 'https://aula360.com/universidades',
        'applicationCategory': 'EducationalApplication',
        'operatingSystem': 'Web',
        'description': 'Sistema de gestión académica con IA para universidades, tecnológicas e instituciones de educación superior colombianas. Gestión de programas por créditos, matrículas por materia, historial académico digital, riesgo de deserción con IA.',
        'priceRange': 'Consultar',
        'provider': {
          '@type': 'Organization',
          'name': 'Aula360',
          'url': 'https://aula360.com'
        },
        'inLanguage': 'es-CO',
        'featureList': [
          'Gestión de programas académicos con créditos',
          'Matrícula por materia con control de semestres',
          'Índice de Riesgo de Deserción con IA',
          'Análisis pedagógico por estudiante con IA',
          'Historial académico y certificados de notas en PDF',
          'Certificados de notas en PDF',
          'Control de asistencia por materia',
          'Calificaciones por cortes con créditos ponderados',
          'Resumen ejecutivo semanal para Rectoría con IA'
        ]
      })
    },
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'WebPage',
        'name': 'Aula360 para Universidades',
        'url': 'https://aula360.com/universidades',
        'description': 'Plataforma de gestión académica con IA para instituciones de educación superior colombianas.',
        'inLanguage': 'es-CO',
        'isPartOf': { '@type': 'WebSite', 'url': 'https://aula360.com', 'name': 'Aula360' }
      })
    },
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'BreadcrumbList',
        'itemListElement': [
          { '@type': 'ListItem', 'position': 1, 'name': 'Inicio', 'item': 'https://aula360.com' },
          { '@type': 'ListItem', 'position': 2, 'name': 'Universidades', 'item': 'https://aula360.com/universidades' }
        ]
      })
    }
  ]
})

const gradientOverlay = 'linear-gradient(192deg, rgba(255,255,255,0.00) 40%, rgba(255,255,255,0.18) 100%), linear-gradient(0deg, rgba(0,0,0,0.08) 0%, rgba(0,0,0,0.08) 100%)'

const aiFeatures = [
  {
    icon: 'i-lucide-shield-alert',
    title: 'Índice de Riesgo de Deserción',
    description: 'Colombia registra una deserción universitaria cercana al 45%. Aula360 calcula un índice 0–100 por estudiante combinando materias reprobadas, créditos perdidos, asistencia, historial semestral y tendencia de notas. Clasifica en Riesgo Alto, Moderado o Bajo para que actúes antes de que sea tarde.',
    cardStyle: `background: ${gradientOverlay}, rgba(109,40,217,1)`
  },
  {
    icon: 'i-lucide-brain',
    title: 'Análisis Pedagógico por Estudiante',
    description: 'Genera narrativas completas con recomendaciones académicas personalizadas por estudiante y semestre usando IA. Analiza calificaciones por corte, créditos acumulados, carga académica, asistencia e historial de reprobaciones en un informe accionable para el docente y la dirección.',
    cardStyle: `background: ${gradientOverlay}, rgba(5,150,105,1)`
  },
  {
    icon: 'i-lucide-trending-up',
    title: 'Evolución Semestral e Historial',
    description: 'Cada análisis queda registrado. Visualiza la evolución del riesgo entre semestres: delta de puntaje, cambio de nivel, materias recuperadas vs. reprobadas acumuladas. Evidencia real de si las intervenciones académicas están funcionando, semestre a semestre.',
    cardStyle: `background: ${gradientOverlay}, rgba(13,148,136,1)`
  },
  {
    icon: 'i-lucide-layout-dashboard',
    title: 'Resumen Ejecutivo para Rectoría',
    description: 'La IA genera automáticamente un resumen ejecutivo semanal con la situación académica de toda la institución: cuántos estudiantes en riesgo de deserción, tendencias por programa, semestres críticos y grupos que requieren intervención prioritaria — directo al escritorio de la Vicerrectoría Académica.',
    cardStyle: `background: ${gradientOverlay}, rgba(124,58,237,1)`
  }
]

const moduleCategories = [
  { key: 'Programas', label: 'Programas', icon: 'i-lucide-graduation-cap' },
  { key: 'Matrículas', label: 'Matrículas', icon: 'i-lucide-clipboard-list' },
  { key: 'Calificaciones', label: 'Calificaciones', icon: 'i-lucide-file-check' },
  { key: 'Documentos', label: 'Documentos', icon: 'i-lucide-file-text' },
  { key: 'IA', label: 'Inteligencia Artificial', icon: 'i-lucide-sparkles' }
]

const activeModuleCategory = ref('Programas')
const activeCategoryKey = computed(() => activeModuleCategory.value)
const setModuleCategory = (key: string) => {
  activeModuleCategory.value = key
}

const modules = [
  { category: 'IA', icon: 'i-lucide-shield-alert', title: 'Riesgo de Deserción con IA', description: 'Detecta estudiantes en riesgo de abandonar su programa con un índice multivariable. Recomendaciones pedagógicas generadas por IA para cada caso.', badge: 'IA', pill: 'bg-violet-50', iconColor: 'text-violet-600' },
  { category: 'IA', icon: 'i-lucide-brain', title: 'Análisis por Estudiante con IA', description: 'Informe académico personalizado por estudiante y semestre: calificaciones, créditos, asistencia, nivelaciones y recomendaciones de intervención generadas por IA.', badge: 'IA', pill: 'bg-violet-50', iconColor: 'text-violet-600' },
  { category: 'IA', icon: 'i-lucide-layout-dashboard', title: 'Resumen Ejecutivo IA', description: 'Resumen automático semanal para Rectoría y Vicerrectoría Académica con estado de riesgo por programa, semestre y grupos críticos.', badge: 'IA', pill: 'bg-violet-50', iconColor: 'text-violet-600' },
  { category: 'Programas', icon: 'i-lucide-graduation-cap', title: 'Gestión de Programas Académicos', description: 'Configura programas de pregrado, tecnológico o técnico con su estructura de semestres, pensum y créditos por materia desde un solo panel.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' },
  { category: 'Programas', icon: 'i-lucide-users', title: 'Estudiantes y Docentes', description: 'Registro completo con código de matrícula, programa, semestre activo, historial académico y estado. Docentes ilimitados sin costo adicional.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' },
  { category: 'Programas', icon: 'i-lucide-calendar-check', title: 'Control de Asistencia por Materia', description: 'Registro de asistencia diaria por materia con estados Presente/Ausente/Tardanza/Excusa. Alertas automáticas y reportes por semestre.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' },
  { category: 'Matrículas', icon: 'i-lucide-clipboard-list', title: 'Matrícula por Materia y Semestre', description: 'Los estudiantes se matriculan materia por materia con control de semestre, créditos inscritos y estado: matriculado, retirado, aprobado o reprobado.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' },
  { category: 'Matrículas', icon: 'i-lucide-refresh-cw', title: 'Re-matrícula y Habilitaciones', description: 'Gestiona la re-matrícula de materias reprobadas con histórico de intentos. Control de créditos pendientes, acumulados y disponibles por estudiante.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' },
  { category: 'Calificaciones', icon: 'i-lucide-file-check', title: 'Calificaciones con Créditos', description: 'Escala numérica por cortes con pesos porcentuales configurables. Calificación final ponderada, promedio acumulado y estado por materia: aprobado o reprobado.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' },
  { category: 'Calificaciones', icon: 'i-lucide-trending-up', title: 'Historial Académico Acumulado', description: 'Visualiza el historial completo de cada estudiante: créditos aprobados, reprobados, promedio ponderado acumulado y evolución por semestre.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' },
  { category: 'Documentos', icon: 'i-lucide-file-text', title: 'Historial Académico en PDF', description: 'Generación individual del historial con logo institucional, materias cursadas, créditos, notas por corte y promedio acumulado. Descarga instantánea.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' },
  { category: 'Documentos', icon: 'i-lucide-file-badge', title: 'Certificados de Notas y Matrícula', description: 'Certificados institucionales PDF con datos del estudiante, programa, semestre activo, notas o estado de matrícula. Logo y firma digital integrados.', badge: null, pill: 'bg-blue-50', iconColor: 'text-blue-600' }
]

const filteredModules = computed(() =>
  modules.filter(m => m.category === activeModuleCategory.value)
)

const benefitsRectoria = [
  'Dashboard ejecutivo en tiempo real con riesgo de deserción por programa y semestre',
  'Resumen semanal con IA: cuántos estudiantes en alerta, tendencias y grupos críticos',
  'Reportes de retención estudiantil para toma de decisiones estratégicas',
  'Gestión de múltiples programas académicos desde un solo panel institucional',
  'Historial académico y certificados PDF con logo institucional en segundos',
  'Reducción de hasta el 70% en carga administrativa de registro y secretaría'
]

const benefitsDocentes = [
  'Registro de notas por corte rápido desde cualquier dispositivo, sin papeles',
  'Índice de riesgo visible por estudiante con factores desglosados por materia',
  'Análisis pedagógico con IA que sugiere intervenciones concretas para cada caso',
  'Control de asistencia digital con alertas automáticas por materia',
  'Historial completo por estudiante: notas, intentos previos y evolución semestral',
  'Planilla consolidada de notas con exportación inmediata a PDF'
]

const benefitsEstudiantes = [
  'Acceso 24/7 a sus calificaciones por corte y materia desde el celular',
  'Descarga inmediata de su historial de notas en PDF sin ir a secretaría',
  'Certificados de notas y de matrícula disponibles en línea en todo momento',
  'Consulta de historial de asistencia por materia y semestre',
  'Información siempre actualizada y segura, sin instalar aplicaciones adicionales'
]

const includedItems = [
  { icon: 'i-lucide-zap', label: 'Onboarding guiado', desc: 'Configurado en 48h' },
  { icon: 'i-lucide-users', label: 'Docentes ilimitados', desc: 'Sin costo por usuario' },
  { icon: 'i-lucide-refresh-cw', label: 'Actualizaciones gratis', desc: 'Siempre la última versión' },
  { icon: 'i-lucide-database', label: 'Sus datos son suyos', desc: 'Exportación en cualquier momento' }
]

const showDemo = ref(false)
</script>

<template>
  <div class="min-h-screen bg-white" style="font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;">
    <!-- ============ NAVBAR ============ -->
    <PublicNavbarUniversidades @open-demo="showDemo = true" />

    <main>
      <!-- ============ HERO ============ -->
      <section class="relative overflow-hidden bg-gradient-to-br from-slate-50 via-white to-violet-50/40 pt-28 pb-16 px-4 sm:px-6 lg:px-8" aria-labelledby="hero-title">
        <div class="max-w-7xl mx-auto">
          <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <!-- LEFT: Text -->
            <div>
              <!-- Badge -->
              <div class="inline-flex items-center gap-2.5 px-4 py-1.5 mb-8 rounded-full border border-violet-200 bg-violet-50 text-violet-700 text-sm font-medium">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-violet-500 opacity-75" />
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-violet-500" />
                </span>
                Para universidades, tecnológicas e IES colombianas
              </div>

              <!-- Headline -->
              <h1 id="hero-title" class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-[1.08] tracking-tight mb-6">
                Reduce la deserción
                <span class="block text-violet-600">
                  antes de perder al estudiante.
                </span>
              </h1>

              <p class="text-lg text-slate-600 leading-relaxed mb-10 max-w-lg">
                Aula360 es la plataforma académica que centraliza programas, créditos, matrículas por materia y el historial de notas de cada estudiante — y usa IA para anticipar quién está en riesgo de abandonar su carrera antes de que sea demasiado tarde.
              </p>

              <div class="flex flex-col sm:flex-row items-start gap-4">
                <a
                  href="/login"
                  class="inline-flex items-center px-8 py-3.5 text-base font-semibold text-white bg-violet-600 hover:bg-violet-500 rounded-xl shadow-lg shadow-violet-600/25 hover:-translate-y-0.5 transition-all duration-200"
                >
                  Ingresar al Sistema
                  <UIcon name="i-lucide-arrow-right" class="size-5 ml-2" />
                </a>
                <a
                  href="#ia-superior"
                  class="inline-flex items-center px-8 py-3.5 text-base font-medium text-slate-700 border border-slate-300 hover:bg-slate-50 hover:border-slate-400 rounded-xl transition-colors duration-200"
                >
                  Ver la IA de deserción
                  <UIcon name="i-lucide-arrow-right" class="size-5 ml-2" />
                </a>
              </div>
            </div>

            <!-- RIGHT: Dashboard mock —- Riesgo de Deserción view -->
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

                <!-- Program header -->
                <div class="flex items-center justify-between mb-5">
                  <div class="flex items-center gap-2">
                    <UIcon name="i-lucide-graduation-cap" class="w-4 h-4 text-violet-600" />
                    <span class="text-sm font-semibold text-slate-900">Riesgo de Deserción · IA</span>
                  </div>
                  <span class="text-xs px-2.5 py-1 rounded-full bg-violet-50 text-violet-700 border border-violet-200">
                    Semestre I · 2025
                  </span>
                </div>

                <!-- Program summary chips -->
                <div class="grid grid-cols-3 gap-3 mb-5">
                  <div class="rounded-xl p-3 bg-red-50 border border-red-200 text-center">
                    <p class="text-2xl font-bold text-red-600">
                      9
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5">
                      Riesgo Alto
                    </p>
                  </div>
                  <div class="rounded-xl p-3 bg-amber-50 border border-amber-200 text-center">
                    <p class="text-2xl font-bold text-amber-600">
                      21
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5">
                      Moderado
                    </p>
                  </div>
                  <div class="rounded-xl p-3 bg-emerald-50 border border-emerald-200 text-center">
                    <p class="text-2xl font-bold text-emerald-600">
                      143
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5">
                      Sin Riesgo
                    </p>
                  </div>
                </div>

                <!-- Student rows — university names -->
                <div class="space-y-2">
                  <div
                    v-for="(s, i) in [
                      { name: 'Diego Alejandro Torres', prog: 'Adm. de Empresas · Sem I', score: 81, bar: 'bg-red-500', scoreColor: 'text-red-600' },
                      { name: 'Laura Cristina Peña', prog: 'Contaduría Pública · Sem III', score: 47, bar: 'bg-amber-500', scoreColor: 'text-amber-600' },
                      { name: 'Valentina Ríos Montoya', prog: 'Tecnología en Sistemas · Sem I', score: 18, bar: 'bg-emerald-500', scoreColor: 'text-emerald-600' }
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
                        {{ s.prog }}
                      </p>
                    </div>
                    <div class="flex items-center gap-2">
                      <div class="w-16 h-1.5 rounded-full bg-slate-200 overflow-hidden">
                        <div class="h-full rounded-full" :class="s.bar" :style="`width:${s.score}%`" />
                      </div>
                      <span class="text-xs font-mono font-semibold w-6 text-right" :class="s.scoreColor">{{ s.score }}</span>
                    </div>
                    <div class="flex items-center gap-1 text-xs px-2 py-1 rounded-lg bg-violet-50 text-violet-700 border border-violet-200 shrink-0 cursor-pointer hover:bg-violet-100 transition-colors">
                      <UIcon name="i-lucide-sparkles" class="w-3 h-3" />
                      IA
                    </div>
                  </div>
                </div>

                <!-- AI status bar -->
                <div class="mt-4 flex items-center gap-2.5 px-3 py-2.5 rounded-xl bg-violet-50 border border-violet-200">
                  <UIcon name="i-lucide-brain" class="w-3.5 h-3.5 text-violet-600 animate-pulse shrink-0" />
                  <span class="text-xs text-violet-700">Analizando riesgo de deserción con IA...</span>
                  <div class="flex gap-0.5 ml-auto ai-dots">
                    <span class="w-1 h-1 rounded-full bg-violet-500 ai-dot" style="animation-delay:0ms" />
                    <span class="w-1 h-1 rounded-full bg-violet-500 ai-dot" style="animation-delay:150ms" />
                    <span class="w-1 h-1 rounded-full bg-violet-500 ai-dot" style="animation-delay:300ms" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ============ SOCIAL PROOF STATS ============ -->
      <section class="bg-white border-y border-slate-200 py-10 px-4 sm:px-6 lg:px-8" aria-label="Estadísticas para educación superior">
        <div class="max-w-5xl mx-auto">
          <figure class="text-center mb-8 pb-8 border-b border-slate-200">
            <blockquote class="text-slate-700 text-sm italic leading-relaxed max-w-xl mx-auto mb-3">
              "Pasamos del Excel a tener el historial de notas del semestre listo en minutos. En 48 horas ya teníamos todos los programas configurados y los docentes registrando notas."
            </blockquote>
            <figcaption class="text-slate-500 text-xs">
              — Director Académico, Institución Universitaria, Colombia
            </figcaption>
          </figure>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-0 md:divide-x md:divide-slate-200">
            <div class="flex flex-col items-center text-center md:px-8">
              <p class="text-3xl font-extrabold text-slate-900 mb-1">
                ~45%
              </p>
              <p class="text-sm text-slate-500">
                Deserción universitaria en Colombia
              </p>
            </div>
            <div class="flex flex-col items-center text-center md:px-8">
              <p class="text-3xl font-extrabold text-violet-600 mb-1">
                100%
              </p>
              <p class="text-sm text-slate-500">
                Digital · Sin papel ni Excel
              </p>
            </div>
            <div class="flex flex-col items-center text-center md:px-8">
              <p class="text-3xl font-extrabold text-slate-900 mb-1">
                48h
              </p>
              <p class="text-sm text-slate-500">
                Onboarding con sus programas
              </p>
            </div>
            <div class="flex flex-col items-center text-center md:px-8">
              <p class="text-3xl font-extrabold text-emerald-600 mb-1">
                24/7
              </p>
              <p class="text-sm text-slate-500">
                Historial de notas para estudiantes
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- ============ WHAT IS AULA360 FOR HIGHER ED ============ -->
      <section class="bg-white py-16 px-4 sm:px-6 lg:px-8" aria-labelledby="que-es-uni-title">
        <div class="max-w-3xl mx-auto text-center">
          <p class="text-xs font-semibold uppercase tracking-widest text-violet-600 mb-4">
            ¿Para qué tipo de institución?
          </p>
          <h2 id="que-es-uni-title" class="text-2xl sm:text-3xl font-bold text-slate-900 leading-snug mb-5">
            El sistema académico que reemplaza el Excel en universidades, tecnológicas y técnicas colombianas.
          </h2>
          <p class="text-base sm:text-lg text-slate-500 leading-relaxed max-w-2xl mx-auto mb-8">
            Aula360 se adapta a la educación superior: gestiona programas con créditos, matrículas por materia, semestres académicos, historial de notas y certificados — en la nube, sin instalar nada. Diseñado para universidades, instituciones universitarias, tecnológicas y técnicas profesionales reconocidas por el MEN.
          </p>
          <div class="flex flex-wrap gap-3 justify-center">
            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-violet-50 border border-violet-200 text-violet-700 text-sm font-medium">
              <UIcon name="i-lucide-building-2" class="w-3.5 h-3.5" />
              Universidades
            </span>
            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-sm font-medium">
              <UIcon name="i-lucide-building-2" class="w-3.5 h-3.5" />
              Instituciones Universitarias
            </span>
            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium">
              <UIcon name="i-lucide-building-2" class="w-3.5 h-3.5" />
              Tecnológicas
            </span>
            <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-sm font-medium">
              <UIcon name="i-lucide-building-2" class="w-3.5 h-3.5" />
              Técnicas Profesionales
            </span>
          </div>
        </div>
      </section>

      <!-- ============ AI FEATURES ============ -->
      <section id="ia-superior" class="bg-slate-50 py-24 px-4 sm:px-6 lg:px-8 border-b border-slate-200" aria-labelledby="ia-superior-title">
        <div class="max-w-7xl mx-auto">
          <div class="text-center mb-16">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-violet-50 border border-violet-200 text-violet-700 text-xs font-medium tracking-wide mb-4">
              <UIcon name="i-lucide-sparkles" class="w-3.5 h-3.5" />
              Motor de Inteligencia Artificial para Educación Superior
            </div>
            <h2 id="ia-superior-title" class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
              IA que <span class="text-violet-600">combate la deserción</span> con datos reales
            </h2>
            <p class="text-slate-600 text-lg max-w-2xl mx-auto">
              No es automatización básica. Es análisis académico profundo con IA que convierte datos de matrícula, notas y asistencia en alertas tempranas y recomendaciones concretas.
            </p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <article
              v-for="feature in aiFeatures"
              :key="feature.title"
              class="group rounded-3xl p-10 overflow-hidden hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 cursor-default"
              :style="feature.cardStyle"
            >
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
      <section id="modulos-uni" class="py-24 px-4 sm:px-6 lg:px-8 bg-white border-b border-slate-200" aria-labelledby="modulos-uni-title">
        <div class="max-w-5xl mx-auto">
          <div class="text-center mb-12">
            <h2 id="modulos-uni-title" class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
              Todo lo que tu IES necesita,<br class="hidden sm:block"> en un solo sistema
            </h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
              12 módulos diseñados para el contexto de educación superior colombiana. Explora por categoría.
            </p>
          </div>

          <!-- Category tab strip -->
          <div class="flex flex-wrap gap-2 justify-center mb-10">
            <button
              v-for="cat in moduleCategories"
              :key="cat.key"
              class="inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-medium border transition-all duration-200 cursor-pointer"
              :class="activeCategoryKey === cat.key
                ? 'bg-violet-600 border-violet-600 text-white shadow-sm'
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
                class="group flex items-start gap-4 bg-white rounded-2xl p-5 border border-slate-200 shadow-sm hover:border-violet-300 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200"
              >
                <div class="p-2.5 rounded-xl shrink-0 mt-0.5" :class="mod.pill">
                  <UIcon :name="mod.icon" class="size-5" :class="mod.iconColor" />
                </div>
                <div class="min-w-0">
                  <div class="flex items-center gap-2 mb-1">
                    <h3 class="text-sm font-semibold text-slate-900 group-hover:text-violet-600 transition-colors duration-200">
                      {{ mod.title }}
                    </h3>
                    <span
                      v-if="mod.badge"
                      class="text-xs px-1.5 py-0.5 rounded-md bg-violet-100 text-violet-700 border border-violet-200 font-semibold"
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

          <p class="text-center text-xs text-slate-400 mt-8">
            Mostrando {{ filteredModules.length }} de 12 módulos
          </p>
        </div>
      </section>

      <!-- ============ BENEFITS ============ -->
      <section id="beneficios-uni" class="py-24 px-4 sm:px-6 lg:px-8 bg-slate-50 border-b border-slate-200" aria-labelledby="beneficios-uni-title">
        <div class="max-w-7xl mx-auto">
          <div class="text-center mb-16">
            <h2 id="beneficios-uni-title" class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
              Diseñado para cada actor de tu institución
            </h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
              Cada rol tiene exactamente lo que necesita, sin complejidad innecesaria.
            </p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Rectoría -->
            <article class="rounded-2xl p-10 bg-white border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300">
              <div class="flex items-center gap-3 mb-8">
                <div class="p-2.5 bg-violet-50 rounded-xl shrink-0">
                  <UIcon name="i-lucide-building-2" class="size-5 text-violet-500" />
                </div>
                <h3 class="text-lg font-bold text-slate-900">
                  Para Rectoría y Vicerrectoría
                </h3>
              </div>
              <ul class="space-y-4">
                <li v-for="b in benefitsRectoria" :key="b" class="flex items-start gap-3">
                  <UIcon name="i-lucide-check-circle" class="size-4 shrink-0 mt-0.5 text-violet-400" />
                  <span class="text-sm text-slate-600 leading-relaxed">{{ b }}</span>
                </li>
              </ul>
            </article>

            <!-- Docentes -->
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
                <li v-for="b in benefitsDocentes" :key="b" class="flex items-start gap-3">
                  <UIcon name="i-lucide-check-circle" class="size-4 shrink-0 mt-0.5 text-emerald-400" />
                  <span class="text-sm text-slate-600 leading-relaxed">{{ b }}</span>
                </li>
              </ul>
            </article>

            <!-- Estudiantes -->
            <article class="rounded-2xl p-10 bg-white border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300">
              <div class="flex items-center gap-3 mb-8">
                <div class="p-2.5 bg-blue-50 rounded-xl shrink-0">
                  <UIcon name="i-lucide-user-check" class="size-5 text-blue-500" />
                </div>
                <h3 class="text-lg font-bold text-slate-900">
                  Para Estudiantes
                </h3>
              </div>
              <ul class="space-y-4">
                <li v-for="b in benefitsEstudiantes" :key="b" class="flex items-start gap-3">
                  <UIcon name="i-lucide-check-circle" class="size-4 shrink-0 mt-0.5 text-blue-400" />
                  <span class="text-sm text-slate-600 leading-relaxed">{{ b }}</span>
                </li>
              </ul>
            </article>
          </div>
        </div>
      </section>

      <!-- ============ DIFFERENTIATORS vs SECTOR-OFICIAL ============ -->
      <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white border-b border-slate-200" aria-labelledby="diferenciadores-title">
        <div class="max-w-5xl mx-auto">
          <div class="grid md:grid-cols-2 gap-16 items-center">
            <div>
              <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-4">
                Entendemos la educación superior
              </p>
              <h2 id="diferenciadores-title" class="text-3xl font-bold text-slate-900 leading-snug mb-6">
                Construido para créditos,<br> programas y semestres
              </h2>
              <p class="text-slate-500 leading-relaxed mb-5">
                No es el mismo sistema K-12 con otro nombre. Aula360 para educación superior maneja la lógica real de las IES: estudiantes que se matriculan por materia, créditos ponderados por calificación y programas con pensum estructurado.
              </p>
              <p class="text-slate-500 leading-relaxed mb-5">
                Un estudiante puede estar en Semestre III de Administración, reprobar Matemáticas Financieras y re-matricularla en el siguiente semestre — Aula360 rastrea ese historial completo y la IA lo incorpora al índice de riesgo.
              </p>
              <a
                href="/universidades/como-funciona"
                class="inline-flex items-center gap-2 text-sm font-semibold text-violet-600 hover:text-violet-700 transition-colors"
              >
                Ver el flujo completo paso a paso
                <UIcon name="i-lucide-arrow-right" class="size-4" />
              </a>
            </div>
            <div class="space-y-4">
              <div
                v-for="item in [
                  { label: 'Matrícula por materia con control de semestre', icon: 'i-lucide-clipboard-list' },
                  { label: 'Créditos académicos ponderados en la nota final', icon: 'i-lucide-layers' },
                  { label: 'Historial de intentos por materia (reprobadas)', icon: 'i-lucide-refresh-cw' },
                  { label: 'Historial de notas con promedio acumulado y créditos', icon: 'i-lucide-file-text' },
                  { label: 'Múltiples programas en una sola institución', icon: 'i-lucide-layout-grid' }
                ]"
                :key="item.label"
                class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200"
              >
                <div class="w-9 h-9 rounded-lg bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm">
                  <UIcon :name="item.icon" class="size-4 text-violet-600" />
                </div>
                <p class="text-sm font-medium text-slate-800">
                  {{ item.label }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ============ INCLUDED BAND ============ -->
      <section class="px-4 sm:px-6 lg:px-8 py-16 border-t border-slate-200 bg-white" aria-labelledby="incluido-uni-title">
        <div class="max-w-7xl mx-auto">
          <h2 id="incluido-uni-title" class="text-center text-slate-500 text-sm font-medium mb-2 uppercase tracking-wider">
            Incluido en todos los planes para educación superior
          </h2>
          <p class="text-center mb-10">
            <a href="/pricing" class="text-violet-600 hover:text-violet-700 text-sm font-semibold transition-colors">
              Consulta precios para tu IES →
            </a>
          </p>
          <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div
              v-for="item in includedItems"
              :key="item.label"
              class="flex flex-col items-center text-center gap-3"
            >
              <div class="w-12 h-12 rounded-xl bg-violet-50 border border-violet-100 flex items-center justify-center">
                <UIcon :name="item.icon" class="w-5 h-5 text-violet-600" />
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
      <section id="contacto-uni" class="py-16 px-4 sm:px-6 lg:px-8 bg-white" aria-labelledby="cta-uni-title">
        <div class="max-w-5xl mx-auto">
          <div class="rounded-3xl px-8 py-16 sm:px-16 sm:py-20 text-center bg-gradient-to-br from-violet-100 via-purple-50 to-indigo-100">
            <h2 id="cta-uni-title" class="text-3xl sm:text-5xl font-extrabold text-slate-900 mb-6 leading-tight tracking-tight">
              Tu IES merece retener<br class="hidden sm:block"> a sus mejores estudiantes.
            </h2>
            <p class="text-lg text-slate-600 mb-10 max-w-2xl mx-auto leading-relaxed">
              Aula360 está lista para tu institución. Cuéntanos el nombre, ciudad, número de programas y estudiantes — en 24 horas tenemos una propuesta lista para ti.
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
    <PublicFooter />

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
  .mod-tab-leave-active {
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
