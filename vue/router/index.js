import { createWebHistory, createRouter } from 'vue-router'
import store from '../store'

import DashboardView from '../src/views/Auth/Dashboard.vue'
import DefaultLayout from '../src/components/Auth/DefaultLayout.vue'

import ClassesView from '../src/views/Auth/Classes/ClassesView.vue'
import ClassRoomView from '../src/views/Auth/Classes/ClassRoomView.vue'
import ClassView from '../src/views/Auth/Classes/ClassView.vue'
import ClassTaskView from '../src/views/Auth/Classes/ClassTaskView.vue'

import AuthLayout from '../src/views/Auth/Authorization/AuthLayout.vue'
import LoginView from '../src/views/Auth/Authorization/LoginView.vue'
import RegisterView from '../src/views/Auth/Authorization/RegisterView.vue'

import UserProfileView from '../src/views/Auth/User/UserProfile.vue'
import Profile from '../src/views/Auth/User/User.vue'

const routes = [
    {
        path: '/*',
        name: 'Index',
        redirect: '/login',
    },
    {
        path: '/',
        redirect: '/dashboard',
        meta: { requiresAuth: true },
        component: DefaultLayout,
        children: [
            {
                path: '/dashboard',
                name: 'Dashboard',
                meta: { requiresAuth: true },
                component: DashboardView,
            },
            {
                path: '/class',
                name: 'Classes',
                meta: { requiresAuth: true },
                component: ClassesView
            },
            {
                path: '/class',
                component: ClassRoomView,
                meta: { requiresAuth: true },
                children: [
                    {
                        path: '/class/:class_code',
                        name: 'ClassDetails',
                        meta: { requiresAuth: true },
                        component: ClassView
                    },
                    {
                        path: '/class/task/:id/:class_code',
                        name: 'ClassTask',
                        meta: { requiresAuth: true },
                        component: ClassTaskView
                    }
                ]
            },
            {
                path:'/profile',
                meta: { requiresAuth: true },
                redirect: '/profile/user',
                component: UserProfileView,
                children: [
                    {
                        path:'/profile/user',
                        name:'Profile',
                        component: Profile
                    }
                ]
            }
        ]
    },
    {
        path: '/login',
        name: 'Auth',
        component: AuthLayout,
        meta: { isGuest: true },
        children: [
            {
                path: '/login',
                name: 'Login',
                component: LoginView
            },
            {
                path: '/register',
                name: 'Register',
                component: RegisterView
            }
        ]
    }
]
const router = createRouter({
    history: createWebHistory(),
    routes
})

const isAuthenticated = () => {
    return store.state.isAuthenticated
}

router.beforeEach((to, from, next) => {
    if (to.matched.some(records => records.meta.requiresAuth)) {
        if (isAuthenticated() === "false" || !isAuthenticated() && store.state.data.session_id == null && store.state.data.api_token == null) {
            to.meta.isGuest = true
            next({ name: 'Login' })
            sessionStorage.clear()
            store.state.isAuthenticated = null
            store.state.data.session_id = null
            store.state.data.api_token = null
        } else {
            next()
        }
    } else if (to.matched.some(records => records.meta.isGuest)) {
        if (isAuthenticated() === "true" && store.state.data.session_id != "") {
            to.meta.isGuest = false
            next({ name: 'Dashboard' })
            sessionStorage.clear()
            store.state.isAuthenticated = null
            store.state.data.session_id = null
            store.state.data.api_token = null
        } else {
            next()
        }
    } else if (!to.matched.some(records => records.path)) {
        if (isAuthenticated() === "true" && store.state.data.session_id != "" && store.state.data.api_token != "") {
            to.meta.isGuest = false
            next({ name: 'Dashboard' })
            sessionStorage.clear()
            store.state.isAuthenticated = null
            store.state.data.session_id = null
            store.state.data.api_token = null
        } else if (isAuthenticated() === "false" || !isAuthenticated() && store.state.data.session_id == null && store.state.data.api_token == null) {
            to.meta.isGuest = true
            next({ name: 'Login' })
            sessionStorage.clear()
            store.state.isAuthenticated = null
            store.state.data.session_id = null
            store.state.data.api_token = null
        } else if (router.currentRoute.value.name === "Register" || router.currentRoute.value.name === "Login") {
            next()
        } else {
            next()
        }
    } else {
        next()
    }
})
export default router