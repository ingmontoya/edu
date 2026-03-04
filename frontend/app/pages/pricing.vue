<script setup lang="ts">
definePageMeta({
  layout: false
})

useSeoMeta({
  title: 'Precios — Aula360 | Software de Gestión Académica para Colegios Colombianos',
  description: 'Planes transparentes para instituciones educativas colombianas. Desde $490.000/mes. Sin costos ocultos, sin costo por docente. Boletines PDF, SIEE, portal de padres e IA incluidos.',
  keywords: 'precio software gestión académica colombia, plan aula360, precio plataforma educativa colegio, software colegio colombia precio, gestión escolar SaaS colombia',
  author: 'Aula360',
  robots: 'index, follow',
  ogTitle: 'Precios Aula360 — Software Académico para Colegios Colombianos',
  ogDescription: 'Únicos con precios públicos en el mercado colombiano. Planes desde $490.000/mes. Sin sorpresas, docentes ilimitados, SIEE incluido.',
  ogType: 'website',
  ogImage: 'https://aula360.com/og-image.png',
  ogImageWidth: '1200',
  ogImageHeight: '630',
  ogImageAlt: 'Precios de Aula360 — Planes para colegios colombianos desde $490.000/mes',
  ogUrl: 'https://aula360.com/pricing',
  ogLocale: 'es_CO',
  ogLocaleAlternate: ['es_ES', 'es_419'],
  ogSiteName: 'Aula360',
  twitterCard: 'summary_large_image',
  twitterSite: '@aula360co',
  twitterCreator: '@aula360co',
  twitterTitle: 'Precios Aula360 — Desde $490.000/mes para colegios colombianos',
  twitterDescription: 'Planes transparentes para colegios colombianos. Sin negociaciones, sin costos por docente. Demo gratuita de 30 días.',
  twitterImage: 'https://aula360.com/og-image.png'
})

useHead({
  htmlAttrs: { lang: 'es-CO' },
  meta: [
    { name: 'viewport', content: 'width=device-width, initial-scale=1' },
    { name: 'theme-color', content: '#0f172a' },
    { name: 'color-scheme', content: 'dark' }
  ],
  link: [
    { rel: 'canonical', href: 'https://aula360.com/pricing' },
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
        'description': 'Sistema de gestión académica con IA para instituciones educativas colombianas.',
        'priceRange': '$490.000 - $2.900.000+ COP/mes',
        'provider': {
          '@type': 'Organization',
          'name': 'Aula360',
          'url': 'https://aula360.com'
        },
        'offers': [
          {
            '@type': 'Offer',
            'name': 'Plan Básico',
            'description': 'Hasta 200 estudiantes. Calificaciones, boletines PDF, portal de padres, SIEE y exportación SIMAT.',
            'price': '490000',
            'priceCurrency': 'COP',
            'eligibleRegion': { '@type': 'Country', 'name': 'Colombia' },
            'priceSpecification': {
              '@type': 'UnitPriceSpecification',
              'billingIncrement': 1,
              'unitCode': 'MON'
            }
          },
          {
            '@type': 'Offer',
            'name': 'Plan Estándar',
            'description': 'Hasta 600 estudiantes. Incluye análisis de riesgo con IA.',
            'price': '890000',
            'priceCurrency': 'COP',
            'eligibleRegion': { '@type': 'Country', 'name': 'Colombia' },
            'priceSpecification': {
              '@type': 'UnitPriceSpecification',
              'billingIncrement': 1,
              'unitCode': 'MON'
            }
          },
          {
            '@type': 'Offer',
            'name': 'Plan Avanzado',
            'description': 'Hasta 1.500 estudiantes. IA completa con diagnóstico pedagógico y constancias automáticas.',
            'price': '1490000',
            'priceCurrency': 'COP',
            'eligibleRegion': { '@type': 'Country', 'name': 'Colombia' },
            'priceSpecification': {
              '@type': 'UnitPriceSpecification',
              'billingIncrement': 1,
              'unitCode': 'MON'
            }
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
          { '@type': 'ListItem', 'position': 2, 'name': 'Precios', 'item': 'https://aula360.com/pricing' }
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
            'name': '¿Hay costos adicionales por número de docentes?',
            'acceptedAnswer': { '@type': 'Answer', 'text': 'No. Todos los planes incluyen docentes ilimitados. Solo el número de estudiantes matriculados define su plan.' }
          },
          {
            '@type': 'Question',
            'name': '¿Puedo cambiar de plan en cualquier momento?',
            'acceptedAnswer': { '@type': 'Answer', 'text': 'Sí. Puede subir de plan en cualquier momento y el costo se prorratea. Para bajar de plan, el cambio aplica al siguiente período de facturación.' }
          },
          {
            '@type': 'Question',
            'name': '¿Qué pasa con mis datos si cancelo?',
            'acceptedAnswer': { '@type': 'Answer', 'text': 'Sus datos son suyos. Puede exportar toda la información en cualquier momento en formato CSV/Excel antes de cancelar. Conservamos sus datos por 90 días adicionales después de la cancelación.' }
          },
          {
            '@type': 'Question',
            'name': '¿El precio anual incluye IVA?',
            'acceptedAnswer': { '@type': 'Answer', 'text': 'Los precios mostrados no incluyen IVA. Para instituciones educativas sin ánimo de lucro, aplican beneficios tributarios según la normativa colombiana. Consulte con nuestro equipo.' }
          },
          {
            '@type': 'Question',
            'name': '¿Hay período de prueba gratuito?',
            'acceptedAnswer': { '@type': 'Answer', 'text': 'Ofrecemos una demo personalizada de 30 minutos y un piloto gratuito de 30 días para que su institución evalúe Aula360 sin compromiso.' }
          }
        ]
      })
    }
  ]
})

