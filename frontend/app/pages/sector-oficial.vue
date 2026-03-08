<script setup lang="ts">
definePageMeta({ layout: false })

useSeoMeta({
  title: 'Aula360 para el Sector Oficial — Colegios Públicos Colombianos',
  description: 'Software de gestión académica para colegios públicos colombianos. SIEE, SIMAT, IA y boletines PDF. Contratación adaptable a secretarías de educación.',
  keywords: 'software colegio público colombia, gestión académica sector oficial colombia, plataforma educativa instituciones oficiales, SIMAT MEN exportación colegio, secretaría educación software, contratación directa software escolar colombia, SIEE colegio público, gestión académica municipios colombia, software educativo licitación pública, IA riesgo estudiantil colegios oficiales',
  author: 'Aula360',
  robots: 'index, follow',
  ogTitle: 'Aula360 para el Sector Oficial — Colegios Públicos Colombianos',
  ogDescription: 'Software de gestión académica para colegios públicos. SIEE, SIMAT, IA y boletines PDF. Contratación adaptable a secretarías de educación de Colombia.',
  ogType: 'website',
  ogImage: 'https://aula360.com/og-image.png',
  ogImageWidth: '1200',
  ogImageHeight: '630',
  ogImageAlt: 'Aula360 para el sector oficial — Software de gestión académica para colegios públicos colombianos',
  ogUrl: 'https://aula360.com/sector-oficial',
  ogLocale: 'es_CO',
  ogLocaleAlternate: ['es_ES', 'es_419'],
  ogSiteName: 'Aula360',
  twitterCard: 'summary_large_image',
  twitterSite: '@aula360co',
  twitterCreator: '@aula360co',
  twitterTitle: 'Aula360 para el Sector Oficial — Colegios Públicos Colombianos',
  twitterDescription: 'La misma tecnología del sector privado, adaptada a secretarías de educación, vigencias fiscales y procesos de contratación pública.',
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
    { rel: 'canonical', href: 'https://aula360.com/sector-oficial' },
    { rel: 'sitemap', type: 'application/xml', href: 'https://aula360.com/sitemap.xml' }
  ],
  script: [
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'WebPage',
        'name': 'Aula360 para el Sector Oficial',
        'url': 'https://aula360.com/sector-oficial',
        'description': 'Plataforma de gestión académica para instituciones educativas oficiales en Colombia. Adaptada a secretarías de educación, vigencias fiscales y procesos de contratación pública.',
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
          { '@type': 'ListItem', 'position': 2, 'name': 'Sector Oficial', 'item': 'https://aula360.com/sector-oficial' }
        ]
      })
    }
  ]
})

const showDemo = ref(false)

const included = [
  { icon: 'i-lucide-graduation-cap', title: 'Todos los módulos', desc: 'Calificaciones, asistencia, SIEE, boletines PDF, portal de padres, convivencia y más.' },
  { icon: 'i-lucide-download', title: 'Exportación SIMAT', desc: 'Formato oficial del MEN listo para reportar al Sistema Integrado de Matrícula.' },
  { icon: 'i-lucide-target', title: 'SIEE completo', desc: 'Evaluación por logros cognitivos, procedimentales y actitudinales. Decreto 1290.' },
  { icon: 'i-lucide-brain', title: 'IA para riesgo estudiantil', desc: 'Índice multivariable que detecta estudiantes en riesgo antes de que sea demasiado tarde.' },
  { icon: 'i-lucide-users', title: 'Docentes ilimitados', desc: 'Sin costo adicional por número de docentes o administrativos.' },
  { icon: 'i-lucide-file-text', title: 'Boletines y constancias', desc: 'Generación masiva de boletines y constancias PDF con logo institucional.' }
]

const process = [
  { step: '01', title: 'Nos contactas', desc: 'Cuéntanos el nombre de tu institución, municipio, secretaría de educación y número aproximado de estudiantes.' },
  { step: '02', title: 'Propuesta en 24h', desc: 'Te enviamos una propuesta técnica y económica adaptada a tu vigencia presupuestal y proceso de contratación.' },
  { step: '03', title: 'Onboarding en 48h', desc: 'Una vez firmado el contrato, dejamos Aula360 configurado con tus grados, grupos y planta docente en 48 horas.' },
  { step: '04', title: 'Acompañamiento continuo', desc: 'Capacitación presencial o virtual para docentes y coordinadores. Soporte prioritario durante todo el año escolar.' }
]
</script>

