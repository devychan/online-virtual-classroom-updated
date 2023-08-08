import { createStore } from 'vuex'
import client from '../src/Axios'
import router from '../router/index'

const user = JSON.parse(sessionStorage.getItem('user'))
const auth = sessionStorage.getItem('auth')
const sideBar = JSON.parse(localStorage.getItem('sidebar'))
const store = createStore({
    state: {
        isAuthenticated: auth ? auth : false,
        sideBar:sideBar,
        data: {
            user: user ? user : {},
            api_token: null,
            session_id: null,
        },
        classes: {},
        tasks: {},
        temp: {},
        comments: {},
        dashboard:{},
        screen: {
            width: window.innerWidth,
            height: window.innerHeight
        }
    },
    getters: {},
    actions: {
        authcheck({ commit, state }) {
            return client.get('/auth/check')
                .then((response) => {
                    if (response.data.auth) {
                        state.data.api_token = response.data.token
                        sessionStorage.setItem('auth', response.data.auth)
                        sessionStorage.setItem('token', response.data.token)
                        commit('SET_AUTHORIZATION', response.data.session_id)
                        router.push({ name: 'Dashboard' })
                    } else {
                        router.push({ name: 'Login' })
                        state.data.api_token = null
                        state.isAuthenticated = false
                        sessionStorage.clear()
                    }
                })
        },
        showRoom({ commit }) {
            return client.post('/get-room')
                .then((response) => {
                    store.state.classes = response.data.result
                    return response.data.result
                }).catch((error) => {
                    console.log("Something wen't wrong!")
                })
        },
        getUser({ commit, state }) {
            return client.get('/user')
                .then((response) => {
                    store.state.data.user = response.data
                    return response.data
                })
                .catch((error) => {
                    state.isAuthenticated = null
                    sessionStorage.clear()
                    router.push({ name: 'Login' })
                })
        },
        createRoom({ commit }, data) {
            return client.post('/create-room', data)
                .then((response) => {
                    store.state.classes = response.data.result
                    return response.data.result
                })
                .catch(error => {
                    return error.response.data
                })
        },
        joinClass({ commit, state }, class_code) {
            return client.post(`/join-room/${state.data.user.id}/${class_code}`)
                .then((response) => {
                    router.push({ name: 'Classes' })
                    return response.data.result
                })
                .catch((error) => {
                    console.log("Something wen't wrong in the server.")
                })
        },
        roomdetails({ commit }, class_code) {
            if (class_code) {
                return client.post(`/room-details/${class_code}`)
                    .then((response) => {
                        if (typeof response.data.result === 'object') {
                            commit('getRoomDetails', response.data.result)
                            return response.data.result
                        } else {
                            setTimeout(() => {
                                router.push({
                                    name: 'Classes'
                                })
                            }, 1500);
                        }
                    })
                    .catch((e) => {
                        console.log("Something wen't wrong in the server.")
                    })
            } else {
                router.push({ name: 'Classes' })
            }
        },
        unEnroll({ }, classroom_id) {
            return client.patch('/class-unenroll/' + classroom_id)
                .then((response) => {
                    return response.data.result
                })
                .catch((err) => {
                    return error.response.data.result
                })
        },
        createTask({commit},data) {
            return client.post('/create-task/' + data.classroom_id, data)
                .then((response)=> {
                    return response.data.result
                })
                .catch((response)=> {
                    return response.response.data
                })
        },
        getTask({commit}, params) {
            return client.post(`/get-task/${params.id}/${params.class_code}`)
                .then((response) => {
                    return response.data.result
                })
        },
        getScore({commit}, data) {
            return client.post(`/submit-answer/${data.id}`,data)
                .then((response)=> {
                    return response.data.result
                })
                .catch((error)=>{
                    console.log(error)
                })
        },
        getResult({commit, state},task_id) {
            return client.post(`/get-result/${task_id}`)
                .then((response) =>{
                    state.temp = response.data.result
                    return response.data.result
                })
        },
        postComment({commit, state}, data) {
            return client.post('/post-comment', data)
                .then((response) => {
                    state.comments = response.data.result
                    return response.data.result
                })
        },
        getComment({commit, state}, data) {
            return client.post('/get-comment', data)
                .then((response) => {
                    state.comments = response.data.result
                    return response.data.result
                })
        },
        roomComments({commit, state}, data) {
            return client.post('/room-comments', data)
                .then((response) => {
                    state.comments = response.data.result
                    return response.data
                })
        },
        getCompleted({commit, state}) {
            return client.post('/get-completed')
                .then((response)=> {
                    state.dashboard = response.data
                    return response.data
                })
        },
        getUserInfo({}) {
            return client.get('/get-user-profile')
                .then((response) => {
                    return response.data.result
                })
        },
        updateUser({}, data) {
            return client.post('/update-profile', data)
                .then((response) => {
                    return response.data.result
                })
                .catch((result) => {
                    console.log(result.response.data.errors)
                    return result.response.data
                })
        },
        setStatus({}, status){
            return client.post('/set-status/', status)
                .then((response) => {
                    console.log(response)
                    return response.data.result
                }).catch((err) =>{
                    console.log(err)
                })
        }
    },
    mutations: {
        setClasses: (state, data) => {
            try {
                state.classes = data
            } catch (e) {
                console.log('Failed to load from server!')
            }
        },
        getClasses: (state, data) => {
            try {
                state.classes = data
            } catch (e) {
                console.log('Failed to load from server!')
            }
        },
        getRoomDetails: (state, data) => {
            try {
                state.classes = data
            } catch (error) {
                console.log('Failed to load from server!')
            }
        },
        SET_AUTH(state, data) {
            state.isAuthenticated = data
            sessionStorage.setItem('auth', data)
        },
        SET_USER: (state, data) => {
            state.data.user = data
        },
        SET_AUTHORIZATION: (state, session) => {
            state.data.session_id = session
        }
    },
    modules: {

    }
})
window.onresize = () => {
    store.state.screen.width = window.innerWidth
    store.state.screen.height = window.innerHeight
}
export default store