// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
	compatibilityDate: '2025-07-15',
	devtools: { enabled: false },
	modules: ['@nuxt/eslint', '@vite-pwa/nuxt', '@nuxtjs/leaflet', '@pinia/nuxt'],
	pinia: {
		storesDirs: ['~/stores/**'],
	},
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
				},
			],
		},
	},
});
