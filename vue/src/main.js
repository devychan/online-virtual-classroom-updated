import { computed, createApp } from 'vue'
import App from './App.vue'

import router from '../router/index'
import store from '../store/index'
if (!store.state.isAuthenticated && router.options.history.state.current === "/login") {
    if(router.options.history.state.current !== "/login") {
        store.state.isAuthenticated = null
        store.state.data.session_id = null
        store.state.data.api_token = null
    }else{
        router.push({ name: 'Login' })
        store.dispatch('authcheck')
        sessionStorage.clear()
    }
} 

createApp(App)
    .use(store)
    .use(router)
    .mount('#app')
