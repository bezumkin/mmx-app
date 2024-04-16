// @ts-nocheck
import {createApp} from 'vue'
import {createRouter, createWebHashHistory} from 'vue-router'
import {createMmx} from '@vesp/mmx-frontend'
import App from './mgr/app.vue'
import routes from '~pages'

import './mgr/scss/index.scss'

document.addEventListener('DOMContentLoaded', () => {
  createApp(App)
    .use(createRouter({history: createWebHashHistory(), routes}))
    .use(createMmx({namespace: 'mmx-app'}))
    .mount('#mmx-app-root')
})
