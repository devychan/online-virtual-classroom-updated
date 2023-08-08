<template>
    <div v-bind="$attrs">
        <header  style="display:flex;align-items:center;">
            <div class="current-route">
                <!-- <div class="load" >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div> -->
                <h1>
                    Profile
                </h1>
            </div>
            <nav v-if="$store.state.screen.width < 800">
                <ul style="display:flex;align-items: center;" v-if="$store.state.screen.width < 800">
                    <li class="nav-item" v-for="route in routes" style="padding: 10px;">
                        <router-link :to="route.to" class="navlink">{{ route.name }}</router-link>
                    </li>
                </ul>
            </nav>
        </header>
        <router-view :_user="user" :_status="userMounted" :_reRender="getUser"></router-view>
    </div>
</template>
<script setup>
import store from '../../../../store';
import { onMounted, nextTick, ref } from 'vue';

const user = ref({})
const userMounted = ref(false)

const getUser = async () => {
    nextTick()
    const response = await store.dispatch('getUserInfo')
    if(response) {
        user.value = response
        userMounted.value = true        
    }
}
const routes = [
    { name: 'Dashboard', svg: '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /> </svg>', to: { name: 'Dashboard' } },
    { name: 'Classes', svg: '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /> </svg>', to: { name: 'Classes' } },
    { name: 'Profile', svg: '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" /> </svg>', to: { name: 'Profile' } },
]
onMounted(()=> {
    getUser()
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
header {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    background-color: #1B2430;
    box-shadow: 1px 2px 7px;
}

header h1 {
    text-align: center;
    color: #F5F3F5;
}
</style>