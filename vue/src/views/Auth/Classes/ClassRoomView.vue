<template>
    <router-view :_roomStatus="roomStatus" :_modalStatus="modalStatus"
        :_modalToggle="modalToggle" :_errorStatus="errorMessage" :_roomDetails="roomdetails" :_loadTask="loadTask"></router-view>
</template>
<script setup>
import store from '../../../../store';
import { onMounted,nextTick,ref, reactive } from 'vue'
import router from '../../../../router';

const params = ref('')
const roomStatus = ref(false)
const modalStatus = ref(false)
const errorMessage = ref({})
const roomdetails = ref({})
params.value = router.currentRoute.value.params.class_code

const loadTask = async () => {
    await nextTick()
    const response = await store.dispatch('roomdetails', params.value)
    if (response == []) {
        roomStatus.value = false
    } else {
        roomdetails.value = response
        roomStatus.value = true
    }
}

const modalToggle = () => {
    modalStatus.value = !modalStatus.value
}
onMounted(() => {
    loadTask()
})
</script>