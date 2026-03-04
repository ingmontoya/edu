// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  modules: [
    '@nuxt/eslint',
    '@nuxt/ui',
    '@vueuse/nuxt',
    '@pinia/nuxt'
  ],

  imports: {
    dirs: ['stores']
  },

  devtools: {
    enabled: true
  },

  app: {
    head: {
      title: 'Aula360',
      meta: [
        { name: 'description', content: 'Plataforma de gestión académica para instituciones educativas' }
      ]
    }
  },

  css: ['~/assets/css/main.css'],

  runtimeConfig: {
    public: {
      // @ts-ignore - process.env is available at build time
      siteUrl: process.env.NUXT_PUBLIC_SITE_URL || 'http://localhost:3002',
      // @ts-ignore - process.env is available at build time
      apiUrl: process.env.NUXT_PUBLIC_API_URL || 'http://localhost:9090/api'
    }
  },

  routeRules: {
    '/api/**': {
      proxy: { to: 'http://localhost:9090/api/**' }
    }
  },

  compatibilityDate: '2024-07-11',

  eslint: {
    config: {
      stylistic: {
        commaDangle: 'never',
        braceStyle: '1tbs'
      }
    }
  },

  icon: {
    serverBundle: 'remote',
    clientBundle: {
      icons: [
        'lucide:save',
        'lucide:download',
        'lucide:file-down',
        'lucide:file-text',
        'lucide:file-edit',
        'lucide:users',
        'lucide:user-plus',
        'lucide:calendar',
        'lucide:calendar-check',
        'lucide:graduation-cap',
        'lucide:layout-grid',
        'lucide:settings',
        'lucide:log-out',
        'lucide:plus',
        'lucide:pencil',
        'lucide:trash',
        'lucide:search',
        'lucide:x',
        'lucide:check',
        'lucide:chevron-down',
        'lucide:chevron-right',
        'lucide:chevron-left',
        'lucide:arrow-left',
        'lucide:menu',
        'lucide:home',
        'lucide:book',
        'lucide:book-open',
        'lucide:clipboard-list',
        'lucide:bar-chart',
        'lucide:building',
        'lucide:user',
        'lucide:mail',
        'lucide:phone',
        'lucide:map-pin',
        'lucide:id-card',
        'lucide:alert-circle',
        'lucide:info',
        'lucide:refresh-cw',
        'lucide:target',
        'lucide:panel-left-close',
        'lucide:check-square',
        'lucide:more-vertical',
        'lucide:edit',
        'lucide:trash-2',
        'lucide:clock',
        'lucide:loader-2',
        'lucide:check-circle',
        'lucide:copy',
        'lucide:upload',
        'lucide:file-spreadsheet',
        'lucide:paperclip',
        'lucide:file-up',
        'lucide:message-circle'
      ],
      scan: true
    }
  }
})
