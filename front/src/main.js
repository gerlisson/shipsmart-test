import { createApp } from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify'
import { TheMask } from 'vue-the-mask'

const app = createApp(App)
app.directive('mask', TheMask)
app.use(vuetify).mount('#app')
