<script setup lang="ts">
definePageMeta({
  layout: false
})

useSeoMeta({
  title: 'Precios para Universidades — Aula360 | Software de Gestión Académica para IES Colombianas',
  description: 'Planes transparentes para universidades, tecnológicas e IES colombianas. Gestión de programas, créditos, matrículas por materia, IA anti-deserción y certificados de notas. Sin costos ocultos.',
  keywords: 'precio software universidad colombia, plan gestión académica IES, precio plataforma educación superior colombia, software universidad SaaS colombia, gestión programas académicos precio, IA deserción universitaria costo, certificados notas pdf universidad precio, matrícula por créditos sistema precio',
  author: 'Aula360',
  robots: 'index, follow',
  ogTitle: 'Precios Aula360 para Universidades — Software Académico IES Colombianas',
  ogDescription: 'Planes transparentes para IES colombianas. Desde $690.000/mes. Sin costos ocultos, docentes ilimitados, IA anti-deserción y certificados PDF incluidos.',
  ogType: 'website',
  ogImage: 'https://aula360.com/og-image.png',
  ogImageWidth: '1200',
  ogImageHeight: '630',
  ogImageAlt: 'Precios Aula360 para Universidades — Planes para IES colombianas',
  ogUrl: 'https://aula360.com/universidades/pricing',
  ogLocale: 'es_CO',
  ogSiteName: 'Aula360',
  twitterCard: 'summary_large_image',
  twitterSite: '@aula360co',
  twitterCreator: '@aula360co',
  twitterTitle: 'Precios Aula360 para Universidades — IES Colombianas',
  twitterDescription: 'Planes para universidades colombianas. Sin negociaciones, docentes ilimitados, IA anti-deserción. Demo gratuita.',
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
    { rel: 'canonical', href: 'https://aula360.com/universidades/pricing' },
    { rel: 'sitemap', type: 'application/xml', href: 'https://aula360.com/sitemap.xml' }
  ],
  script: [
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'SoftwareApplication',
        'name': 'Aula360 para Universidades',
        'url': 'https://aula360.com/universidades',
        'applicationCategory': 'EducationalApplication',
        'operatingSystem': 'Web',
        'description': 'Sistema de gestión académica con IA para universidades, tecnológicas e IES colombianas.',
        'priceRange': '$690.000 - $2.490.000+ COP/mes',
        'provider': { '@type': 'Organization', 'name': 'Aula360', 'url': 'https://aula360.com' },
        'offers': [
          {
            '@type': 'Offer',
            'name': 'Plan Inicial',
            'description': 'Hasta 300 estudiantes. Programas, matrículas por crédito, historial académico, certificados PDF y portal del estudiante.',
            'price': '690000',
            'priceCurrency': 'COP',
            'eligibleRegion': { '@type': 'Country', 'name': 'Colombia' },
            'priceSpecification': { '@type': 'UnitPriceSpecification', 'billingIncrement': 1, 'unitCode': 'MON' }
          },
          {
            '@type': 'Offer',
            'name': 'Plan Universitario',
            'description': 'Hasta 1.500 estudiantes. Incluye IA anti-deserción y análisis de riesgo por programa.',
            'price': '1290000',
            'priceCurrency': 'COP',
            'eligibleRegion': { '@type': 'Country', 'name': 'Colombia' },
            'priceSpecification': { '@type': 'UnitPriceSpecification', 'billingIncrement': 1, 'unitCode': 'MON' }
          },
          {
            '@type': 'Offer',
            'name': 'Plan Campus',
            'description': 'Hasta 5.000 estudiantes. IA completa con diagnóstico pedagógico y reportes ejecutivos para Rectoría.',
            'price': '2490000',
            'priceCurrency': 'COP',
            'eligibleRegion': { '@type': 'Country', 'name': 'Colombia' },
            'priceSpecification': { '@type': 'UnitPriceSpecification', 'billingIncrement': 1, 'unitCode': 'MON' }
          }
        ]
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
          { '@type': 'ListItem', 'position': 3, 'name': 'Precios', 'item': 'https://aula360.com/universidades/pricing' }
        ]
      })
    },
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'FAQPage',
        'mainEntity': [
          {
            '@type': 'Question',
            'name': '¿Hay costos adicionales por número de docentes o programas?',
            'acceptedAnswer': { '@type': 'Answer', 'text': 'No. Todos los planes incluyen docentes y programas ilimitados. Solo el número de estudiantes matriculados define su plan.' }
          },
          {
            '@type': 'Question',
            'name': '¿El sistema maneja créditos académicos por materia?',
            'acceptedAnswer': { '@type': 'Answer', 'text': 'Sí. Cada materia tiene créditos configurables. El sistema calcula promedios ponderados por crédito, controla créditos aprobados y reprobados por estudiante.' }
          },
          {
            '@type': 'Question',
            'name': '¿Funciona para matrículas por materia (no por semestre)?',
            'acceptedAnswer': { '@type': 'Answer', 'text': 'Sí. El modelo de matrícula es por materia individual con control de semestre. Cada estudiante puede inscribir materias distintas según su avance en el pensum.' }
          },
          {
            '@type': 'Question',
            'name': '¿Puedo cambiar de plan en cualquier momento?',
            'acceptedAnswer': { '@type': 'Answer', 'text': 'Sí. Puede subir de plan en cualquier momento y el costo se prorratea. Para bajar de plan, el cambio aplica al siguiente período de facturación.' }
          },
          {
            '@type': 'Question',
            'name': '¿Hay período de prueba gratuito?',
            'acceptedAnswer': { '@type': 'Answer', 'text': 'Ofrecemos una demo personalizada de 30 minutos y un piloto gratuito de 30 días para evaluar Aula360 con datos reales de su institución, sin compromiso.' }
          }
        ]
      })
    }
  ]
})

