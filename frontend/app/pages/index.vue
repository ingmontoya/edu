<script setup lang="ts">
definePageMeta({
  layout: false
})

// SEO Meta Tags
useSeoMeta({
  title: 'Aula360 - Sistema de Gestión Académica para Colegios en Colombia',
  description: 'Plataforma integral de gestión académica para instituciones educativas colombianas. Administra estudiantes, docentes, calificaciones, asistencia y genera boletines. Cumple con SIEE.',
  keywords: 'gestión académica, software educativo, sistema escolar, boletines escolares, control de asistencia, notas escolares, SIEE Colombia, plataforma educativa, colegio, institución educativa',
  author: 'Aula360',
  robots: 'index, follow',
  ogTitle: 'Aula360 - Sistema de Gestión Académica para Colegios',
  ogDescription: 'Plataforma integral para administrar estudiantes, docentes, calificaciones, asistencia y boletines. Diseñada para instituciones educativas colombianas.',
  ogType: 'website',
  ogImage: '/og-image.png',
  ogUrl: 'https://aula360.com',
  ogLocale: 'es_CO',
  ogSiteName: 'Aula360',
  twitterCard: 'summary_large_image',
  twitterTitle: 'Aula360 - Gestión Académica para Colegios',
  twitterDescription: 'Sistema completo para instituciones educativas: estudiantes, notas, asistencia, boletines PDF y más. Cumple con SIEE colombiano.',
  twitterImage: '/og-image.png'
})

// Structured Data (JSON-LD)
useHead({
  htmlAttrs: {
    lang: 'es-CO'
  },
  link: [
    { rel: 'canonical', href: 'https://aula360.com' }
  ],
  script: [
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'SoftwareApplication',
        name: 'Aula360',
        applicationCategory: 'EducationalApplication',
        operatingSystem: 'Web',
        description: 'Sistema de gestión académica integral para instituciones educativas colombianas. Gestión de estudiantes, docentes, calificaciones, asistencia y generación de boletines.',
        offers: {
          '@type': 'Offer',
          price: '0',
          priceCurrency: 'COP'
        },
        aggregateRating: {
          '@type': 'AggregateRating',
          ratingValue: '4.8',
          ratingCount: '150'
        },
        featureList: [
          'Gestión de estudiantes y docentes',
          'Registro de calificaciones',
          'Control de asistencia',
          'Generación de boletines PDF',
          'Portal para padres de familia',
          'Cumplimiento SIEE colombiano',
          'Escala de valoración nacional'
        ]
      })
    },
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'Organization',
        name: 'Aula360',
        url: 'https://aula360.com',
        logo: 'https://aula360.com/logo.svg',
        description: 'Plataforma de gestión académica para instituciones educativas en Colombia',
        address: {
          '@type': 'PostalAddress',
          addressCountry: 'CO'
        },
        sameAs: []
      })
    },
    {
      type: 'application/ld+json',
      innerHTML: JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'FAQPage',
        mainEntity: [
          {
            '@type': 'Question',
            name: '¿Qué es Aula360?',
            acceptedAnswer: {
              '@type': 'Answer',
              text: 'Aula360 es un sistema de gestión académica moderno y completo que permite a las instituciones educativas administrar de manera eficiente todos sus procesos académicos: desde el registro de estudiantes hasta la generación de boletines.'
            }
          },
          {
            '@type': 'Question',
            name: '¿Aula360 cumple con el SIEE colombiano?',
            acceptedAnswer: {
              '@type': 'Answer',
              text: 'Sí, Aula360 está diseñado especialmente para instituciones educativas colombianas y cumple con los estándares del Sistema Institucional de Evaluación de Estudiantes (SIEE), permitiendo evaluar por logros cognitivos, procedimentales y actitudinales.'
            }
          },
          {
            '@type': 'Question',
            name: '¿Qué escala de calificaciones usa Aula360?',
            acceptedAnswer: {
              '@type': 'Answer',
              text: 'Aula360 utiliza la escala de valoración colombiana: Superior (4.6-5.0), Alto (4.0-4.5), Básico (3.0-3.9) y Bajo (1.0-2.9).'
            }
          }
        ]
      })
    }
  ]
})

