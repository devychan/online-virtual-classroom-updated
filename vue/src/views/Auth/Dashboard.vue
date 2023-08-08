<template>
    <div v-bind="$attrs">
        <header style="display:flex; align-items: center;">
            <div class="current-route">
                <h1>Dashboard</h1>
            </div>
            <nav v-if="$store.state.screen.width < 800">
                <ul style="display:flex;align-items: center;" v-if="$store.state.screen.width < 800">
                    <li class="nav-item" v-for="route in routes" style="padding: 10px;">
                        <router-link :to="route.to" class="navlink">{{ route.name }}</router-link>
                    </li>
                </ul>
            </nav>
        </header>

        <div class="load" v-if="!status">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        </div>
        <div v-if="status">
            <h4 style="color: #000422" v-if="user.name">Welcome {{ user.name }}!</h4>
        </div>
        <div class="grid" v-if="status" :style="$store.state.screen.width > 800 ? 'display:grid' :
            'display:flex;flex-direction: column;justify-content:center;align-items:center;margin: 0% 10% 0% 10%'
        ">

            <div class="grid-box" id="grid-box1" :style="$store.state.screen.width > 800 ? '' :
            'width: 100%;height:200px;'">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                    <path fill-rule="evenodd"
                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <div class="data-content">
                    <h1>{{ doneWorks.length ? doneWorks.done : 0 }}</h1>
                    <h3>Done</h3>
                </div>
            </div>
            <div class="grid-box" id="grid-box2" :style="$store.state.screen.width > 800 ? '' :
            'width: 100%;height:200px;'">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="data-content">
                    <h1>{{ doneWorks.length ? doneWorks.missing : 0 }}</h1>
                    <h3>Missing</h3>
                </div>
            </div>
            <div class="grid-box" id="grid-box3" :style="$store.state.screen.width > 800 ? '' :
            'width: 100%;height:200px;'">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <div class="data-content">
                    <h1>{{ doneWorks.length ? doneWorks.assigned : 0 }}</h1>
                    <h3>Assigned</h3>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import store from '../../../store';
import { ref } from 'vue';
import router from '../../../router';
import client from '../../Axios';
import ProfileVue from '../Auth/User/UserProfile.vue'
const user = computed(() => store.state.data.user)
const doneWorks = ref({})
const status = ref(false)
const Dashboard = async () => {
    await store.dispatch('getCompleted')
        .then((response) => {
            doneWorks.value = response
            status.value = !status.value
        })
}
doneWorks.value = computed(() => store.state.dashboard)
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
const profile = ref('')

onMounted(async () => {
    Dashboard()
})


</script>
<style scoped>
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

.load {
    display: flex;
    align-items: center;
    padding: 10px;
}

.load svg {
    width: 40px;
    height: 40px;
    animation: spin 1.5s infinite;
}

@keyframes spin {
    from {
        transform: rotateZ(0deg);
    }

    to {
        transform: rotateZ(360deg);
    }
}

.class-today {
    padding: 5px;
    background-color: #14C38E;
    border-radius: 4px;
    width: 400px;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.class-today h1 {
    padding: 10px;
}

.class-info {
    margin: 5px 0px 5px 0px;
    background-color: #E0E0E0;
    cursor: pointer;

}

.class-info:hover {
    box-shadow: 1px 1px 5px;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
}

.page {
    margin: 5px;
    padding: 10px;
    border-radius: 4px;
    cursor: pointer;
    border: 0.5px solid;
    background-color: #EFEFEF;
}

.page:hover {
    box-shadow: 1px 1px 10px;
}

.notif {
    padding: 10px;
    display: flex;
    justify-content: center;
    text-align: center;
    margin: 0% 8% 5% 8%;
    border-radius: 4px;
    /* margin:0% 25% 0% 25%; */
    animation: fadeIn 1s;

}

.classwork,
.class-today {
    margin: 0px 5px 0px 5px;
}

.notif-title h1 {
    padding: 10px;
}

.data-works {
    display: flex;
    flex-direction: column;

    max-height: 250px;
    overflow-y: auto;
    overflow-x: hidden;
    scroll-behavior: smooth;
}

.data-works::-webkit-scrollbar {
    display: none;
}

@keyframes slideIn {
    from {
        width: 0;
    }

    to {
        width: 80%;
    }
}

.data-info {
    box-shadow: 1px 1px 5px;
    margin: 2px;
}

.data-info:hover {
    cursor: pointer;
    box-shadow: 1px 1px 15px;
}

.classwork {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 400px;
}

header {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    background-color: #1B2430;
    box-shadow: 1px 2px 7px;
}

header h1 {
    text-align: center;
    color: #E0E0E0;
}

#grid-box1 {
    grid-area: box1;
    background-color: #559E54;
}

#grid-box2 {
    grid-area: box2;
    background-color: #EA202C;
}

#grid-box3 {
    grid-area: box3;
    background-color: #FF8C32;
}

.grid-box {
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-radius: 4px;
    box-shadow: 1px 5px 10px;
    padding: 15px;
    width: 300px;
    height: 150px;
    margin: 25px;
    cursor: pointer;
}

.grid-box h1,
h2,
h3,
h4,
h5,
h6 {
    color: #E0E0E0;
    padding: 5px;
}

.grid-box svg {
    width: 80px;
    height: 80px;
}

.grid-box:hover {
    box-shadow: 1px 1px 20px;
}

.data-content {
    display: flex;
    align-items: center;
    flex-direction: column;
    justify-content: space-between;
}

.grid {
    display: grid;
    grid-template-areas:
        "box1 box2 box3";
    position: relative;
    animation: slideIn 1.5s;

}


.top-nav {
    display: flex;
    align-items: center;
}

.top-nav svg {
    width: 40px;
    height: 35px;
}

.button-nav {
    border-radius: 50%;
    cursor: pointer;
    margin: 0px 5px 0px 5px;
}

.button-nav:hover {
    box-shadow: 1px 2px 10px;
}

.subject-notif {
    display: flex;
    align-items: center;
    justify-content: center;
}

.subject-notif svg {
    width: 40px;
    height: 40px;
}

.data-info h4 {
    color: rgba(0, 0, 0, 0.575);
}

@media only screen and (max-width: 768px) {
    .grid {
        display: flex;
        flex-direction: column;
    }
}
</style>