const billing = ref<'monthly' | 'annual'>('annual')

interface Plan {
  name: string
  tagline: string
  monthly: number | null
  annual: number | null
  annualTotal: string | null
  maxStudents: string
  badge: string | null
  badgeColor: string
  accentColor: string
  cta: string
  ctaStyle: string
  features: { label: string, included: boolean }[]
}

const plans: Plan[] = [
  {
    name: 'Inicial',
    tagline: 'Para IES pequeñas y programas piloto',
    monthly: 690000,
    annual: 575000,
    annualTotal: '$6.900.000',
    maxStudents: 'Hasta 300 estudiantes',
    badge: null,
    badgeColor: '',
    accentColor: 'slate',
    cta: 'Solicitar demo',
    ctaStyle: 'border',
    features: [
      { label: 'Gestión de programas académicos', included: true },
      { label: 'Matrícula por materia y créditos', included: true },
      { label: 'Calificaciones por corte', included: true },
      { label: 'Historial académico digital', included: true },
      { label: 'Certificados de notas PDF', included: true },
      { label: 'Control de asistencia por materia', included: true },
      { label: 'Portal del estudiante 24/7', included: true },
      { label: 'Soporte por email', included: true },
      { label: 'IA anti-deserción', included: false },
      { label: 'Diagnóstico pedagógico IA', included: false },
      { label: 'Reporte ejecutivo para Rectoría', included: false }
    ]
  },
  {
    name: 'Universitario',
    tagline: 'El plan más elegido por IES colombianas',
    monthly: 1290000,
    annual: 1075000,
    annualTotal: '$12.900.000',
    maxStudents: 'Hasta 1.500 estudiantes',
    badge: 'Más elegido',
    badgeColor: 'violet',
    accentColor: 'violet',
    cta: 'Solicitar demo',
    ctaStyle: 'filled',
    features: [
      { label: 'Gestión de programas académicos', included: true },
      { label: 'Matrícula por materia y créditos', included: true },
      { label: 'Calificaciones por corte', included: true },
      { label: 'Historial académico digital', included: true },
      { label: 'Certificados de notas PDF', included: true },
      { label: 'Control de asistencia por materia', included: true },
      { label: 'Portal del estudiante 24/7', included: true },
      { label: 'Soporte prioritario', included: true },
      { label: 'IA anti-deserción', included: true },
      { label: 'Diagnóstico pedagógico IA', included: false },
      { label: 'Reporte ejecutivo para Rectoría', included: false }
    ]
  },
  {
    name: 'Campus',
    tagline: 'Para universidades con IA completa',
    monthly: 2490000,
    annual: 2075000,
    annualTotal: '$24.900.000',
    maxStudents: 'Hasta 5.000 estudiantes',
    badge: 'IA completa',
    badgeColor: 'indigo',
    accentColor: 'indigo',
    cta: 'Agendar demo',
    ctaStyle: 'filled',
    features: [
      { label: 'Gestión de programas académicos', included: true },
      { label: 'Matrícula por materia y créditos', included: true },
      { label: 'Calificaciones por corte', included: true },
      { label: 'Historial académico digital', included: true },
      { label: 'Certificados de notas PDF', included: true },
      { label: 'Control de asistencia por materia', included: true },
      { label: 'Portal del estudiante 24/7', included: true },
      { label: 'Soporte prioritario + chat', included: true },
      { label: 'IA anti-deserción', included: true },
      { label: 'Diagnóstico pedagógico IA', included: true },
      { label: 'Reporte ejecutivo para Rectoría', included: true }
    ]
  },
  {
    name: 'Enterprise',
    tagline: 'Multi-sede, integraciones y SLA garantizado',
    monthly: null,
    annual: null,
    annualTotal: null,
    maxStudents: 'Estudiantes ilimitados',
    badge: null,
    badgeColor: '',
    accentColor: 'slate',
    cta: 'Solicitar cotización',
    ctaStyle: 'border',
    features: [
      { label: 'Todo lo de Campus', included: true },
      { label: 'Multi-sede centralizada', included: true },
      { label: 'SSO / integración AD', included: true },
      { label: 'SLA garantizado', included: true },
      { label: 'Gerente de cuenta dedicado', included: true },
      { label: 'Capacitación in-situ', included: true },
      { label: 'Integraciones a medida', included: true },
      { label: 'IA anti-deserción', included: true },
      { label: 'Diagnóstico pedagógico IA', included: true },
      { label: 'Reporte ejecutivo para Rectoría', included: true }
    ]
  }
]

