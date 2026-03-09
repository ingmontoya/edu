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
    '/api/_nuxt_icon/**': {},
    '/api/**': {
      proxy: { to: 'http://localhost:9090/api/**' }
    }
  },

  compatibilityDate: '2024-07-11',

  nitro: {
    prerender: {
      // Explicitly pre-render all app routes so nginx can serve them directly.
      // Without this, nuxt generate only crawls public pages (/, /login, etc.)
      // and authenticated routes like /dashboard never get an HTML file.
      // Without their own HTML, nginx falls back to the landing page index.html,
      // and Vue Router must re-route from there — which breaks on iOS Safari.
      routes: [
        '/login',
        '/forgot-password',
        '/dashboard',
        '/students',
        '/students/new',
        '/students/simat-export',
        '/teachers',
        '/teachers/assignments',
        '/academic/years',
        '/academic/grades',
        '/academic/groups',
        '/academic/subjects',
        '/academic/periods',
        '/grades/record',
        '/grades/worksheet',
        '/attendance',
        '/reports/cards',
        '/reports/certificates',
        '/reports/consolidation',
        '/reports/failing',
        '/reports/risk',
        '/siee/achievements',
        '/siee/achievements/record',
        '/siee/remedials',
        '/siee/remedials/new',
        '/convivencia',
        '/convivencia/new',
        '/tasks',
        '/tasks/new',
        '/guardian',
        '/guardian/tasks',
        '/settings',
        '/settings/security',
        '/institution/settings'
      ]
    }
  },

  vite: {
    build: {
      // Merge all CSS into a single render-blocking file so every pre-rendered
      // page gets the full stylesheet in one <link> request. Without this,
      // Vite splits CSS per-chunk; navigating to a pre-rendered page triggers
      // a new CSS download → flash of unstyled content before styles apply.
      cssCodeSplit: false
    }
  },

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
