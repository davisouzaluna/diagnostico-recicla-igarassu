// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  modules: ['@nuxt/eslint', '@nuxt/ui', '@vite-pwa/nuxt', '@nuxtjs/leaflet'],
     vite: {
        server: {
            allowedHosts: true,
        },
    },
  css: ['vuetify/styles', '~/assets/main.scss', '@mdi/font/css/materialdesignicons.css'],
  build: {
    transpile: ['vuetify'],
  }, 
  ssr: false,
   app: {
    head: {
      script: [
        {
          src: '/js/vlibras.js',
          tagPosition: 'bodyClose',
        }
      ]
     }
    }
})