function displayPrice(plan: Plan): string {
  if (plan.monthly === null) return 'Cotización'
  if (billing.value === 'annual' && plan.annual !== null) {
    return `$${plan.annual.toLocaleString('es-CO')}`
  }
  return `$${plan.monthly.toLocaleString('es-CO')}`
}

function savingsLabel(plan: Plan): string | null {
  if (billing.value !== 'annual' || plan.monthly === null || plan.annual === null) return null
  const saved = (plan.monthly - plan.annual) * 12
  return `Ahorra $${Math.round(saved / 1000)}k al año`
}

const faqs = [
  {
    q: '¿Hay costos adicionales por número de docentes o programas?',
    a: 'No. Todos los planes incluyen docentes y programas ilimitados. Solo el número de estudiantes matriculados define su plan. Puede invitar a todos sus docentes sin costo extra.'
  },
  {
    q: '¿El sistema maneja créditos académicos por materia?',
    a: 'Sí. Cada materia tiene créditos configurables. El sistema calcula promedios ponderados por crédito, controla créditos aprobados y reprobados por estudiante, y genera el historial académico completo.'
  },
  {
    q: '¿Funciona para matrículas por materia (no por semestre cerrado)?',
    a: 'Sí. El modelo de matrícula es por materia individual con control de semestre. Cada estudiante puede inscribir materias según su avance en el pensum, con control de estados: matriculado, retirado, aprobado o reprobado.'
  },
  {
    q: '¿Puedo cambiar de plan en cualquier momento?',
    a: 'Sí. Puede subir de plan en cualquier momento y el costo se prorratea automáticamente. Para bajar de plan, el cambio aplica al siguiente período de facturación.'
  },
  {
    q: '¿Hay período de prueba gratuito?',
    a: 'Ofrecemos una demo personalizada de 30 minutos y un piloto gratuito de 30 días completos para que evalúe Aula360 con datos reales de su institución, sin compromiso de pago.'
  }
]

const openFaq = ref<number | null>(null)
const toggleFaq = (i: number) => {
  openFaq.value = openFaq.value === i ? null : i
}

const showDemo = ref(false)
</script>

