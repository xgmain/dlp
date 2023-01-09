require('./bootstrap')

import { createApp } from 'vue'
import DlpIndex from './components/Dlp/'

const app = createApp({})
app.component('dlp-validation', DlpIndex)
app.mount('#app')