<template>
  <div class="min-h-screen bg-white" style="font-family: 'Figtree', ui-sans-serif, system-ui, sans-serif;">
    <PublicNavbar @open-demo="showDemo = true" />

    <main class="pt-16">
      <!-- HERO -->
      <section class="bg-gradient-to-br from-slate-800 to-slate-900 py-24 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-white/70 text-xs font-medium tracking-wide mb-6">
            <UIcon name="i-lucide-building-2" class="w-3.5 h-3.5" />
            Sector oficial
          </div>
          <h1 class="text-4xl sm:text-5xl font-extrabold text-white leading-tight tracking-tight mb-6">
            Aula360 para instituciones<br>
            <span class="text-emerald-400">educativas oficiales</span>
          </h1>
          <p class="text-slate-300 text-lg leading-relaxed max-w-2xl mx-auto mb-10">
            Sabemos que los colegios públicos operan con presupuestos asignados por secretarías de educación, vigencias fiscales y procesos de contratación distintos a los del sector privado. Por eso no hay un precio de lista — hablemos y construimos un esquema que funcione para su institución.
          </p>
          <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <button
              type="button"
              class="inline-flex items-center px-8 py-3.5 text-base font-bold text-slate-900 bg-white hover:bg-slate-50 rounded-xl shadow-lg hover:-translate-y-0.5 transition-all duration-200 cursor-pointer"
              @click="showDemo = true"
            >
              Solicitar información
              <UIcon name="i-lucide-arrow-right" class="size-5 ml-2" />
            </button>
            <a
              href="/como-funciona"
              class="inline-flex items-center px-8 py-3.5 text-base font-medium text-white border-2 border-white/30 hover:border-white/60 hover:bg-white/10 rounded-xl transition-all duration-200"
            >
              Ver cómo funciona
              <UIcon name="i-lucide-arrow-right" class="size-5 ml-2" />
            </a>
          </div>
        </div>
      </section>

      <!-- ENTENDEMOS EL SECTOR -->
      <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white border-b border-slate-200">
        <div class="max-w-5xl mx-auto">
          <div class="grid md:grid-cols-2 gap-16 items-center">
            <div>
              <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-4">
                Entendemos el contexto
              </p>
              <h2 class="text-3xl font-bold text-slate-900 leading-snug mb-6">
                La misma tecnología,<br> adaptada a su realidad
              </h2>
              <p class="text-slate-500 leading-relaxed mb-5">
                Un colegio oficial no puede suscribirse a un plan mensual con tarjeta de crédito. Su proceso de contratación involucra a la secretaría de educación, disponibilidad presupuestal y a veces licitación pública. Lo entendemos.
              </p>
              <p class="text-slate-500 leading-relaxed">
                Aula360 funciona exactamente igual para el sector oficial que para el privado — mismos módulos, misma IA, mismo cumplimiento normativo. Lo que varía es cómo estructuramos el contrato y los pagos.
              </p>
            </div>
            <div class="space-y-4">
              <div
                v-for="item in [
                  { label: 'Contratación directa o por licitación', icon: 'i-lucide-file-check' },
                  { label: 'Facturación a nombre de la secretaría o la institución', icon: 'i-lucide-receipt' },
                  { label: 'Pago anual o por vigencia fiscal', icon: 'i-lucide-calendar' },
                  { label: 'Carta de intención sin costo', icon: 'i-lucide-mail' }
                ]"
                :key="item.label"
                class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-200"
              >
                <div class="w-9 h-9 rounded-lg bg-white border border-slate-200 flex items-center justify-center shrink-0 shadow-sm">
                  <UIcon :name="item.icon" class="size-4 text-slate-600" />
                </div>
                <p class="text-sm font-medium text-slate-800">
                  {{ item.label }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- QUÉ INCLUYE -->
      <section class="py-20 px-4 sm:px-6 lg:px-8 bg-slate-50 border-b border-slate-200">
        <div class="max-w-5xl mx-auto">
          <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">
              Todo lo que su institución necesita
            </h2>
            <p class="text-slate-500 max-w-xl mx-auto">
              Sin módulos recortados. El sector oficial accede a la plataforma completa.
            </p>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div
              v-for="item in included"
              :key="item.title"
              class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200"
            >
              <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center mb-4">
                <UIcon :name="item.icon" class="size-5 text-slate-700" />
              </div>
              <h3 class="text-sm font-bold text-slate-900 mb-1.5">
                {{ item.title }}
              </h3>
              <p class="text-xs text-slate-500 leading-relaxed">
                {{ item.desc }}
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- PROCESO -->
      <section class="py-20 px-4 sm:px-6 lg:px-8 bg-white border-b border-slate-200">
        <div class="max-w-4xl mx-auto">
          <div class="text-center mb-14">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">
              ¿Cómo es el proceso?
            </h2>
            <p class="text-slate-500 max-w-xl mx-auto">
              Desde el primer contacto hasta que su institución está operando en Aula360.
            </p>
          </div>
          <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div v-for="step in process" :key="step.step" class="relative">
              <div class="text-4xl font-black text-slate-100 mb-3 leading-none">
                {{ step.step }}
              </div>
              <h3 class="text-sm font-bold text-slate-900 mb-2">
                {{ step.title }}
              </h3>
              <p class="text-xs text-slate-500 leading-relaxed">
                {{ step.desc }}
              </p>
            </div>
          </div>
        </div>
      </section>

      <!-- CTA -->
      <section class="py-16 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-5xl mx-auto">
          <div class="rounded-3xl px-8 py-16 sm:px-16 sm:py-20 text-center bg-gradient-to-br from-teal-100 via-cyan-50 to-emerald-100">
            <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 mb-6 leading-tight tracking-tight">
              Cuéntenos sobre<br class="hidden sm:block"> su institución
            </h2>
            <p class="text-lg text-slate-600 mb-3 max-w-xl mx-auto leading-relaxed">
              Nombre de la institución, municipio, secretaría de educación y número de estudiantes. Con eso es suficiente para preparar una propuesta.
            </p>
            <p class="text-sm text-slate-400 mb-10">
              Le respondemos en menos de 24 horas.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
              <button
                type="button"
                class="inline-flex items-center px-8 py-3.5 text-base font-bold text-white bg-slate-900 hover:bg-slate-800 rounded-xl shadow-lg hover:-translate-y-0.5 transition-all duration-200 cursor-pointer"
                @click="showDemo = true"
              >
                Solicitar propuesta
                <UIcon name="i-lucide-arrow-right" class="size-5 ml-2" />
              </button>
              <a
                href="/pricing"
                class="inline-flex items-center px-8 py-3.5 text-base font-medium text-slate-900 border-2 border-slate-900 hover:bg-slate-900/10 rounded-xl transition-all duration-200"
              >
                Ver planes privados
                <UIcon name="i-lucide-arrow-right" class="size-5 ml-2" />
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
