import {createApp} from 'vue'

require('./bootstrap')
import App from './App.vue'
import axios from 'axios'
import router from './router'
import store from './store'

import { library } from '@fortawesome/fontawesome-svg-core'

import { faArrowRightFromBracket } from '@fortawesome/free-solid-svg-icons'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

import {ClientTable} from 'v-tables-3';


library.add(faArrowRightFromBracket)

const app = createApp(App)

app.component('font-awesome-icon', FontAwesomeIcon)

app.use(Toast, {
    timeout: 2000,
    position: "bottom-right"
});

app.use(ClientTable)


app.config.globalProperties.$axios = axios;
app.use(router)
app.use(store)
app.mount('#app')
