<script setup lang="ts">
definePageMeta({
  layout: false
})

useSeoMeta({
  title: 'Cómo funciona Aula360 para Universidades — Ciclo Académico en Educación Superior',
  description: 'Descubre cómo Aula360 gestiona programas, créditos, matrículas por materia, riesgo de deserción con IA y certificados de notas para universidades e IES colombianas.',
  keywords: 'cómo funciona software universidad colombia, ciclo académico educación superior, matrícula por créditos sistema, riesgo deserción universitaria IA colombia, certificado notas pdf universidad, historial académico digital IES, gestión programas académicos, semestres universitarios sistema',
  author: 'Aula360',
  robots: 'index, follow',
  ogTitle: 'Cómo funciona Aula360 para Universidades — Ciclo Académico en Educación Superior',
  ogDescription: 'Desde la configuración de programas hasta que el estudiante descarga su certificado de notas — todo en una sola plataforma para IES colombianas.',
  ogType: 'website',
  ogImage: 'https://aula360.com/og-image.png',
  ogImageWidth: '1200',
  ogImageHeight: '630',
  ogImageAlt: 'Aula360 para Universidades — Cómo funciona la plataforma de gestión académica para educación superior',
  ogUrl: 'https://aula360.com/universidades/como-funciona',
  ogLocale: 'es_CO',
  ogLocaleAlternate: ['es_ES', 'es_419'],
  ogSiteName: 'Aula360',
  twitterCard: 'summary_large_image',
  twitterSite: '@aula360co',
  twitterCreator: '@aula360co',
  twitterTitle: 'Cómo funciona Aula360 para Universidades',
  twitterDescription: 'Desde la configuración de programas hasta el certificado de notas. Todo en una sola plataforma para IES colombianas.',
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
    { rel: 'canonical', href: 'https://aula360.com/universidades/como-funciona' },
    { rel: 'sitemap', type: 'application/xml', href: 'https://aula360.com/sitemap.xml' }
  ],
  script: [
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'WebPage',
        'name': 'Cómo funciona Aula360 para Universidades',
        'url': 'https://aula360.com/universidades/como-funciona',
        'description': 'Flujos detallados de Aula360 para educación superior: configuración de programas, matrículas por crédito, riesgo de deserción con IA y certificados de notas.',
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
          { '@type': 'ListItem', 'position': 2, 'name': 'Universidades', 'item': 'https://aula360.com/universidades' },
          { '@type': 'ListItem', 'position': 3, 'name': 'Cómo funciona', 'item': 'https://aula360.com/universidades/como-funciona' }
        ]
      })
    }
  ]
})

const showDemo = ref(false)

const riskInputs = [
  { icon: 'i-lucide-x-circle', label: 'Materias reprobadas', sub: 'Conteo y créditos perdidos acumulados', color: 'text-red-500' },
  { icon: 'i-lucide-calendar-x', label: 'Porcentaje de asistencia', sub: 'Inasistencias por materia vs. clases dictadas', color: 'text-orange-500' },
  { icon: 'i-lucide-layers', label: 'Carga académica', sub: 'Créditos inscritos vs. capacidad histórica', color: 'text-amber-500' },
  { icon: 'i-lucide-trending-down', label: 'Tendencia de notas', sub: 'Comparativo semestre anterior', color: 'text-rose-500' },
  { icon: 'i-lucide-refresh-cw', label: 'Historial de reprobadas', sub: 'Materias cursadas más de una vez', color: 'text-yellow-600' }
]

const riskLevels = [
  { range: '70–100', label: 'Riesgo Alto', desc: 'Intervención inmediata', bg: 'bg-red-500' },
  { range: '35–69', label: 'Riesgo Moderado', desc: 'Seguimiento cercano', bg: 'bg-amber-400' },
  { range: '0–34', label: 'Sin riesgo', desc: 'Monitoreo periódico', bg: 'bg-emerald-500' }
]

const navSteps = [
  { id: 'paso-01', label: 'Programas académicos' },
  { id: 'paso-02', label: 'Docentes y estudiantes' },
  { id: 'paso-03', label: 'Matrículas por materia' },
  { id: 'paso-04', label: 'Notas y asistencia' },
  { id: 'paso-05', label: 'IA anti-deserción' },
  { id: 'paso-06', label: 'Certificados y portal' }
]
</script>

