import { createApp } from 'vue'
import { createPinia } from 'pinia'
import axios from 'axios'
import { io } from 'socket.io-client'
import PrimeVue from 'primevue/config'
import { definePreset } from '@primevue/themes';
import Aura from '@primevue/themes/aura';

import 'primeicons/primeicons.css'
import "preline/preline";
import '@/assets/base.css'

import App from './App.vue'
import router from './router'

const pinia = await createPinia()

const app = createApp(App)

app.use(pinia)
app.use(router)

const CustomPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: "#f4f7fe",
            100: "#cbdafa",
            200: "#a1bcf6",
            300: "#789ef3",
            400: "#4e81ef",
            500: "#2563eb",
            600: "#1f54c8",
            700: "#1a45a5",
            800: "#143681",
            900: "#0f285e",
            950: "#09193b"
        },
    },
    components: {
        datatable: {
            colorScheme: {
                dark: {
                    header: {
                        background: '{slate.900}',
                        color: '{surface.0}',
                        borderColor: "{surface.800}",
                    },
                    headerCell: {
                        background: "{slate.900}",
                        color: "{surface.0}",
                        borderColor: "{surface.800}",
                    },
                    bodyCell: {
                        background: "{slate.900}",
                        color: "{surface.0}",
                        borderColor: "{surface.800}",
                    },
                    row: {
                        background: '{slate.900}',
                        color: '{surface.0}',
                        borderColor: "{slate.0}",
                    },
                    footer: {
                        background: '{slate.900}',
                    }
                }
            }
        },
        paginator: {
            colorScheme: {
                dark: {
                    background: "{slate.900}",
                    color: "{surface.0}",
                    borderColor: "{surface.800}",
                }
            }
        },
        inputtext : {
            colorScheme: {
                dark: {
                    background: "{slate.950}",
                    color: "{surface.0}",
                    borderColor: "{surface.800}",
                }
            }
        },
        select : {
            colorScheme: {
                dark: {
                    background: "{slate.950}",
                    color: "{surface.0}",
                    borderColor: "{surface.800}",
                }
            }
        },
    }
});

app.use(PrimeVue, {
    theme: {
        preset: CustomPreset
    },
});

const apiDomain = import.meta.env.VITE_API_DOMAIN
const wsConnection = import.meta.env.VITE_WS_CONNECTION

console.log('api domain', apiDomain)
console.log('ws connection', wsConnection)

axios.defaults.baseURL = `http://${apiDomain}/api`

app.provide('socket', io(wsConnection))

app.provide('serverBaseUrl', apiDomain)

app.mount('#app')