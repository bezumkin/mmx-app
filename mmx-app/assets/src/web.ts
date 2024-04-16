// @ts-nocheck
import {createApp} from 'vue'
import {createMmxToast} from '@vesp/mmx-frontend/toast'
import {setNamespace} from '@vesp/mmx-frontend/namespace'
import App from './web/app.vue'

import './web/scss/index.scss'

document.addEventListener('DOMContentLoaded', () => {
  const appRoot = document.getElementById('mmx-app-root')
  if (appRoot) {
    setNamespace('mmx-app')
    createApp(App).use(createMmxToast()).mount(appRoot)
  }
})