const billing = ref<'monthly' | 'annual'>('annual')

const navLinks = [
  { label: 'Inteligencia Artificial', href: '/#ia' },
  { label: 'Módulos', href: '/#modulos' },
  { label: 'Beneficios', href: '/#beneficios' },
  { label: 'Comparativo', href: '/#comparativo' },
  { label: 'Precios', href: '/pricing' },
  { label: 'Contacto', href: '/#contacto' }
]

interface Plan {
  name: string
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
    name: 'Básico',
    monthly: 490000,
    annual: 408333,
    annualTotal: '$4.900.000',
    maxStudents: 'Hasta 200 estudiantes',
    badge: null,
    badgeColor: '',
    accentColor: 'blue',
    cta: 'Solicitar demo',
    ctaStyle: 'border',
    features: [
      { label: 'Gestión de calificaciones', included: true },
      { label: 'Boletines PDF', included: true },
      { label: 'Portal de padres', included: true },
      { label: 'Control de asistencia', included: true },
      { label: 'SIEE configurable', included: true },
      { label: 'Exportación SIMAT', included: true },
      { label: 'Soporte por email', included: true },
      { label: 'Análisis de riesgo con IA', included: false },
      { label: 'Diagnóstico pedagógico IA', included: false },
      { label: 'Constancias automáticas', included: false }
    ]
  },
  {
    name: 'Estándar',
    monthly: 890000,
    annual: 741667,
    annualTotal: '$8.900.000',
    maxStudents: 'Hasta 600 estudiantes',
    badge: 'Más elegido',
    badgeColor: 'indigo',
    accentColor: 'indigo',
    cta: 'Solicitar demo',
    ctaStyle: 'filled',
    features: [
      { label: 'Gestión de calificaciones', included: true },
      { label: 'Boletines PDF', included: true },
      { label: 'Portal de padres', included: true },
      { label: 'Control de asistencia', included: true },
      { label: 'SIEE configurable', included: true },
      { label: 'Exportación SIMAT', included: true },
      { label: 'Soporte prioritario', included: true },
      { label: 'Análisis de riesgo con IA', included: true },
      { label: 'Diagnóstico pedagógico IA', included: false },
      { label: 'Constancias automáticas', included: false }
    ]
  },
  {
    name: 'Avanzado',
    monthly: 1490000,
    annual: 1241667,
    annualTotal: '$14.900.000',
    maxStudents: 'Hasta 1.500 estudiantes',
    badge: 'Con IA completa',
    badgeColor: 'violet',
    accentColor: 'violet',
    cta: 'Agendar demo',
    ctaStyle: 'filled',
    features: [
      { label: 'Gestión de calificaciones', included: true },
      { label: 'Boletines PDF', included: true },
      { label: 'Portal de padres', included: true },
      { label: 'Control de asistencia', included: true },
      { label: 'SIEE configurable', included: true },
      { label: 'Exportación SIMAT', included: true },
      { label: 'Soporte prioritario + chat', included: true },
      { label: 'Análisis de riesgo con IA', included: true },
      { label: 'Diagnóstico pedagógico IA', included: true },
      { label: 'Constancias automáticas', included: true }
    ]
  },
  {
    name: 'Enterprise',
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
      { label: 'Todo lo de Avanzado', included: true },
      { label: 'Multi-sede centralizada', included: true },
      { label: 'SSO / integración AD', included: true },
      { label: 'SLA garantizado', included: true },
      { label: 'Gerente de cuenta dedicado', included: true },
      { label: 'Capacitación in-situ', included: true },
      { label: 'Integraciones a medida', included: true },
      { label: 'Análisis de riesgo con IA', included: true },
      { label: 'Diagnóstico pedagógico IA', included: true },
      { label: 'Constancias automáticas', included: true }
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
    q: '¿Hay costos adicionales por número de docentes?',
    a: 'No. Todos los planes incluyen docentes ilimitados. Solo el número de estudiantes matriculados define su plan. Puede invitar a todos los docentes de su institución sin costo extra.'
  },
  {
    q: '¿Puedo cambiar de plan en cualquier momento?',
    a: 'Sí. Puede subir de plan en cualquier momento y el costo se prorratea automáticamente. Para bajar de plan, el cambio aplica al siguiente período de facturación.'
  },
  {
    q: '¿Qué pasa con mis datos si cancelo?',
    a: 'Sus datos son suyos. Puede exportar toda la información en formato CSV/Excel en cualquier momento. Conservamos sus datos por 90 días adicionales después de la cancelación, sin costo.'
  },
  {
    q: '¿El precio anual incluye IVA?',
    a: 'Los precios mostrados no incluyen IVA. Para instituciones educativas sin ánimo de lucro aplican beneficios tributarios según la normativa colombiana. Nuestro equipo le asesora en el proceso.'
  },
  {
    q: '¿Hay período de prueba gratuito?',
    a: 'Ofrecemos una demo personalizada de 30 minutos y un piloto gratuito de 30 días completos para que evalúe Aula360 con sus propios datos, sin compromiso de pago.'
  }
]

