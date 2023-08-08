<template>
    <div class="main">
        <NavigationBarVue class="nav">
        </NavigationBarVue>
        <div class="content">
            <router-view :_component="status" :_createroom="createroom" :_showroom="classes" :_errors="errors"
                :_statusError="showError" :_modalStatus="modalStatus" :_modalToggle="modalToggle" :_unEnroll="unEnroll"
                :_status="status" :_user="user" :_joinClass="joinclass" :_state="state" :_options="options"
                :_successMessage="successMsg" :_successStatus="success">
            </router-view>
        </div>
    </div>

</template>
<script setup>
import { ref } from 'vue'
import store from '../../../store';
import NavigationBarVue from './NavigationBar.vue';
import { onMounted, nextTick } from 'vue'
import router from '../../../router';

const classes = ref({})
const errors = ref({})
const showError = ref(false)
const modalStatus = ref(false)
const user = ref({})
const class_code = ref('')
const status = ref(false)
const successMsg = ref('')
const success = ref(false)

const state = ref({
    status: true,
    title: 'CREATE CLASS',
    text: 'JOIN'
})

const options = () => {
    state.value.status = !state.value.status
    if (errors.value = {}) {
        showError.value = false
    } else {
        showError.value = true
    }

    if (state.value.status) {
        state.value.text = 'JOIN'
        state.value.title = 'CREATE CLASS'
    } else {
        state.value.text = 'CREATE'
        state.value.title = 'JOIN CLASS'
    }
}

const modalToggle = () => {
    modalStatus.value = !modalStatus.value
    errors.value = {}
    showError.value = false
}

// done
const createroom = async (data) => {
    errors.value = {}
    const response = await store.dispatch('createRoom', data)
    if (response.message) {
        showError.value = !showError.value
        errors.value = response
    } else {
        getRoom()
        errors.value = {}
        showError.value = null
        status.value = !status.value
        success.value = !success.value
        successMsg.value = response
        modalToggle()
        setTimeout(() => {
            successMsg.value = null
            success.value = !success.value
        }, 3000);
        data.class_name = ""
        data.class_section = ""
        data.class_room = ""
        data.class_subject = ""
    }

}
const joinclass = async (code) => {
    if (!code) {
        errors.value = {
            errors: {
                0: ["You must enter classroom code"]
            }
        }
        showError.value = !success.value
        setTimeout(() => {
            errors.value = {}
            showError.value = !showError.value
        }, 3000);
    } else {
        class_code.value = code
        if (confirm("Do you want to join in this classroom? ") == true) {
            const response = await store.dispatch('joinClass', class_code.value)
            if (response) {
                modalToggle()
                successMsg.value = response
                success.value = !success.value
                status.value = !status.value
                getRoom()
                setTimeout(() => {
                    successMsg.value = null
                    success.value = !success.value
                }, 3000);
            }
        } else {
            successMsg.value = "Cancelled"
            success.value = !success.value
            modalToggle()
            setTimeout(() => {
                successMsg.value = null
                success.value = !success.value
            }, 3000);
        }

    }
}
// done
const getUser = async () => {
    const response = await store.dispatch('getUser')
    if (response) {
        store.state.data.user = response
        user.value = response
    } else {
        sessionStorage.clear()
        router.push({ name: 'Login' })
    }
}

// done
const getRoom = async () => {
    await nextTick()
    const response = await store.dispatch('showRoom')
    if (response) {
        status.value = !status.value
        classes.value = response.reverse()
    } else {
        status.value = !status.value
    }
}
// done
const unEnroll = async (classroom_id) => {
    if (confirm("Do you wan't to unenroll in this class? ") == true) {
        const response = await store.dispatch('unEnroll', classroom_id)
        successMsg.value = response
        success.value = !success.value
        status.value = !status.value
        getRoom()
        setTimeout(() => {
            successMsg.value = null
            success.value = !success.value
        }, 3000);
    } else {
        successMsg.value = "Cancelled"
        success.value = !success.value
        setTimeout(() => {
            successMsg.value = null
            success.value = !success.value
        }, 1000);
    }
}

onMounted(() => {
    getRoom()
    getUser()
})


</script>
<style scoped>
.main {
    display: flex;
    height: 100vh;
}

.content {
    width: 100%;
}

@media only screen and (max-width: 768px) {
    .main {
        flex-direction: column;
    }

}
</style>