<template>
    <div v-bind="$attrs">
        <header style="display:flex; align-items: center;">
            <div class="current-route">
                <h1>Classes</h1>
            </div>
            <nav v-if="$store.state.screen.width < 800">
                <ul style="display:flex;align-items: center;" v-if="$store.state.screen.width < 800">
                    <li class="nav-item" v-for="route in routes" style="padding: 10px;">
                        <router-link :to="route.to" class="navlink">{{ route.name }}</router-link>
                    </li>
                </ul>
            </nav>
            <div class="top-nav">
                <div class="button-nav" @click="_modalToggle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="#F5F3F5">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

        </header>
        <div class="content">
            <div class="class-container" v-if="_status && _showroom.length">
                <div class="grid" :style="$store.state.screen.width > 800 ? '' :
                'display:flex;justify-content:center;align-items:center;margin: 0% 10% 0% 10%'">
                    <div class="grid-box" id="grid" v-for="room in _showroom" :style="$store.state.screen.width > 800 ? ''
                    : 'width: 100%;height: 230px;'">
                        <div class="class-data">
                            <div class="class-subject delete">
                                <router-link :to="{ name: 'ClassDetails', params: { class_code: room.class_code } }">
                                    {{ room.class_subject && room.class_subject.length > 16 ?
                                            room.class_subject.slice(0,
                                                16) +
                                            '...' : room.class_subject
                                    }}
                                </router-link>
                                <div>
                                    <svg @click="_unEnroll(room.id)" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                                        fill="#D82148" viewBox="0 0 24 24" stroke="#F5F3F5" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </div>
                            </div>
                            <div class="class-info">

                                <div class="class-time">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path
                                            d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                    </svg>
                                    {{ room.schedule }}
                                </div>
                                <div class="class-schedule">
                                    {{ room.class_name }} <br> {{ room.class_section }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="load" v-else>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <div v-if="_status && !_showroom.length">
                    <h1>NO CLASS FOUND!</h1>
                </div>
            </div>
            <div class="modal-container" v-if="_modalStatus">
                <div class="add-class-modal" :style="$store.state.screen.width > 800 ? '' :
                'margin-top:0%;width: auto'">
                    <div class="modal-header">
                        <div class="modal-title">
                            <h1>{{ _state.title }}</h1>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="modal-content">
                            <div class="class-options" style="flex-direction: column;">

                                <div class="btn-options">
                                    <button type="button" class="btn-option" @click="_options"
                                        style="background-color:#1B2430;color:#fff;font-weight: bold;box-shadow: 1px 1px 5px #1B2430;">{{
                                                _state.text
                                        }}</button>
                                </div>
                            </div>
                            <div class="modal-form">
                                <form @submit.prevent="_createroom(createdroom);" v-if="_state.status">
                                    <div class="input-group">
                                        <input type="text" name="title" class="class-input" placeholder="Class name"
                                            v-model="createdroom.class_name"
                                            :style="_statusError && _errors.errors.class_name ? 'border: 2px solid #F94C66;' : ''">
                                    </div>
                                    <div class="input-group">
                                        <input type="text" name="section" class="class-input"
                                            placeholder="Class section" v-model="createdroom.class_section"
                                            :style="_statusError && _errors.errors.class_section ? 'border: 2px solid #F94C66;' : ''">
                                    </div>
                                    <div class="input-group">
                                        <input type="text" name="room" class="class-input" placeholder="Class room"
                                            v-model="createdroom.class_room"
                                            :style="_statusError && _errors.errors.class_room ? 'border: 2px solid #F94C66;' : ''">
                                    </div>
                                    <div class="input-group">
                                        <input type="text" name="subject" class="class-input" placeholder="Subject"
                                            v-model="createdroom.class_subject"
                                            :style="_statusError && _errors.errors.class_subject ? 'border: 2px solid #F94C66;' : ''">
                                    </div>
                                    <div class="input-group" style="padding:10px;">
                                        <input type="submit" class="submit" :value="_state.title">
                                    </div>
                                </form>
                                <form @submit.prevent="_joinClass(class_code)" v-else>
                                    <div class="input-group">
                                        <input type="text" name="title" class="class-input" placeholder="Class code"
                                            v-model="class_code"
                                            :style="_statusError && _errors.errors[0] ? 'border: 2px solid #F94C66;' : ''">
                                    </div>
                                    <div class="input-group" style="padding:10px;">
                                        <input type="submit" class="submit" :value="_state.title">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="close-modal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="#E0E0E0"
                        @click="_modalToggle()">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <div class="error-msg" v-if="_statusError">
                <div class="error-title" v-for="(error, index) in _errors.errors"> {{ error[0] }}</div>
            </div>
            <div class="error-msg" v-else-if="_successStatus && _successMessage" style="background-color: #6BCB77;">
                <div class="error-title" style="color:#1B2430"> {{ _successMessage }}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
const props = defineProps({
    _createroom: Function,
    _showroom: Object,
    _errors: Object,
    _statusError: Boolean,
    _modalStatus: Boolean,
    _modalToggle: Function,
    _roomDetails: Function,
    _user: Object,
    _joinClass: Function,
    _options: Function,
    _state: Object,
    code: String,
    _unEnroll: Function,
    _status: Boolean,
    _successStatus: Boolean,
    _successMessage: String
})

const class_code = ref('')
const createdroom = ref({
    class_name: '',
    class_section: '',
    class_room: '',
    class_subject: ''
})
const routes = [
    { name: 'Dashboard', svg: '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /> </svg>', to: { name: 'Dashboard' } },
    { name: 'Classes', svg: '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /> </svg>', to: { name: 'Classes' } },
    { name: 'Profile', svg: '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" /> </svg>', to: { name: 'Profile' } },
]
onMounted(() => {
    props._showroom
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

.error-msg {
    position: absolute;
    top: 0%;
    right: 0%;
    padding: 10px;
    margin: 10px 20px 0px 5px;
    background-color: #D82148;
    box-shadow: 1px 1px 8px;
    animation: fadeIn .5s;
    z-index: 1;
}

.error-title {
    padding: 10px;
    font-size: 18px;
    color: #F5F3F5;
    width: auto;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

* {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.delete {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.delete svg {
    width: 30px;
    height: 30px;
}

.class-options {
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-option {
    padding: 10px;
    border: 0px;
    border-radius: 4px;
    box-shadow: 1px 1px 8px;
    margin: 0px 5px 0px 5px;
}

.btn-option:hover {
    box-shadow: 1px 1px 10px;
    cursor: pointer;
}

.latest {
    margin: 10px
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

.errorMsg {
    display: flex;
    flex-direction: column;
    padding: 10px 10px 10px 10px;
    background-color: #D82148;
}

.errorMsg {
    display: flex;
}

.error {
    color: #DDDDDD;
    padding: 1px;
}

.add-class-modal {
    background-color: #EEEEEE;
    height: auto;
    position: fixed;
    z-index: 2;
    margin-top: 5%;
    display: grid;
    place-items: center;
    place-content: center;

}

.modal-form {
    padding: 20px 50px 20px 50px;
}

.modal-header {
    padding: 20px 0px 20px 0px;
    text-align: center;
    background-color: #1B2430;
    width: 100%;
}

.modal-title h1 {
    color: #F5F3F5;

}

.modal-content {
    padding: 10px 50px 10px 50px;
}

.input-group {
    display: flex;
    align-items: center;
    margin: 10px;
}

.text-area {
    padding: 10px;
    display: flex;
    flex-direction: column;
}


.text-area .class-input {
    resize: none;
    font-size: 17px;
    width: 100%;
}



.class-input {
    padding: 10px;
    font-size: 18px;
    width: 100%;
    border: 0;
    border-radius: 3;
    box-shadow: 1px 1px 10px;
}

.class-input:focus {
    outline: none;
}

.submit {
    padding: 10px;
    font-size: 18px;
    width: 100%;
    cursor: pointer;
    border: 0px;
    color: #F5F3F5;
    box-shadow: 1px 1px 5px #1B2430;
    border-radius: 4px;
    background-color: #1363DF;
}

.submit:hover {
    box-shadow: 1px 1px 10px #1B2430;
}

.ex {
    display: flex;
    align-items: center;
    padding: 10px;
}

.ex div {
    color: #9c9393f9;
}

.close-modal {
    width: 100%;
    display: flex;
    position: fixed;
    flex-direction: row-reverse;
}

.close-modal svg {
    width: 40px;
    height: 40px;
    margin: 15px;
    cursor: pointer;
}

.close-modal svg:hover {
    box-shadow: 1px 1px 10px;
}

@keyframes showModal {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.modal-container {
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0%;
    height: 100vh;
    width: 100%;
    display: flex;
    justify-content: center;
    background: rgba(0, 0, 0, 0.9);
    border-radius: 4px;
    padding: 10px;
    animation: showModal 0.5s;
}


.class-container {
    display: grid;
    place-items: center;
    margin-top: 0%;
    overflow-y: auto;
    max-height: 530px;
    scroll-behavior: smooth;
}

.class-container::-webkit-scrollbar {
    display: none;
}

@keyframes rotate {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.grid {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    margin: 5%;
}

.grid-box {
    box-shadow: 1px 1px 10px;
    border-radius: 4px;
    width: 300px;
    height: 200px;
    margin: 10px;
    animation: rotate 1.5s;
}

.grid-box:hover {
    cursor: pointer;
    box-shadow: 1px 1px 20px;
}

.class-subject {
    padding: 15px;
    background-color: #1B2430;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
}

.class-subject a {
    color: #E0E0E0;
    font-size: 19px;
    text-align: center;
}

.class-subject a:hover {
    text-decoration: underline;
}

.class-data {
    display: flex;
    flex-direction: column;
}

.class-info {
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.class-time {
    font-size: 19px;
    padding: 10px;
    display: flex;
    flex-direction: column;
}

.class-schedule {
    padding: 10px;
    font-size: 17px;
    overflow: hidden;
    text-overflow: ellipsis;
}

.class-time svg {
    width: 70px;
    height: 70px;
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
</style>