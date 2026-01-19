// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  modules: [
    '@nuxt/eslint',
    '@nuxt/ui',
    '@vueuse/nuxt',
    '@pinia/nuxt'
  ],

  devtools: {
    enabled: true
  },

  css: ['~/assets/css/main.css'],

  runtimeConfig: {
    public: {
      siteUrl: process.env.NUXT_PUBLIC_SITE_URL || 'http://localhost:3000',
      apiUrl: process.env.NUXT_PUBLIC_API_URL || 'http://localhost:9090/api'
    }
  },

  routeRules: {
    '/api/**': {
      proxy: { to: 'http://localhost:9090/api/**' }
    }
  },

  compatibilityDate: '2024-07-11',

  app: {
    head: {
      title: 'App Template',
      meta: [
        { name: 'description', content: 'Laravel + Nuxt Full-Stack Template' }
      ]
    }
  },

  imports: {
    dirs: ['stores']
  },

  eslint: {
    config: {
      stylistic: {
        commaDangle: 'never',
        braceStyle: '1tbs'
      }
    }
  }
})
