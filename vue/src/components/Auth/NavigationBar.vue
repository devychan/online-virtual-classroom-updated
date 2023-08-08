<template :style="$store.state.screen.width < 800 ? 'display:none' : 'display: block'">
    <header :style="menuStatus ? 'width: 60px;' : 'width:280px'">
        <nav class="nav" :style="$store.state.screen.width < 800 ? 'display:none' : 'display: block'">
            <div class="homeTitle">
                <div class="menu">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2" @click="menuToggle">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </div>
                <router-link :to="{ name: 'Dashboard' }">
                    <h1>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="#EEEDDE"
                            :style="!menuStatus ? '' : 'width: 40px;'">
                            <path
                                d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                        </svg>
                    </h1>
                </router-link>
            </div>
            <div class="nav-container" >
                <ul class="navitems">
                    <div class="classes" v-show="!menuStatus">
                        <h1>Your class</h1>
                    </div>
                    <li class="nav-item" v-for="route in routes">
                        <router-link :to="route.to" class="navlink" v-show="!menuStatus">{{ route.name }}</router-link>
                        <router-link :to="route.to" class="navlink" v-show="menuStatus"><span class="svg"
                                v-html="route.svg"></span></router-link>
                    </li>
                    <li class="nav-item">
                        <form @submit="logout">
                            <a class="navlink" @click="logout" v-show="!menuStatus">Logout</a>
                            <a class="navlink" @click="logout" v-show="menuStatus"><span class="svg"><svg
                                        xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg></span></a>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
</template>
<script setup>
import store from '../../../store/index'
import router from '../../../router';
import client from '../../Axios';
import { ref} from 'vue'
import cookie from '../../Cookie/index'

const logout = async () => {
    await cookie()
    return client.post('logout').then((response) => {
        store.state.isAuthenticated = false
        store.dispatch('authcheck')
        return router.go(3)
    })
}
const routes = [
    { name: 'Dashboard', svg: '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /> </svg>', to: { name: 'Dashboard' } },
    { name: 'Classes', svg: '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /> </svg>', to: { name: 'Classes' } },
    { name: 'Profile', svg: '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" /> </svg>', to: { name: 'Profile' } },
]
const menuStatus = ref(store.state.sideBar)

const menuToggle = () => {
    menuStatus.value = !menuStatus.value
    localStorage.setItem('sidebar', menuStatus.value)
}

</script>
<style scoped>
.menu {
    display: flex;
    align-items: center;
    flex-direction: row-reverse;
}

.svg {
    width: 40px;
    height: 40px;
    background-color: #F5F5F5;
}

@keyframes slideAnim {
    from {
        opacity: 0;
        width: 60px;
    }

    to {
        opacity: 1;
        width: 280px;
    }
}

.menu svg {
    width: 40px;
    height: 40px;
    cursor: pointer;
}

header {
    background-color: #1B2430;
    border: 0;
    z-index: 1;
    position: relative;
    width: 280px;
}

.homeTitle {
    background-color: #141E27;
    padding: 10px;
}

.homeTitle h1 {
    font-size: 40px;
    color: #EFDDFD;
}

.classes {
    padding: 10px;
}

.classes h1 {
    color: #fff;
}

.nav {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.navitems {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;

}

.nav-item {
    background-color: #141E27;
    width: 100%;
    margin: 1px;
    cursor: pointer;
}

.nav-item:hover a {
    background-color: #F5F5F5;
    color: #1B2430;
}

.nav .router-link-exact-active {
    background-color: #F5F5F5;
    color: #1B2430;
    font-weight: bold;
}

.navlink {
    color: #EFDDFD;
    padding: 13px 10px 13px 10px;
    display: flex;
    justify-content: center;
}


</style>