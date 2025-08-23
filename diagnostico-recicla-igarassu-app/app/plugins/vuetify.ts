import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import 'vuetify/styles'
import { aliases, mdi } from 'vuetify/iconsets/mdi'
import { VMaskInput } from 'vuetify/labs/VMaskInput'

const myCustomTheme = {
  dark: false,
  colors: {
    background: '#E0F2F1',
    surface: '#F1F8F6',
    primary: '#00695C',
    'primary-darken-1': '#004D40',
    secondary: '#FFFFFFF',
    'secondary-darken-1': '#E65100',
    error: '#B00020',
    info: '#2196F3',
    success: '#4CAF50',
    warning: '#FB8C00',
  },
}

export default defineNuxtPlugin((nuxtApp) => {
  const vuetify = createVuetify({
    icons: {
      defaultSet: 'mdi',
      aliases,
      sets: {
        mdi,
      },
    },
    theme: {
      defaultTheme: 'myCustomTheme',
      themes: {
        myCustomTheme,
      },
    },
    defaults: {
        VTextField: {
            class: 'rounded-pill',
            variant: 'outlined',
        },
        VSelect: {
            class: 'rounded-pill',
        },
    },
    components: {
      ...components,
       VMaskInput
    },
    directives,
    ssr: true,
  })

  nuxtApp.vueApp.use(vuetify)
})