const roiCases = [
  {
    icon: 'i-lucide-file-text',
    title: 'Constancias en segundos',
    before: '45 min',
    after: '30 segundos',
    saving: '44 horas/año por secretaria',
    detail: 'Una secretaria que expide 60 constancias al mes pasa de 45 min a 30 segundos por constancia. Eso son 44 horas liberadas al año — sin errores, sin represas.'
  },
  {
    icon: 'i-lucide-trending-up',
    title: 'Retención estudiantil con IA',
    before: 'Reacción tardía',
    after: 'Detección temprana',
    saving: 'Hasta 8% más retención',
    detail: 'El índice de riesgo detecta estudiantes en peligro académico 6 semanas antes que el método tradicional. Las instituciones piloto reportaron una reducción del 8% en deserción escolar.'
  }
]

const openFaq = ref<number | null>(null)
const toggleFaq = (i: number) => {
  openFaq.value = openFaq.value === i ? null : i
}

const showDemo = ref(false)
</script>

<template>
  <div class="min-h-screen bg-slate-950">
    <!-- Navbar -->
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
              :class="[
                'text-sm transition-colors duration-200',
                link.href === '/pricing'
                  ? 'text-white font-medium'
                  : 'text-slate-400 hover:text-white'
              ]"
            >{{ link.label }}</a>
          </div>
          <div class="flex items-center gap-3">
            <a href="/login" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-300 hover:text-white hover:bg-white/10 rounded-lg transition-colors duration-200">
              Iniciar Sesión
            </a>
            <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-lg shadow-lg shadow-indigo-600/30 transition-colors duration-200" @click="showDemo = true">
              Solicitar Demo
            </button>
          </div>
        </div>
      </nav>
    </header>

    <main>
      <!-- Hero -->
      <section class="relative pt-32 pb-20 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <!-- Orbs decorativos -->
        <div aria-hidden="true" class="absolute top-20 left-1/4 w-96 h-96 rounded-full bg-indigo-600/20 blur-[120px] pointer-events-none" />
        <div aria-hidden="true" class="absolute top-40 right-1/4 w-80 h-80 rounded-full bg-violet-600/15 blur-[120px] pointer-events-none" />

        <div class="relative max-w-4xl mx-auto text-center">
          <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20 mb-8">
            <UIcon name="i-lucide-tag" class="text-indigo-400 w-4 h-4" />
            <span class="text-sm text-indigo-300 font-medium">Precios públicos — sin sorpresas</span>
          </div>
          <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
            Un precio claro para<br>
            <span class="bg-gradient-to-r from-indigo-400 via-violet-400 to-purple-400 bg-clip-text text-transparent">
              cada etapa de su institución
            </span>
          </h1>
          <p class="text-xl text-slate-400 max-w-2xl mx-auto mb-4">
            Somos los únicos en el mercado colombiano con precios publicados. Sin negociaciones eternas, sin letra pequeña.
          </p>
          <p class="text-base text-slate-500">
            Todos los planes incluyen onboarding guiado, docentes ilimitados y soporte en español.
          </p>
        </div>
      </section>

      <!-- Toggle facturación -->
      <section class="px-4 sm:px-6 lg:px-8 mb-12">
        <div class="flex items-center justify-center gap-4">
          <span :class="['text-sm font-medium transition-colors', billing === 'monthly' ? 'text-white' : 'text-slate-500']">
            Mensual
          </span>
          <button
            type="button"
            :class="[
              'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-950',
              billing === 'annual' ? 'bg-indigo-600' : 'bg-slate-700'
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
          <span :class="['text-sm font-medium transition-colors', billing === 'annual' ? 'text-white' : 'text-slate-500']">
            Anual
          </span>
          <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-xs font-medium text-emerald-400">
            <UIcon name="i-lucide-gift" class="w-3 h-3" />
            2 meses gratis
          </span>
        </div>
      </section>

      <!-- Grid de planes -->
      <section class="px-4 sm:px-6 lg:px-8 pb-20" aria-labelledby="plans-heading">
        <h2 id="plans-heading" class="sr-only">
          Planes de precios
        </h2>
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="plan in plans"
            :key="plan.name"
            :class="[
              'relative rounded-2xl border p-6 flex flex-col',
              plan.accentColor === 'indigo'
                ? 'bg-indigo-950/40 border-indigo-500/40 shadow-xl shadow-indigo-500/10'
                : 'bg-slate-900/80 border-white/10'
            ]"
          >
            <!-- Badge -->
            <div v-if="plan.badge" class="absolute -top-3 left-1/2 -translate-x-1/2">
              <span
                :class="[
                  'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold',
                  plan.badgeColor === 'indigo'
                    ? 'bg-indigo-600 text-white'
                    : 'bg-violet-600 text-white'
                ]"
              >
                {{ plan.badge }}
              </span>
            </div>

            <!-- Nombre del plan -->
            <h3 class="text-lg font-bold text-white mb-1">
              {{ plan.name }}
            </h3>
            <p class="text-sm text-slate-400 mb-4">
              {{ plan.maxStudents }}
            </p>

            <!-- Precio -->
            <div class="mb-6">
              <div v-if="plan.monthly !== null" class="flex items-end gap-1">
                <span class="text-3xl font-bold text-white">{{ displayPrice(plan) }}</span>
                <span class="text-slate-400 text-sm mb-1">/mes</span>
              </div>
              <div v-else>
                <span class="text-2xl font-bold text-white">A cotizar</span>
              </div>
              <div v-if="billing === 'annual' && plan.annualTotal" class="mt-1 text-xs text-slate-500">
                {{ plan.annualTotal }}/año
              </div>
              <div v-if="savingsLabel(plan)" class="mt-1">
                <span class="inline-flex items-center gap-1 text-xs text-emerald-400 font-medium">
                  <UIcon name="i-lucide-trending-down" class="w-3 h-3" />
                  {{ savingsLabel(plan) }}
                </span>
              </div>
            </div>

            <!-- CTA -->
            <button
              type="button"
              :class="[
                'w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors duration-200 mb-6',
                plan.ctaStyle === 'filled'
                  ? 'bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/20'
                  : 'border border-white/20 hover:bg-white/10 text-white'
              ]"
              @click="showDemo = true"
            >
              {{ plan.cta }}
            </button>

            <!-- Features -->
            <ul class="space-y-2.5 flex-1">
              <li
                v-for="feature in plan.features"
                :key="feature.label"
                class="flex items-start gap-2.5 text-sm"
              >
                <UIcon
                  :name="feature.included ? 'i-lucide-check' : 'i-lucide-x'"
                  :class="[
                    'w-4 h-4 mt-0.5 flex-shrink-0',
                    feature.included ? 'text-emerald-400' : 'text-slate-600'
                  ]"
                />
                <span :class="feature.included ? 'text-slate-300' : 'text-slate-600'">
                  {{ feature.label }}
                </span>
              </li>
            </ul>
          </div>
        </div>
      </section>

      <!-- ROI -->
      <section class="px-4 sm:px-6 lg:px-8 py-20" aria-labelledby="roi-heading">
        <div class="max-w-7xl mx-auto">
          <div class="text-center mb-12">
            <h2 id="roi-heading" class="text-3xl font-bold text-white mb-4">
              El costo se paga solo
            </h2>
            <p class="text-slate-400 max-w-2xl mx-auto">
              Ejemplos reales de instituciones colombianas que miden el retorno de Aula360 en semanas, no en años.
            </p>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
              v-for="roi in roiCases"
              :key="roi.title"
              class="bg-slate-900/80 border border-white/10 rounded-2xl p-6"
            >
              <div class="flex items-center gap-3 mb-5">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center">
                  <UIcon :name="roi.icon" class="w-5 h-5 text-indigo-400" />
                </div>
                <h3 class="text-white font-semibold">
                  {{ roi.title }}
                </h3>
              </div>
              <div class="flex items-center gap-4 mb-4">
                <div class="flex-1 rounded-xl bg-red-500/10 border border-red-500/20 p-3 text-center">
                  <p class="text-xs text-slate-500 mb-1">
                    Antes
                  </p>
                  <p class="text-lg font-bold text-red-400">
                    {{ roi.before }}
                  </p>
                </div>
                <UIcon name="i-lucide-arrow-right" class="w-5 h-5 text-slate-600 flex-shrink-0" />
                <div class="flex-1 rounded-xl bg-emerald-500/10 border border-emerald-500/20 p-3 text-center">
                  <p class="text-xs text-slate-500 mb-1">
                    Con Aula360
                  </p>
                  <p class="text-lg font-bold text-emerald-400">
                    {{ roi.after }}
                  </p>
                </div>
              </div>
              <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-500/10 border border-indigo-500/20 mb-3">
                <UIcon name="i-lucide-zap" class="w-4 h-4 text-indigo-400" />
                <span class="text-sm font-semibold text-indigo-300">{{ roi.saving }}</span>
              </div>
              <p class="text-sm text-slate-400">
                {{ roi.detail }}
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- Comparativa vs competencia -->
      <section class="px-4 sm:px-6 lg:px-8 py-20 border-t border-white/[0.06]" aria-labelledby="compare-heading">
        <div class="max-w-3xl mx-auto">
          <div class="text-center mb-10">
            <h2 id="compare-heading" class="text-3xl font-bold text-white mb-4">
              ¿Por qué Aula360 y no otro?
            </h2>
            <p class="text-slate-400">
              No es una comparativa de features — es una comparativa de filosofía de producto.
            </p>
          </div>
          <div class="bg-slate-900/80 border border-white/10 rounded-2xl overflow-hidden">
            <div class="grid grid-cols-3 bg-slate-800/50 border-b border-white/[0.06] px-6 py-3">
              <span class="text-xs text-slate-500 font-medium uppercase tracking-wider" />
              <span class="text-xs text-center text-indigo-400 font-semibold uppercase tracking-wider">Aula360</span>
              <span class="text-xs text-center text-slate-500 font-medium uppercase tracking-wider">Alternativas típicas</span>
            </div>
            <div
              v-for="(row, i) in [
                ['Precios publicados', true, false],
                ['Precio por institución (no por usuario)', true, false],
                ['Diseñado para el SIEE colombiano', true, false],
                ['IA para riesgo estudiantil', true, false],
                ['Portal de padres incluido', true, false],
                ['Onboarding en 48h', true, false],
                ['Soporte en español colombiano', true, false]
              ]"
              :key="row[0] as string"
              :class="['grid grid-cols-3 items-center px-6 py-4', i % 2 === 0 ? '' : 'bg-slate-800/20']"
            >
              <span class="text-sm text-slate-300">{{ row[0] }}</span>
              <div class="flex justify-center">
                <UIcon name="i-lucide-check-circle-2" class="w-5 h-5 text-emerald-400" />
              </div>
              <div class="flex justify-center">
                <UIcon name="i-lucide-x-circle" class="w-5 h-5 text-red-400/60" />
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- FAQ -->
      <section class="px-4 sm:px-6 lg:px-8 py-20 border-t border-white/[0.06]" aria-labelledby="faq-heading">
        <div class="max-w-3xl mx-auto">
          <div class="text-center mb-10">
            <h2 id="faq-heading" class="text-3xl font-bold text-white mb-4">
              Preguntas frecuentes
            </h2>
          </div>
          <div class="space-y-3">
            <div
              v-for="(faq, i) in faqs"
              :key="faq.q"
              class="bg-slate-900/80 border border-white/10 rounded-xl overflow-hidden"
            >
              <button
                type="button"
                class="w-full flex items-center justify-between gap-4 px-6 py-4 text-left"
                :aria-expanded="openFaq === i"
                @click="toggleFaq(i)"
              >
                <span class="text-white font-medium text-sm sm:text-base">{{ faq.q }}</span>
                <UIcon
                  :name="openFaq === i ? 'i-lucide-chevron-up' : 'i-lucide-chevron-down'"
                  class="w-4 h-4 text-slate-400 flex-shrink-0 transition-transform duration-200"
                />
              </button>
              <div v-show="openFaq === i" class="px-6 pb-4">
                <p class="text-slate-400 text-sm leading-relaxed">
                  {{ faq.a }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA final -->
      <section class="relative px-4 sm:px-6 lg:px-8 py-24 overflow-hidden border-t border-white/[0.06]">
        <div aria-hidden="true" class="absolute inset-0 flex items-center justify-center pointer-events-none">
          <div class="w-[600px] h-[300px] rounded-full bg-indigo-600/15 blur-[100px]" />
        </div>
        <div class="relative max-w-3xl mx-auto text-center">
          <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
            ¿Listo para simplificar la gestión de su institución?
          </h2>
          <p class="text-slate-400 mb-8 max-w-xl mx-auto">
            Agende una demo de 30 minutos con nuestro equipo. Le mostramos Aula360 con datos reales de su institución, sin compromiso.
          </p>
          <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <button
              type="button"
              class="inline-flex items-center gap-2 px-8 py-3.5 text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl shadow-xl shadow-indigo-600/40 hover:-translate-y-0.5 transition-all duration-200"
              @click="showDemo = true"
            >
              <UIcon name="i-lucide-calendar" class="w-5 h-5" />
              Agendar demo gratuita
            </button>
            <a href="/#modulos" class="inline-flex items-center gap-2 px-8 py-3.5 text-base font-medium text-white border border-white/20 hover:bg-white/10 rounded-xl transition-colors duration-200">
              Ver funcionalidades
            </a>
          </div>
        </div>
      </section>
    </main>

    <!-- Footer -->
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
        <div class="flex flex-col items-center md:items-end gap-1">
          <div class="flex items-center gap-4">
            <a href="/pricing" class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors">Precios</a>
            <span class="text-slate-700">·</span>
            <a href="/#contacto" class="text-sm text-slate-500 hover:text-slate-400 transition-colors">Contacto</a>
            <span class="text-slate-700">·</span>
            <span class="text-slate-600 text-sm">© {{ new Date().getFullYear() }} Aula360.</span>
          </div>
          <p class="text-slate-700 text-xs">
            Desarrollado por
            <a
              href="https://mauriciomontoya.co"
              target="_blank"
              rel="noopener noreferrer"
              class="text-slate-500 hover:text-slate-300 transition-colors"
            >Mauricio Montoya</a>
          </p>
        </div>
      </div>
    </footer>

    <DemoFormModal v-model="showDemo" />
  </div>
</template>