<template>
  <div class="min-h-screen bg-white" style="font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;">
    <PublicNavbarUniversidades @open-demo="showDemo = true" />

    <main>
      <!-- Hero -->
      <section class="pt-28 pb-16 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-violet-50/60 to-white">
        <div class="max-w-4xl mx-auto text-center">
          <nav class="flex items-center justify-center gap-1.5 text-xs text-slate-400 mb-6" aria-label="Breadcrumb">
            <a href="/universidades" class="hover:text-violet-600 transition-colors">
              Universidades
            </a>
            <UIcon name="i-lucide-chevron-right" class="w-3 h-3" />
            <span class="text-slate-600">Precios</span>
          </nav>
          <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-violet-50 border border-violet-200 mb-8">
            <UIcon name="i-lucide-tag" class="text-violet-600 w-4 h-4" />
            <span class="text-sm text-violet-700 font-medium">Precios públicos para educación superior — sin sorpresas</span>
          </div>
          <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight mb-6">
            Un precio claro para<br>
            <span class="text-violet-600">cada etapa de su IES</span>
          </h1>
          <p class="text-xl text-slate-600 max-w-2xl mx-auto mb-4">
            Somos los únicos en el mercado colombiano con precios publicados para educación superior. Sin negociaciones eternas, sin letra pequeña.
          </p>
          <p class="text-base text-slate-500">
            Todos los planes incluyen onboarding guiado, docentes ilimitados, programas ilimitados y soporte en español.
          </p>
        </div>
      </section>

      <!-- Toggle facturación -->
      <section class="px-4 sm:px-6 lg:px-8 mb-12">
        <div class="flex items-center justify-center gap-4">
          <span :class="['text-sm font-medium transition-colors', billing === 'monthly' ? 'text-slate-900' : 'text-slate-400']">
            Mensual
          </span>
          <button
            type="button"
            :class="[
              'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2',
              billing === 'annual' ? 'bg-violet-600' : 'bg-slate-300'
            ]"
            :aria-checked="billing === 'annual'"
            role="switch"
            aria-label="Cambiar entre facturación mensual y anual"
            @click="billing = billing === 'annual' ? 'monthly' : 'annual'"
          >
            <span
              :class="[
                'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                billing === 'annual' ? 'translate-x-5' : 'translate-x-0'
              ]"
            />
          </button>
          <span :class="['text-sm font-medium transition-colors', billing === 'annual' ? 'text-slate-900' : 'text-slate-400']">
            Anual
          </span>
          <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-medium text-emerald-700">
            <UIcon name="i-lucide-gift" class="w-3 h-3" />
            2 meses gratis
          </span>
        </div>
      </section>

      <!-- Grid de planes -->
      <section class="px-4 sm:px-6 lg:px-8 pb-20" aria-labelledby="plans-heading">
        <h2 id="plans-heading" class="sr-only">
          Planes de precios para universidades
        </h2>
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="plan in plans"
            :key="plan.name"
            :class="[
              'relative rounded-2xl border p-7 flex flex-col transition-all duration-300',
              plan.accentColor === 'violet'
                ? 'bg-white border-violet-400 shadow-xl shadow-violet-100 ring-1 ring-violet-400'
                : plan.accentColor === 'indigo'
                  ? 'bg-white border-indigo-200 shadow-md hover:shadow-lg'
                  : 'bg-white border-slate-200 shadow-sm hover:shadow-md'
            ]"
          >
            <!-- Badge -->
            <div v-if="plan.badge" class="absolute -top-3.5 left-1/2 -translate-x-1/2">
              <span
                :class="[
                  'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold',
                  plan.badgeColor === 'violet'
                    ? 'bg-violet-600 text-white'
                    : 'bg-indigo-600 text-white'
                ]"
              >
                {{ plan.badge }}
              </span>
            </div>

            <!-- Nombre del plan -->
            <h3 class="text-lg font-bold text-slate-900 mb-0.5">
              {{ plan.name }}
            </h3>
            <p class="text-xs text-slate-400 mb-1.5 leading-snug">
              {{ plan.tagline }}
            </p>
            <p class="text-sm text-slate-500 mb-5">
              {{ plan.maxStudents }}
            </p>

            <!-- Precio -->
            <div class="mb-6 pb-6 border-b border-slate-100">
              <div v-if="plan.monthly !== null" class="flex items-end gap-1">
                <span class="text-3xl font-extrabold text-slate-900">{{ displayPrice(plan) }}</span>
                <span class="text-slate-500 text-sm mb-1">/mes</span>
              </div>
              <div v-else>
                <span class="text-2xl font-bold text-slate-900">A cotizar</span>
              </div>
              <div v-if="billing === 'annual' && plan.annualTotal" class="mt-1 text-xs text-slate-400">
                {{ plan.annualTotal }}/año
              </div>
              <div v-if="savingsLabel(plan)" class="mt-1.5">
                <span class="inline-flex items-center gap-1 text-xs text-emerald-600 font-medium">
                  <UIcon name="i-lucide-trending-down" class="w-3 h-3" />
                  {{ savingsLabel(plan) }}
                </span>
              </div>
            </div>

            <!-- CTA -->
            <button
              type="button"
              :class="[
                'w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors duration-200 mb-6 cursor-pointer',
                plan.ctaStyle === 'filled' && plan.accentColor === 'violet'
                  ? 'bg-violet-600 hover:bg-violet-500 text-white'
                  : plan.ctaStyle === 'filled' && plan.accentColor === 'indigo'
                    ? 'bg-indigo-600 hover:bg-indigo-500 text-white'
                    : 'border border-slate-300 hover:bg-slate-50 text-slate-700 hover:border-slate-400'
              ]"
              @click="showDemo = true"
            >
              {{ plan.cta }}
            </button>

            <!-- Features -->
            <ul class="space-y-3 flex-1">
              <li
                v-for="feature in plan.features"
                :key="feature.label"
                class="flex items-start gap-2.5 text-sm"
              >
                <UIcon
                  :name="feature.included ? 'i-lucide-check' : 'i-lucide-x'"
                  :class="[
                    'w-4 h-4 mt-0.5 flex-shrink-0',
                    feature.included ? 'text-emerald-500' : 'text-slate-300'
                  ]"
                />
                <span :class="feature.included ? 'text-slate-700' : 'text-slate-400'">
                  {{ feature.label }}
                </span>
              </li>
            </ul>
          </div>
        </div>
      </section>

      <!-- ¿Vienes de un colegio? -->
      <div class="text-center pb-10 px-4">
        <p class="text-sm text-slate-500">
          ¿Eres un colegio o institución K-12?
          <a href="/pricing" class="text-violet-600 hover:text-violet-700 font-semibold transition-colors">
            Ver planes para colegios →
          </a>
        </p>
      </div>

      <!-- Incluido en todos los planes -->
      <section class="px-4 sm:px-6 lg:px-8 py-20 bg-slate-50 border-y border-slate-200" aria-labelledby="included-heading">
        <div class="max-w-5xl mx-auto">
          <div class="text-center mb-12">
            <h2 id="included-heading" class="text-3xl font-bold text-slate-900 mb-4">
              Incluido en todos los planes
            </h2>
            <p class="text-slate-600 max-w-2xl mx-auto">
              Sin costos ocultos. Cada plan incluye lo que su IES necesita para operar desde el primer día.
            </p>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div
              v-for="item in [
                { icon: 'i-lucide-users', title: 'Docentes ilimitados', desc: 'Invite a todos sus docentes sin costo adicional por usuario.' },
                { icon: 'i-lucide-book-open', title: 'Programas ilimitados', desc: 'Configure todos sus programas académicos: pregrado, tecnológico, técnico.' },
                { icon: 'i-lucide-database', title: 'Onboarding guiado', desc: 'En 48 horas tenemos su pensum, materias y créditos configurados.' },
                { icon: 'i-lucide-headphones', title: 'Soporte en español', desc: 'Equipo de soporte colombiano que conoce la normativa local.' },
                { icon: 'i-lucide-shield-check', title: 'Backups automáticos', desc: 'Sus datos respaldados diariamente con 99.9% de disponibilidad.' },
                { icon: 'i-lucide-refresh-cw', title: 'Actualizaciones incluidas', desc: 'Nuevas funcionalidades sin costo adicional, siempre en la última versión.' }
              ]"
              :key="item.title"
              class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm"
            >
              <div class="w-9 h-9 rounded-lg bg-violet-50 flex items-center justify-center mb-4">
                <UIcon :name="item.icon" class="w-4.5 h-4.5 text-violet-600" />
              </div>
              <h3 class="text-sm font-bold text-slate-900 mb-1.5">
                {{ item.title }}
              </h3>
              <p class="text-sm text-slate-500 leading-relaxed">
                {{ item.desc }}
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- Comparativa vs competencia -->
      <section class="px-4 sm:px-6 lg:px-8 py-20" aria-labelledby="compare-heading">
        <div class="max-w-3xl mx-auto">
          <div class="text-center mb-10">
            <h2 id="compare-heading" class="text-3xl font-bold text-slate-900 mb-4">
              ¿Por qué Aula360 y no otro?
            </h2>
            <p class="text-slate-600">
              La mayoría de alternativas están diseñadas para colegios K-12. Aula360 entiende cómo funciona la educación superior colombiana.
            </p>
          </div>
          <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="grid grid-cols-3 bg-slate-50 border-b border-slate-200 px-6 py-3">
              <span class="text-xs text-slate-400 font-medium uppercase tracking-wider" />
              <span class="text-xs text-center text-violet-600 font-bold uppercase tracking-wider">Aula360</span>
              <span class="text-xs text-center text-slate-400 font-medium uppercase tracking-wider">Alternativas típicas</span>
            </div>
            <div
              v-for="(row, i) in [
                ['Precios públicos sin negociar', true, false],
                ['Diseñado para créditos académicos', true, false],
                ['Matrícula por materia (no por semestre cerrado)', true, false],
                ['IA anti-deserción universitaria', true, false],
                ['Portal del estudiante 24/7', true, false],
                ['Certificados de notas PDF', true, false],
                ['Onboarding en 48h', true, false],
                ['Soporte en español colombiano', true, false]
              ]"
              :key="row[0] as string"
              :class="['grid grid-cols-3 items-center px-6 py-4', i % 2 === 0 ? 'bg-white' : 'bg-slate-50/60']"
            >
              <span class="text-sm text-slate-700">{{ row[0] }}</span>
              <div class="flex justify-center">
                <UIcon name="i-lucide-check-circle-2" class="w-5 h-5 text-emerald-500" />
              </div>
              <div class="flex justify-center">
                <UIcon name="i-lucide-x-circle" class="w-5 h-5 text-red-300" />
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- FAQ -->
      <section class="px-4 sm:px-6 lg:px-8 py-20 bg-slate-50 border-t border-slate-200" aria-labelledby="faq-heading">
        <div class="max-w-3xl mx-auto">
          <div class="text-center mb-10">
            <h2 id="faq-heading" class="text-3xl font-bold text-slate-900 mb-4">
              Preguntas frecuentes
            </h2>
          </div>
          <div class="space-y-3">
            <div
              v-for="(faq, i) in faqs"
              :key="faq.q"
              class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm"
            >
              <button
                type="button"
                class="w-full flex items-center justify-between gap-4 px-6 py-4 text-left cursor-pointer"
                :aria-expanded="openFaq === i"
                @click="toggleFaq(i)"
              >
                <span class="text-slate-900 font-medium text-sm sm:text-base">{{ faq.q }}</span>
                <UIcon
                  :name="openFaq === i ? 'i-lucide-chevron-up' : 'i-lucide-chevron-down'"
                  class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform duration-200"
                />
              </button>
              <div v-show="openFaq === i" class="px-6 pb-5">
                <p class="text-slate-600 text-sm leading-relaxed">
                  {{ faq.a }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA final -->
      <section class="py-16 px-4 sm:px-6 lg:px-8 bg-white border-t border-slate-200">
        <div class="max-w-5xl mx-auto">
          <div class="rounded-3xl px-8 py-16 sm:px-16 sm:py-20 text-center bg-gradient-to-br from-violet-100 via-purple-50 to-indigo-100">
            <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mb-4 tracking-tight">
              ¿Listo para implementarlo en su IES?
            </h2>
            <p class="text-slate-600 mb-10 max-w-xl mx-auto leading-relaxed">
              Agende una demo de 30 minutos con nuestro equipo. Le mostramos Aula360 configurado con los programas y pensum de su institución, sin compromiso.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
              <button
                type="button"
                class="inline-flex items-center px-8 py-3.5 text-base font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl shadow-lg hover:-translate-y-0.5 transition-all duration-200 cursor-pointer"
                @click="showDemo = true"
              >
                Agendar demo gratuita
                <UIcon name="i-lucide-arrow-right" class="w-5 h-5 ml-2" />
              </button>
              <a
                href="/universidades"
                class="inline-flex items-center px-8 py-3.5 text-base font-medium text-slate-900 border-2 border-slate-900 hover:bg-slate-900/10 rounded-xl transition-all duration-200"
              >
                Ver funcionalidades
                <UIcon name="i-lucide-arrow-right" class="w-5 h-5 ml-2" />
              </a>
            </div>
          </div>
        </div>
      </section>
    </main>

    <PublicFooter />
    <DemoFormModal v-model="showDemo" />
  </div>
</template>