<template>
  <div class="min-h-screen bg-slate-50" style="font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;">
    <PublicNavbarUniversidades @open-demo="showDemo = true" />

    <main class="pt-16">
      <!-- ===== COMPACT HEADER ===== -->
      <header class="bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-8 py-7">
        <div class="max-w-6xl mx-auto flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <nav class="flex items-center gap-1.5 text-xs text-slate-400 mb-2" aria-label="Breadcrumb">
              <a href="/universidades" class="hover:text-violet-600 transition-colors">
                Universidades
              </a>
              <UIcon name="i-lucide-chevron-right" class="w-3 h-3" />
              <span class="text-slate-700 font-medium">Cómo funciona</span>
            </nav>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">
              Guía de flujos: el ciclo académico en Aula360
            </h1>
            <p class="text-sm text-slate-500 mt-1">
              6 pasos — desde la configuración de programas hasta que el estudiante descarga su certificado.
            </p>
          </div>
          <button
            type="button"
            class="shrink-0 inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-violet-600 hover:bg-violet-500 rounded-lg transition-colors duration-200 cursor-pointer"
            @click="showDemo = true"
          >
            Solicitar Demo
            <UIcon name="i-lucide-arrow-right" class="w-4 h-4 ml-1.5" />
          </button>
        </div>
      </header>

      <!-- ===== TWO-COLUMN LAYOUT ===== -->
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:flex lg:gap-10 lg:items-start">
        <!-- Sidebar -->
        <aside class="hidden lg:block w-52 shrink-0">
          <div class="sticky top-24">
            <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-3 px-2">
              En esta guía
            </p>
            <nav class="space-y-0.5" aria-label="Pasos del ciclo académico">
              <a
                v-for="step in navSteps"
                :key="step.id"
                :href="`#${step.id}`"
                class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-slate-600 hover:text-violet-700 hover:bg-violet-50 transition-colors duration-150 group"
              >
                <span class="text-xs font-bold text-slate-300 group-hover:text-violet-400 w-5 shrink-0">
                  {{ String(navSteps.indexOf(step) + 1).padStart(2, '0') }}
                </span>
                {{ step.label }}
              </a>
            </nav>

            <!-- Sidebar CTA -->
            <div class="mt-8 rounded-xl bg-violet-50 border border-violet-200 p-4">
              <p class="text-xs font-semibold text-violet-800 mb-1">
                ¿Lista para implementarlo?
              </p>
              <p class="text-xs text-violet-600 mb-3 leading-relaxed">
                En 48 horas tenemos Aula360 configurado con los programas de su IES.
              </p>
              <button
                type="button"
                class="w-full text-xs font-semibold text-white bg-violet-600 hover:bg-violet-500 rounded-lg py-2 transition-colors cursor-pointer"
                @click="showDemo = true"
              >
                Solicitar Demo
              </button>
            </div>

            <!-- Back link -->
            <a
              href="/universidades"
              class="mt-4 flex items-center gap-1.5 px-3 py-2 text-xs text-slate-400 hover:text-violet-600 transition-colors"
            >
              <UIcon name="i-lucide-arrow-left" class="w-3.5 h-3.5" />
              Volver al inicio
            </a>
          </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 min-w-0 space-y-16">
          <!-- PASO 01 -->
          <section id="paso-01" class="scroll-mt-24">
            <div class="flex items-center gap-3 mb-5">
              <span class="shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-lg bg-violet-600 text-white text-xs font-bold">01</span>
              <h2 class="text-lg font-bold text-slate-900">
                Configuración de programas académicos
              </h2>
            </div>
            <p class="text-slate-600 leading-relaxed mb-5">
              El primer paso es definir la estructura académica de su institución. Aula360 soporta múltiples programas (pregrado, tecnológico, técnico) y por cada uno configura su pensum: semestres, materias y créditos académicos por asignatura.
            </p>
            <p class="text-slate-600 leading-relaxed mb-5">
              Una vez configurado, el sistema opera de forma autónoma: cada nueva matrícula hereda automáticamente las reglas del programa.
            </p>
            <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100 overflow-hidden">
              <div
                v-for="item in [
                  { icon: 'i-lucide-book-open', label: 'Programas', val: 'Pregrado · Tecnológico · Técnico · Posgrado' },
                  { icon: 'i-lucide-layers', label: 'Pensum', val: 'Semestres, materias y créditos por asignatura' },
                  { icon: 'i-lucide-settings', label: 'Pesos de corte', val: 'Porcentajes por corte configurables por materia' },
                  { icon: 'i-lucide-check-circle', label: 'Activación', val: 'Listo en 48 h con el equipo de onboarding de Aula360' }
                ]"
                :key="item.label"
                class="flex items-start gap-3 px-5 py-3.5"
              >
                <UIcon :name="item.icon" class="w-4 h-4 text-violet-500 mt-0.5 shrink-0" />
                <span class="text-xs font-semibold text-slate-500 w-28 shrink-0">{{ item.label }}</span>
                <span class="text-sm text-slate-700">{{ item.val }}</span>
              </div>
            </div>
          </section>

          <div class="border-t border-slate-200" />

          <!-- PASO 02 -->
          <section id="paso-02" class="scroll-mt-24">
            <div class="flex items-center gap-3 mb-5">
              <span class="shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-600 text-white text-xs font-bold">02</span>
              <h2 class="text-lg font-bold text-slate-900">
                Registro de docentes y estudiantes
              </h2>
            </div>
            <p class="text-slate-600 leading-relaxed mb-5">
              Registre el equipo docente por especialización y asígnelo a materias. Los estudiantes se crean individualmente o se importan por lote con su código de matrícula.
            </p>
            <p class="text-slate-600 leading-relaxed mb-5">
              Cada rol accede solo a lo que le corresponde: el docente ve sus materias y la asistencia de sus grupos; el estudiante ve sus notas y descarga sus certificados; el coordinador tiene visión completa del programa.
            </p>
            <div class="grid sm:grid-cols-3 gap-3">
              <div
                v-for="role in [
                  { icon: 'i-lucide-shield', label: 'Coordinador / Admin', desc: 'Visión completa de programas, matrículas, IA y reportes.' },
                  { icon: 'i-lucide-pencil', label: 'Docente', desc: 'Registro de notas por corte y asistencia de sus materias.' },
                  { icon: 'i-lucide-user', label: 'Estudiante', desc: 'Portal propio: notas, asistencia y descarga de certificados.' }
                ]"
                :key="role.label"
                class="bg-white rounded-xl border border-slate-200 p-4"
              >
                <UIcon :name="role.icon" class="w-4 h-4 text-indigo-500 mb-2" />
                <p class="text-sm font-semibold text-slate-800 mb-1">
                  {{ role.label }}
                </p>
                <p class="text-xs text-slate-500 leading-relaxed">
                  {{ role.desc }}
                </p>
              </div>
            </div>
          </section>

          <div class="border-t border-slate-200" />

          <!-- PASO 03 -->
          <section id="paso-03" class="scroll-mt-24">
            <div class="flex items-center gap-3 mb-5">
              <span class="shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-600 text-white text-xs font-bold">03</span>
              <h2 class="text-lg font-bold text-slate-900">
                Matrícula por materia y semestre
              </h2>
            </div>
            <p class="text-slate-600 leading-relaxed mb-5">
              A diferencia de los colegios K-12 donde el grupo define todo, en educación superior cada estudiante se matricula materia por materia. Aula360 registra el semestre en que se inscribe, los créditos correspondientes y controla el estado de la matrícula durante todo el período.
            </p>
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden mb-4">
              <div class="px-5 py-3 bg-slate-50 border-b border-slate-100">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                  Estados de matrícula
                </p>
              </div>
              <div class="divide-y divide-slate-100">
                <div
                  v-for="st in [
                    { badge: 'Matriculado', color: 'bg-blue-100 text-blue-700', desc: 'Estudiante inscrito y cursando la materia.' },
                    { badge: 'Aprobado', color: 'bg-emerald-100 text-emerald-700', desc: 'Nota final ≥ 3.0 · Créditos acumulados al historial.' },
                    { badge: 'Reprobado', color: 'bg-red-100 text-red-700', desc: 'Nota final < 3.0 · Cuenta en el índice de riesgo IA.' },
                    { badge: 'Retirado', color: 'bg-slate-100 text-slate-600', desc: 'Cancelación voluntaria registrada en el historial.' }
                  ]"
                  :key="st.badge"
                  class="flex items-center gap-4 px-5 py-3"
                >
                  <span :class="['inline-flex text-xs font-semibold px-2.5 py-1 rounded-full w-28 shrink-0 justify-center', st.color]">
                    {{ st.badge }}
                  </span>
                  <span class="text-sm text-slate-600">{{ st.desc }}</span>
                </div>
              </div>
            </div>
          </section>

          <div class="border-t border-slate-200" />

          <!-- PASO 04 -->
          <section id="paso-04" class="scroll-mt-24">
            <div class="flex items-center gap-3 mb-5">
              <span class="shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-lg bg-purple-600 text-white text-xs font-bold">04</span>
              <h2 class="text-lg font-bold text-slate-900">
                Notas por corte y asistencia
              </h2>
            </div>
            <p class="text-slate-600 leading-relaxed mb-5">
              Cada materia tiene cortes configurables con pesos porcentuales. El docente ingresa la nota de cada corte y el sistema calcula el promedio ponderado automáticamente. Si hay múltiples materias, el promedio final se pondera además por créditos.
            </p>
            <div class="grid sm:grid-cols-2 gap-4">
              <div class="bg-white rounded-xl border border-slate-200 p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">
                  Ejemplo de calificación
                </p>
                <div class="space-y-2 text-sm">
                  <div class="flex justify-between text-slate-600">
                    <span>Corte 1 (30%)</span>
                    <span class="font-mono font-semibold text-slate-800">3.8</span>
                  </div>
                  <div class="flex justify-between text-slate-600">
                    <span>Corte 2 (30%)</span>
                    <span class="font-mono font-semibold text-slate-800">4.1</span>
                  </div>
                  <div class="flex justify-between text-slate-600">
                    <span>Corte 3 (40%)</span>
                    <span class="font-mono font-semibold text-slate-800">3.5</span>
                  </div>
                  <div class="flex justify-between border-t border-slate-100 pt-2 font-semibold text-slate-900">
                    <span>Promedio final</span>
                    <span class="font-mono text-emerald-600">3.80</span>
                  </div>
                </div>
              </div>
              <div class="bg-white rounded-xl border border-slate-200 p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">
                  Asistencia
                </p>
                <ul class="space-y-2 text-sm text-slate-600">
                  <li class="flex items-start gap-2">
                    <UIcon name="i-lucide-check" class="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" />
                    Registro diario por materia
                  </li>
                  <li class="flex items-start gap-2">
                    <UIcon name="i-lucide-check" class="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" />
                    Porcentaje de asistencia acumulado
                  </li>
                  <li class="flex items-start gap-2">
                    <UIcon name="i-lucide-check" class="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" />
                    Inasistencias entran al modelo de riesgo IA
                  </li>
                  <li class="flex items-start gap-2">
                    <UIcon name="i-lucide-check" class="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" />
                    Visible para el estudiante en tiempo real
                  </li>
                </ul>
              </div>
            </div>
          </section>

          <div class="border-t border-slate-200" />

          <!-- PASO 05 — IA -->
          <section id="paso-05" class="scroll-mt-24">
            <div class="flex items-center gap-3 mb-5">
              <span class="shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-lg bg-violet-700 text-white text-xs font-bold">05</span>
              <h2 class="text-lg font-bold text-slate-900">
                IA anti-deserción: cómo se calcula el riesgo
              </h2>
            </div>
            <p class="text-slate-600 leading-relaxed mb-5">
              El motor de IA combina cinco variables académicas en un índice de riesgo de 0 a 100 por estudiante. Cuanto mayor el puntaje, mayor la probabilidad de abandono de carrera. El sistema actualiza el índice al cierre de cada corte.
            </p>

            <!-- AI diagram — contained in content column -->
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
              <!-- Variables -->
              <div class="px-5 py-4 border-b border-slate-100">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">
                  Variables que analiza la IA
                </p>
                <div class="grid sm:grid-cols-2 gap-2">
                  <div
                    v-for="input in riskInputs"
                    :key="input.label"
                    class="flex items-start gap-2.5"
                  >
                    <UIcon :name="input.icon" class="w-4 h-4 mt-0.5 shrink-0" :class="input.color" />
                    <div>
                      <p class="text-sm font-medium text-slate-800">
                        {{ input.label }}
                      </p>
                      <p class="text-xs text-slate-400">
                        {{ input.sub }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Score bar -->
              <div class="px-5 py-4 border-b border-slate-100">
                <div class="flex items-center justify-between mb-2">
                  <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                    Índice de riesgo (0 – 100)
                  </p>
                  <UIcon name="i-lucide-brain" class="w-4 h-4 text-violet-500 animate-pulse" />
                </div>
                <div class="h-3 w-full rounded-full overflow-hidden bg-slate-100">
                  <div class="h-full w-full rounded-full" style="background: linear-gradient(to right, #10b981, #f59e0b, #ef4444);" />
                </div>
                <div class="flex justify-between text-xs text-slate-400 mt-1.5">
                  <span>Sin riesgo</span>
                  <span>Riesgo Alto</span>
                </div>
              </div>

              <!-- Levels -->
              <div class="px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">
                  Clasificación y acción
                </p>
                <div class="space-y-2">
                  <div
                    v-for="level in riskLevels"
                    :key="level.label"
                    class="flex items-center gap-3"
                  >
                    <span class="w-3 h-3 rounded-full shrink-0" :class="level.bg" />
                    <span class="text-xs font-mono text-slate-400 w-16 shrink-0">{{ level.range }}</span>
                    <span class="text-sm font-semibold text-slate-800 w-32 shrink-0">{{ level.label }}</span>
                    <span class="text-xs text-slate-500">{{ level.desc }}</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="mt-4 flex items-start gap-3 bg-violet-50 border border-violet-200 rounded-xl px-5 py-4">
              <UIcon name="i-lucide-sparkles" class="w-4 h-4 text-violet-600 mt-0.5 shrink-0" />
              <p class="text-sm text-violet-800 leading-relaxed">
                <strong>Narrativa pedagógica:</strong> la IA genera un informe personalizado con análisis de causas y recomendaciones de intervención para el docente y la Vicerrectoría Académica.
              </p>
            </div>
          </section>

          <div class="border-t border-slate-200" />

          <!-- PASO 06 -->
          <section id="paso-06" class="scroll-mt-24">
            <div class="flex items-center gap-3 mb-5">
              <span class="shrink-0 inline-flex items-center justify-center w-7 h-7 rounded-lg bg-emerald-600 text-white text-xs font-bold">06</span>
              <h2 class="text-lg font-bold text-slate-900">
                Certificados de notas y portal del estudiante
              </h2>
            </div>
            <p class="text-slate-600 leading-relaxed mb-5">
              Una vez que el docente cierra las notas del período, el historial académico queda disponible. El coordinador genera certificados de notas en PDF con un clic — con logo institucional, datos del estudiante, programa, materias, créditos y promedio acumulado.
            </p>

            <div class="grid sm:grid-cols-2 gap-4 mb-5">
              <div class="bg-white rounded-xl border border-slate-200 p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">
                  Flujo del certificado
                </p>
                <ol class="space-y-3">
                  <li
                    v-for="(step, i) in [
                      'Docente registra nota final de cada corte',
                      'Sistema calcula promedio ponderado por créditos',
                      'Coordinador genera certificado PDF con un clic',
                      'Estudiante descarga desde su portal 24/7'
                    ]"
                    :key="i"
                    class="flex items-start gap-2.5 text-sm text-slate-600"
                  >
                    <span class="shrink-0 w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold flex items-center justify-center mt-0.5">
                      {{ i + 1 }}
                    </span>
                    {{ step }}
                  </li>
                </ol>
              </div>
              <div class="bg-white rounded-xl border border-slate-200 p-5">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 mb-3">
                  Portal del estudiante
                </p>
                <ul class="space-y-2.5">
                  <li
                    v-for="access in [
                      'Notas por corte en tiempo real',
                      'Historial de asistencia por materia',
                      'Estado de matrículas y créditos acumulados',
                      'Descarga de certificados sin ir a secretaría'
                    ]"
                    :key="access"
                    class="flex items-start gap-2 text-sm text-slate-600"
                  >
                    <UIcon name="i-lucide-check" class="w-4 h-4 text-emerald-500 mt-0.5 shrink-0" />
                    {{ access }}
                  </li>
                </ul>
              </div>
            </div>

            <!-- Final CTA -->
            <div class="bg-slate-900 rounded-xl p-7 text-center">
              <p class="text-white font-bold text-lg mb-2">
                ¿Listo para implementarlo en su institución?
              </p>
              <p class="text-slate-400 text-sm mb-5">
                En 48 horas configuramos Aula360 con sus programas, pensum y docentes.
              </p>
              <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <button
                  type="button"
                  class="inline-flex items-center px-6 py-2.5 text-sm font-bold text-slate-900 bg-white hover:bg-slate-100 rounded-lg transition-colors cursor-pointer"
                  @click="showDemo = true"
                >
                  Solicitar Demo Gratuita
                  <UIcon name="i-lucide-arrow-right" class="w-4 h-4 ml-2" />
                </button>
                <a
                  href="/universidades/pricing"
                  class="inline-flex items-center px-6 py-2.5 text-sm font-medium text-slate-300 hover:text-white border border-slate-700 hover:border-slate-500 rounded-lg transition-colors"
                >
                  Ver precios
                </a>
              </div>
            </div>
          </section>

          <!-- Mobile-only nav to go back -->
          <div class="lg:hidden pt-4 border-t border-slate-200">
            <a
              href="/universidades"
              class="inline-flex items-center gap-1.5 text-sm text-violet-600 hover:text-violet-700 font-medium"
            >
              <UIcon name="i-lucide-arrow-left" class="w-4 h-4" />
              Volver al inicio de Universidades
            </a>
          </div>
        </div>
      </div>
    </main>

    <PublicFooter />
    <DemoFormModal v-model="showDemo" />
  </div>
</template>