const modules = [
  {
    icon: 'i-lucide-calendar-days',
    title: 'Gestión Académica',
    description: 'Años académicos, períodos, grados, grupos y asignaturas organizados de forma eficiente.'
  },
  {
    icon: 'i-lucide-users',
    title: 'Gestión de Estudiantes',
    description: 'Registro completo con datos personales, familiares, historial académico y vinculación con acudientes.'
  },
  {
    icon: 'i-lucide-briefcase',
    title: 'Gestión de Docentes',
    description: 'Información profesional, asignación a grupos y asignaturas, seguimiento de carga académica.'
  },
  {
    icon: 'i-lucide-file-check',
    title: 'Registro de Calificaciones',
    description: 'Sistema de evaluación colombiano con cálculo automático de promedios ponderados y ranking.'
  },
  {
    icon: 'i-lucide-clipboard-check',
    title: 'Control de Asistencia',
    description: 'Seguimiento diario con reportes de porcentaje y alertas de baja asistencia.'
  },
  {
    icon: 'i-lucide-file-text',
    title: 'Boletines y Reportes',
    description: 'Generación automática de boletines PDF individuales y masivos con logo institucional.'
  },
  {
    icon: 'i-lucide-target',
    title: 'SIEE - Logros',
    description: 'Evaluación por logros cognitivos, procedimentales y actitudinales según normativa.'
  },
  {
    icon: 'i-lucide-refresh-cw',
    title: 'Actividades de Nivelación',
    description: 'Recuperación, refuerzo y nivelación para estudiantes con dificultades.'
  },
  {
    icon: 'i-lucide-home',
    title: 'Portal de Padres',
    description: 'Acceso exclusivo para acudientes: notas, asistencia, boletines y anuncios.'
  }
]

const benefitsInstitution = [
  'Centraliza toda la información académica en un solo lugar',
  'Reduce tiempos administrativos en un 70%',
  'Genera reportes y estadísticas en tiempo real',
  'Elimina el papeleo y los procesos manuales'
]

const benefitsTeachers = [
  'Registro de notas rápido y sencillo',
  'Control de asistencia digital',
  'Seguimiento del progreso de cada estudiante',
  'Identificación automática de estudiantes en riesgo'
]

const benefitsParents = [
  'Acceso 24/7 a las notas de sus hijos',
  'Descarga de boletines en PDF',
  'Consulta del historial de asistencia',
  'Comunicación directa con la institución'
]

const roles = [
  { role: 'Administrador', access: 'Control total del sistema', icon: 'i-lucide-shield' },
  { role: 'Coordinador', access: 'Gestión académica completa', icon: 'i-lucide-settings' },
  { role: 'Docente', access: 'Sus grupos y asignaturas', icon: 'i-lucide-book-open' },
  { role: 'Acudiente', access: 'Solo información de sus hijos', icon: 'i-lucide-user' }
]

const comparison = [
  { feature: 'Escala colombiana', aula360: true, others: 'Limitado' },
  { feature: 'SIEE integrado', aula360: true, others: false },
  { feature: 'Portal de padres', aula360: true, others: 'Adicional' },
  { feature: 'Boletines PDF', aula360: true, others: 'Manual' },
  { feature: 'Multi-institución', aula360: true, others: false },
  { feature: 'Fácil de usar', aula360: true, others: 'Complejo' },
  { feature: 'Soporte en español', aula360: true, others: 'Variable' }
]

const gradeScale = [
  { level: 'Superior', range: '4.6 - 5.0', indicator: 'Excelente', color: 'text-green-600 dark:text-green-400', bg: 'bg-green-100 dark:bg-green-900/30' },
  { level: 'Alto', range: '4.0 - 4.5', indicator: 'Bueno', color: 'text-blue-600 dark:text-blue-400', bg: 'bg-blue-100 dark:bg-blue-900/30' },
  { level: 'Básico', range: '3.0 - 3.9', indicator: 'Aceptable', color: 'text-yellow-600 dark:text-yellow-400', bg: 'bg-yellow-100 dark:bg-yellow-900/30' },
  { level: 'Bajo', range: '1.0 - 2.9', indicator: 'Reprobado', color: 'text-red-600 dark:text-red-400', bg: 'bg-red-100 dark:bg-red-900/30' }
]

const steps = [
  { step: 1, title: 'Configuración', description: 'Configuramos el sistema con los datos de tu institución, logo y estructura académica.' },
  { step: 2, title: 'Capacitación', description: 'Entrenamos a coordinadores y docentes en el uso de la plataforma.' },
  { step: 3, title: 'Migración', description: 'Importamos los datos existentes de estudiantes y docentes.' },
  { step: 4, title: 'Operación', description: 'La institución comienza a usar el sistema con soporte continuo.' }
]
</script>

<template>
  <div class="min-h-screen bg-white dark:bg-gray-950">
    <!-- Header / Navbar -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-gray-950/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-800">
      <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" aria-label="Navegación principal">
        <div class="flex items-center justify-between h-16">
          <a href="/" class="flex items-center gap-3" aria-label="Aula360 - Ir al inicio">
            <img src="/logo.svg" alt="Logo de Aula360" class="h-8 w-8" width="32" height="32" />
            <span class="text-xl font-bold text-gray-900 dark:text-white" aria-hidden="true">Aula<span class="text-primary-600 dark:text-primary-400">360</span></span>
          </a>
          <div class="hidden md:flex items-center gap-6" role="navigation" aria-label="Secciones de la página">
            <a href="#modulos" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Módulos</a>
            <a href="#beneficios" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Beneficios</a>
            <a href="#siee" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">SIEE</a>
            <a href="#comparativa" class="text-sm text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Comparativa</a>
          </div>
          <div class="flex items-center gap-4">
            <UButton to="/login" color="neutral" variant="ghost" aria-label="Ingresar al sistema">
              Ingresar
            </UButton>
            <UButton to="/login" color="primary" aria-label="Comenzar a usar Aula360">
              Comenzar
            </UButton>
          </div>
        </div>
      </nav>
    </header>

    <main>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8" aria-labelledby="hero-title">
      <div class="max-w-7xl mx-auto">
        <div class="text-center max-w-4xl mx-auto">
          <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 text-sm font-medium mb-6">
            <UIcon name="i-lucide-award" class="size-4" aria-hidden="true" />
            <span>Cumple con el SIEE colombiano</span>
          </div>
          <h1 id="hero-title" class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
            La plataforma integral para la
            <span class="text-primary-600 dark:text-primary-400">gestión académica</span>
            de tu institución
          </h1>
          <p class="text-xl text-gray-600 dark:text-gray-400 mb-10 max-w-3xl mx-auto">
            Sistema moderno y completo que permite administrar de manera eficiente todos los procesos académicos: desde el registro de estudiantes hasta la generación de boletines.
          </p>
          <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <UButton to="/login" color="primary" size="xl">
              <UIcon name="i-lucide-arrow-right" class="size-5 mr-2" />
              Ingresar al Sistema
            </UButton>
            <UButton href="#modulos" color="neutral" variant="outline" size="xl">
              Conocer Módulos
            </UButton>
          </div>
        </div>

        <!-- Hero Stats -->
        <div class="mt-16 relative">
          <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-gray-950 to-transparent z-10 h-32 bottom-0 top-auto" />
          <div class="bg-gradient-to-br from-primary-100 to-primary-50 dark:from-primary-900/20 dark:to-gray-900 rounded-2xl p-8 shadow-2xl shadow-primary-500/10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                  <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <UIcon name="i-lucide-users" class="size-5 text-blue-600 dark:text-blue-400" />
                  </div>
                  <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Estudiantes</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">1,234</p>
              </div>
              <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                  <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                    <UIcon name="i-lucide-briefcase" class="size-5 text-green-600 dark:text-green-400" />
                  </div>
                  <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Docentes</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">48</p>
              </div>
              <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                  <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                    <UIcon name="i-lucide-layout-grid" class="size-5 text-purple-600 dark:text-purple-400" />
                  </div>
                  <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Grupos</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">36</p>
              </div>
              <div class="bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm">
                <div class="flex items-center gap-3 mb-3">
                  <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                    <UIcon name="i-lucide-percent" class="size-5 text-orange-600 dark:text-orange-400" />
                  </div>
                  <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Reducción Admin</span>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">70%</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Modules Section -->
    <section id="modulos" class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900" aria-labelledby="modulos-title">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
          <h2 id="modulos-title" class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-4">
            9 Módulos para una Gestión Completa
          </h2>
          <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Todo lo que necesitas para administrar tu institución educativa en una sola plataforma.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" role="list">
          <article
            v-for="module in modules"
            :key="module.title"
            class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-lg transition-shadow duration-300"
            role="listitem"
          >
            <div class="p-3 bg-primary-100 dark:bg-primary-900/30 rounded-xl w-fit mb-4" aria-hidden="true">
              <UIcon :name="module.icon" class="size-6 text-primary-600 dark:text-primary-400" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
              {{ module.title }}
            </h3>
            <p class="text-gray-600 dark:text-gray-400">
              {{ module.description }}
            </p>
          </article>
        </div>
      </div>
    </section>

    <!-- Benefits Section -->
    <section id="beneficios" class="py-20 px-4 sm:px-6 lg:px-8" aria-labelledby="beneficios-title">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
          <h2 id="beneficios-title" class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Beneficios para Todos
          </h2>
          <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Diseñado para facilitar el trabajo de toda la comunidad educativa.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Institution Benefits -->
          <article class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-8 text-white">
            <div class="flex items-center gap-3 mb-6">
              <div class="p-3 bg-white/20 rounded-xl" aria-hidden="true">
                <UIcon name="i-lucide-building-2" class="size-6" />
              </div>
              <h3 class="text-xl font-bold">Para la Institución</h3>
            </div>
            <ul class="space-y-3" role="list">
              <li v-for="benefit in benefitsInstitution" :key="benefit" class="flex items-start gap-3">
                <UIcon name="i-lucide-check-circle" class="size-5 shrink-0 mt-0.5 text-blue-200" aria-hidden="true" />
                <span>{{ benefit }}</span>
              </li>
            </ul>
          </article>

          <!-- Teacher Benefits -->
          <article class="bg-gradient-to-br from-green-600 to-green-700 rounded-2xl p-8 text-white">
            <div class="flex items-center gap-3 mb-6">
              <div class="p-3 bg-white/20 rounded-xl" aria-hidden="true">
                <UIcon name="i-lucide-book-open" class="size-6" />
              </div>
              <h3 class="text-xl font-bold">Para los Docentes</h3>
            </div>
            <ul class="space-y-3" role="list">
              <li v-for="benefit in benefitsTeachers" :key="benefit" class="flex items-start gap-3">
                <UIcon name="i-lucide-check-circle" class="size-5 shrink-0 mt-0.5 text-green-200" aria-hidden="true" />
                <span>{{ benefit }}</span>
              </li>
            </ul>
          </article>

          <!-- Parent Benefits -->
          <article class="bg-gradient-to-br from-purple-600 to-purple-700 rounded-2xl p-8 text-white">
            <div class="flex items-center gap-3 mb-6">
              <div class="p-3 bg-white/20 rounded-xl" aria-hidden="true">
                <UIcon name="i-lucide-heart" class="size-6" />
              </div>
              <h3 class="text-xl font-bold">Para los Padres</h3>
            </div>
            <ul class="space-y-3" role="list">
              <li v-for="benefit in benefitsParents" :key="benefit" class="flex items-start gap-3">
                <UIcon name="i-lucide-check-circle" class="size-5 shrink-0 mt-0.5 text-purple-200" aria-hidden="true" />
                <span>{{ benefit }}</span>
              </li>
            </ul>
          </article>
        </div>
      </div>
    </section>

    <!-- SIEE Section -->
    <section id="siee" class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900" aria-labelledby="siee-title">
      <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
          <article>
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400 text-sm font-medium mb-6">
              <UIcon name="i-lucide-award" class="size-4" aria-hidden="true" />
              <span>Normativa Colombiana</span>
            </div>
            <h2 id="siee-title" class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-6">
              SIEE - Sistema Institucional de Evaluación
            </h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
              Cumplimos con los estándares del Sistema Institucional de Evaluación de Estudiantes, permitiendo evaluar por logros cognitivos, procedimentales y actitudinales.
            </p>

            <div class="space-y-4 mb-8">
              <div class="flex items-center gap-3">
                <div class="p-2 bg-primary-100 dark:bg-primary-900/30 rounded-lg">
                  <UIcon name="i-lucide-brain" class="size-5 text-primary-600 dark:text-primary-400" />
                </div>
                <div>
                  <span class="font-semibold text-gray-900 dark:text-white">Cognitivos</span>
                  <span class="text-gray-600 dark:text-gray-400"> - Conocimiento</span>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <div class="p-2 bg-primary-100 dark:bg-primary-900/30 rounded-lg">
                  <UIcon name="i-lucide-wrench" class="size-5 text-primary-600 dark:text-primary-400" />
                </div>
                <div>
                  <span class="font-semibold text-gray-900 dark:text-white">Procedimentales</span>
                  <span class="text-gray-600 dark:text-gray-400"> - Habilidades</span>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <div class="p-2 bg-primary-100 dark:bg-primary-900/30 rounded-lg">
                  <UIcon name="i-lucide-heart-handshake" class="size-5 text-primary-600 dark:text-primary-400" />
                </div>
                <div>
                  <span class="font-semibold text-gray-900 dark:text-white">Actitudinales</span>
                  <span class="text-gray-600 dark:text-gray-400"> - Comportamiento</span>
                </div>
              </div>
            </div>
          </article>

          <!-- Grading Scale -->
          <aside class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-lg" aria-labelledby="escala-title">
            <h3 id="escala-title" class="text-xl font-bold text-gray-900 dark:text-white mb-6">
              Escala de Valoración Colombiana
            </h3>
            <div class="space-y-4">
              <div
                v-for="grade in gradeScale"
                :key="grade.level"
                class="flex items-center gap-4 p-4 rounded-xl"
                :class="grade.bg"
              >
                <div class="w-24">
                  <span class="font-bold" :class="grade.color">{{ grade.level }}</span>
                </div>
                <div class="w-24 text-gray-700 dark:text-gray-300 font-mono">
                  {{ grade.range }}
                </div>
                <div class="text-gray-600 dark:text-gray-400">
                  {{ grade.indicator }}
                </div>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </section>

    <!-- User Roles Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8" aria-labelledby="roles-title">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
          <h2 id="roles-title" class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Roles de Usuario
          </h2>
          <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Acceso seguro basado en roles con permisos específicos para cada tipo de usuario.
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" role="list">
          <article
            v-for="item in roles"
            :key="item.role"
            class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm text-center hover:shadow-lg transition-shadow duration-300"
            role="listitem"
          >
            <div class="p-4 bg-primary-100 dark:bg-primary-900/30 rounded-full w-fit mx-auto mb-4" aria-hidden="true">
              <UIcon :name="item.icon" class="size-8 text-primary-600 dark:text-primary-400" />
            </div>
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
              {{ item.role }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              {{ item.access }}
            </p>
          </article>
        </div>
      </div>
    </section>

    <!-- Comparison Section -->
    <section id="comparativa" class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900" aria-labelledby="comparativa-title">
      <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16">
          <h2 id="comparativa-title" class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-4">
            ¿Por qué elegir Aula360?
          </h2>
          <p class="text-lg text-gray-600 dark:text-gray-400">
            Comparativa con otros sistemas del mercado.
          </p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
          <table class="w-full" aria-label="Comparativa de características entre Aula360 y otros sistemas">
            <thead>
              <tr class="bg-gray-50 dark:bg-gray-700">
                <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Característica</th>
                <th scope="col" class="px-6 py-4 text-center text-sm font-semibold text-primary-600 dark:text-primary-400">Aula360</th>
                <th scope="col" class="px-6 py-4 text-center text-sm font-semibold text-gray-500 dark:text-gray-400">Otros</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="item in comparison" :key="item.feature">
                <th scope="row" class="px-6 py-4 text-sm text-gray-900 dark:text-white font-normal text-left">{{ item.feature }}</th>
                <td class="px-6 py-4 text-center">
                  <UIcon v-if="item.aula360 === true" name="i-lucide-check-circle" class="size-5 text-green-500 mx-auto" aria-hidden="true" />
                  <span v-if="item.aula360 === true" class="sr-only">Sí disponible</span>
                  <span v-else class="text-sm text-gray-600 dark:text-gray-400">{{ item.aula360 }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                  <UIcon v-if="item.others === false" name="i-lucide-x-circle" class="size-5 text-red-500 mx-auto" aria-hidden="true" />
                  <span v-if="item.others === false" class="sr-only">No disponible</span>
                  <span v-else class="text-sm text-gray-500 dark:text-gray-400">{{ item.others }}</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- Implementation Steps Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8" aria-labelledby="implementacion-title">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
          <h2 id="implementacion-title" class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Implementación Sencilla
          </h2>
          <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
            Te acompañamos en cada paso para que tu institución comience a usar Aula360.
          </p>
        </div>

        <ol class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 list-none p-0">
          <li
            v-for="item in steps"
            :key="item.step"
            class="relative"
          >
            <article class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm h-full">
              <div class="flex items-center justify-center w-12 h-12 rounded-full bg-primary-600 text-white font-bold text-xl mb-4" aria-hidden="true">
                {{ item.step }}
              </div>
              <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                <span class="sr-only">Paso {{ item.step }}: </span>{{ item.title }}
              </h3>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ item.description }}
              </p>
            </article>
            <div v-if="item.step < 4" class="hidden lg:block absolute top-1/2 -right-4 transform -translate-y-1/2" aria-hidden="true">
              <UIcon name="i-lucide-chevron-right" class="size-8 text-gray-300 dark:text-gray-600" />
            </div>
          </li>
        </ol>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-primary-600 to-primary-700" aria-labelledby="cta-title">
      <div class="max-w-4xl mx-auto text-center">
        <h2 id="cta-title" class="text-3xl sm:text-4xl font-bold text-white mb-6">
          Transforma la gestión académica de tu institución
        </h2>
        <p class="text-lg text-primary-100 mb-10 max-w-2xl mx-auto">
          Únete a las instituciones educativas colombianas que ya confían en Aula360 para simplificar sus procesos académicos.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
          <UButton to="/login" color="neutral" size="xl" aria-label="Ingresar ahora al sistema Aula360">
            <UIcon name="i-lucide-log-in" class="size-5 mr-2" aria-hidden="true" />
            Ingresar Ahora
          </UButton>
          <UButton href="#modulos" variant="outline" size="xl" class="text-white border-white hover:bg-white/10" aria-label="Conocer más sobre los módulos de Aula360">
            Conocer Más
          </UButton>
        </div>
      </div>
    </section>
    </main>

    <!-- Footer -->
    <footer class="py-12 px-4 sm:px-6 lg:px-8 bg-gray-900" role="contentinfo">
      <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
          <a href="/" class="flex items-center gap-3" aria-label="Aula360 - Ir al inicio">
            <img src="/logo.svg" alt="Logo de Aula360" class="h-8 w-8" width="32" height="32" loading="lazy" />
            <span class="text-xl font-bold text-white">Aula<span class="text-primary-400">360</span></span>
          </a>
          <p class="text-primary-200 text-center italic">
            Transformando la educación, un colegio a la vez.
          </p>
          <p class="text-gray-400 text-sm">
            © {{ new Date().getFullYear() }} Aula360. Todos los derechos reservados.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>
