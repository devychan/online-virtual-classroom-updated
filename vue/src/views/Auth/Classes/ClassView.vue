<template >
    <div class="class-data" v-if="_roomStatus">
        <header style="display:flex; align-items: center;">
            <div class="current-route" v-if="$store.state.screen.width > 800 ? 'display: none;' : ''">
                <h1>
                    {{ _roomDetails.class_subject }}
                </h1>
            </div>
            <nav v-if="$store.state.screen.width < 800">
                <ul style="display:flex;align-items: center;" v-if="$store.state.screen.width < 800">
                    <li class="nav-item" v-for="route in routes" style="padding: 10px;">
                        <router-link :to="route.to" class="navlink">{{ route.name }}</router-link>
                    </li>
                </ul>
            </nav>
            <div class="top-nav" v-if="_roomDetails.user_id == store.state.data.user.id">
                <div class="button-nav" @click="_modalToggle()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="#F5F3F5">
                        <path fill-rule="evenodd"
                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </header>
        <!-- <div class="load" v-if="!_roomStatus">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        </div> -->
        <div class="room" :style="$store.state.screen.width > 800 ? ''
        : 'display:flex;flex-direction:column;align-items:center;'">
            <div v-if="_roomDetails">
                <div class="room-description">
                    <h1 style="color:#F1EEE9;">{{ _roomDetails.class_code ? _roomDetails.class_name + ' : ' +
                            _roomDetails.class_subject : ''
                    }}</h1>
                    <div class="room-schedule">
                        <h2 style="color:#F1EEE9;">{{ _roomDetails.class_code ? _roomDetails.class_section + ' - ' +
                                _roomDetails.class_room : ''
                        }}</h2>
                    </div>
                </div>

                <div class="works" v-if="_roomDetails.tasks"
                    :style="$store.state.screen.width > 800 ? '' : 'padding: 0;margin-top:5%;'">
                    <router-link class="class-assign" v-for="(task, index) in _roomDetails.tasks.slice().reverse()"
                        :key="index"
                        :to="{ name: 'ClassTask', params: { id: task.id, class_code: _roomDetails.class_code } }"
                        :style="$store.state.screen.width > 800 ? '' : 'width:auto;'">
                        <div class="profile">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z"
                                    clip-rule="evenodd" />
                                <path
                                    d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                            </svg>
                        </div>
                        <div class="class-info" :style="'padding: 20px;' + $store.state.screen.width > 800 ? ''
                        : 'width: 100%;padding: 10px;'">
                            <div>
                                <h3>{{ task.title }}</h3>
                                <div class="due">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                        viewBox="0 0 24 24" stroke="#F1EEE9" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <b>{{ task.created_at.substring(0, 10) }}</b>
                                </div>
                            </div>
                        </div>

                    </router-link>
                </div>

                <div class="room-description" v-if="!_roomDetails.tasks">
                    <div v-if="_roomDetails.user_id == store.state.data.user.id">
                        <h1 style="color:#F1EEE9;">Create a task</h1>
                    </div>
                    <div v-else>
                        <h1>No task</h1>
                    </div>
                </div>
            </div>
            <div v-else></div>

            <div class="latest" v-if="_roomDetails.user_id === $store.state.data.user.id">
                <div class="latest-title">
                    <h1>Updates</h1>
                </div>
                <div class="bulletin" v-if="getComments.length">
                    <span style="display:grid;place-content: center;position: relative;top:-5%">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor"
                            class="bi bi-pin-angle-fill" viewBox="0 0 16 16"
                            style="position: absolute; top: 0;left: 50%;">
                            <path
                                d="M9.828.722a.5.5 0 0 1 .354.146l4.95 4.95a.5.5 0 0 1 0 .707c-.48.48-1.072.588-1.503.588-.177 0-.335-.018-.46-.039l-3.134 3.134a5.927 5.927 0 0 1 .16 1.013c.046.702-.032 1.687-.72 2.375a.5.5 0 0 1-.707 0l-2.829-2.828-3.182 3.182c-.195.195-1.219.902-1.414.707-.195-.195.512-1.22.707-1.414l3.182-3.182-2.828-2.829a.5.5 0 0 1 0-.707c.688-.688 1.673-.767 2.375-.72a5.922 5.922 0 0 1 1.013.16l3.134-3.133a2.772 2.772 0 0 1-.04-.461c0-.43.108-1.022.589-1.503a.5.5 0 0 1 .353-.146z" />
                        </svg>
                    </span>
                    <div class="frame">
                        <div v-for="update in getComments" class="update-field" style="cursor:pointer">
                            <router-link class="user" style="color:#F1EEE9;"
                                :to="{ name: 'ClassTask', params: { id: update.task_id, class_code: _roomDetails.class_code } }">
                                <b style="color:#F1EEE9;"> {{ update.user }}</b> commended on class
                            </router-link>
                        </div>
                    </div>
                </div>
                <div class="bulletin" v-else>
                    <span style="display:grid;place-content: center;position: relative;top:-5%">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor"
                            class="bi bi-pin-angle-fill" viewBox="0 0 16 16"
                            style="position: absolute; top: 0;left: 50%;">
                            <path
                                d="M9.828.722a.5.5 0 0 1 .354.146l4.95 4.95a.5.5 0 0 1 0 .707c-.48.48-1.072.588-1.503.588-.177 0-.335-.018-.46-.039l-3.134 3.134a5.927 5.927 0 0 1 .16 1.013c.046.702-.032 1.687-.72 2.375a.5.5 0 0 1-.707 0l-2.829-2.828-3.182 3.182c-.195.195-1.219.902-1.414.707-.195-.195.512-1.22.707-1.414l3.182-3.182-2.828-2.829a.5.5 0 0 1 0-.707c.688-.688 1.673-.767 2.375-.72a5.922 5.922 0 0 1 1.013.16l3.134-3.133a2.772 2.772 0 0 1-.04-.461c0-.43.108-1.022.589-1.503a.5.5 0 0 1 .353-.146z" />
                        </svg>
                    </span>
                    <div class="frame">
                        <div class="update-field">
                            <div class="user" style="color:#F1EEE9;">
                                <b style="color:#F1EEE9;">No updates</b> available today
                            </div>
                        </div>
                    </div>
                </div>
                <div class="paginate" style="display:flex;align-items: center; justify-content: center;"
                    v-if="totalPage">
                    <div v-if="totalPage.current_page > 9" style="padding: 10px;
                        background-color: #1B2430;
                        color:#F1EEE9;
                        cursor:pointer" @click="getComment(1)"> First </div>
                    <div class="page-number" v-for="index in totalPage.total"
                        :style="totalPage.current_page == index ? 'background-color:#1363DF' : ''"
                        @click="getComment(index)">{{ index }}</div>

                    <div style="padding: 10px;
                        background-color: #1B2430;
                        color:#F1EEE9;
                        cursor:pointer" @click="getComment(totalPage.total)" v-if="totalPage.total"> Last </div>
                </div>
            </div>
        </div>
        <div class="modal-post" v-show="_modalStatus">
            <div class="modal">
                <div class="modal-header">
                    <h1 style="color:#F1EEE9;">Write a post</h1>
                    <div class="close-modal">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="#F5F3F5"
                            @click="_modalToggle()">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="modal-content">
                        <div v-if="_errorStatus" class="errors">
                            <div v-for="error in _errorStatus" class="error">
                                {{ _modalToggle() }}
                                {{ error[0] }}
                            </div>
                        </div>
                        <h2>Choose type</h2>
                        <div class="question-type">
                            <input type="button" value="Announce" @click="stateOption('announce', true)" class="q-type"
                                :style="options.announce ? 'background-color: #302B27; color:#F1EEE9; box-shadow: 1px 1px 10px;' : ''">
                            <input type="button" value="Quiz" @click="stateOption('quiz', true)" class="q-type"
                                :style="options.quiz ? 'background-color: #302B27; color:#F1EEE9; box-shadow: 1px 1px 10px;' : ''">
                            <input type="button" value="Bulletin" @click="stateOption('bulletin', true)" class="q-type"
                                :style="options.bulletin ? 'background-color: #302B27; color:#F1EEE9; box-shadow: 1px 1px 10px;' : ''">
                        </div>
                        <!-- Announcement -->
                        <form v-if="options.announce" @submit.prevent="createTask(_roomDetails.id, 'announce');">
                            <div class="input-group"
                                :style="errorStatus && errorMessage.title ? 'border: 2px solid #F94C66;' : ''">
                                <input type="text" name="title" v-model="announce.title" class="modal-input"
                                    placeholder="Title">
                            </div>
                            <div class="input-group"
                                :style="errorStatus && errorMessage.description ? 'border: 2px solid #F94C66;' : ''">
                                <textarea type="text" name="description" class="modal-input"
                                    v-model="announce.description" placeholder="Write an announcement"></textarea>
                            </div>
                            <input type="submit" value="Create" class="button-submit">
                        </form>
                        <!-- Quiz -->
                        <form v-if="options.quiz" @submit.prevent="createTask(_roomDetails.id, 'quiz'); ">
                            <div class="input-group"
                                :style="errorStatus && errorMessage.title ? 'border: 2px solid #F94C66;' : ''">
                                <input type="text" name="" v-model="quiz.title" class="modal-input" placeholder="Title">
                            </div>
                            <div class="input-group"
                                :style="errorStatus && errorMessage.tag ? 'border: 2px solid #F94C66;' : ''">
                                <input type="text" name="" v-model="quiz.tag" class="modal-input"
                                    placeholder="Note: ">
                            </div>
                            <div class="input-group">
                                <label for="">Status</label>
                                <select name="status" class="modal-input" v-model="quiz.status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div style="padding: 10px;">
                                <input type="button" value="Choices" @click="stateTag('choices', true)" class="q-type"
                                    :style="tag.choices ? 'background-color: #302B27; color:#F1EEE9; box-shadow: 1px 1px 10px;' : ''">
                                <input type="button" value="Identification" @click="stateTag('identification', true)"
                                    class="q-type"
                                    :style="tag.identification ? 'background-color: #302B27; color:#F1EEE9; box-shadow: 1px 1px 10px;' : ''">
                            </div>
                            <div class="input-group">
                                {{ count + 1 }}<input type="text" v-model="tempQuestion" class="modal-input"
                                    placeholder="Add question" :disabled="status ? true : false">
                                <svg @click="addQuestion" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                    viewBox="0 0 20 20" fill="currentColor" v-if="tempQuestion && !status">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                        clip-rule="evenodd" />
                                </svg>
                                <svg @click="cancelQuestion" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                    viewBox="0 0 20 20" fill="currentColor" v-if="status">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>

                            <div v-if="status">
                                <div class="input-group" v-if="tag.choices">
                                    <input type="text" class="modal-input" v-model="tempChoice" placeholder="Add item">
                                    <svg v-if="tempChoice" @click="addChoices(tempChoice)"
                                        xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="input-group" v-else-if="tag.identification" style="padding: 10px;">
                                    <b style="color:#F24C4C">Note: Identification is case sensitive, make use use an
                                        upper-case letter or lower-case as key</b>
                                </div>
                                <ol class="input-item" style="max-height: 120px">

                                    <li v-for="choice in tempChoices" @click="getAnswer(choice)"
                                        :style="choice === tempChoice ? ['background-color: #FFB562'] : ''">
                                        {{ choice }}{{ choice === tempChoice ? ' - Duplicated' : '' }}
                                        <svg @click="deleteChoices(choice)" xmlns="http://www.w3.org/2000/svg"
                                            class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </li>
                                </ol>
                                <div class="input-group">
                                    <b>Answer: </b>
                                    <input type="text" class="modal-input" v-model="tempAnswer" :disabled="tag.choices"
                                        placeholder="Answer">
                                </div>
                            </div>
                            <div v-else class="question-list">
                                <div v-if="quiz.items.length">
                                    <h3>Your Questions</h3>
                                </div>
                                <div v-else style="height:180px">
                                    <h3>No Questions available</h3>
                                </div>
                                <div>
                                    <ol v-for="(item, index) in quiz.items">
                                        <div class="question-delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor" v-if="!status" @click="deleteQuestion(item)">
                                                <path fill-rule="evenodd"
                                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <li>
                                            <div>
                                                <div>
                                                    {{ index + 1 + ')' }} {{ item.question }}
                                                </div>
                                                <div class="choices" style="padding: 0px 10px 0px 30px">
                                                    <div :style="choice.toLowerCase() === item.answer.toLowerCase() ? 'color:red;font-weight:bold' : ''"
                                                        v-for="choice in item.choices[0]">{{ choice }}</div>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            Answer: {{ item.answer }}
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <button style="margin: 5px 0px 5px 0px;padding:10px;" v-if="status" type="button"
                                class="button-submit"
                                @click="saveQuestion(tempQuestion, tempChoices, tempAnswer)">Save</button>
                            <button style="margin: 5px 0px 5px 0px;" v-if="!status" type="submit"
                                class="button-submit">Create quiz</button>
                        </form>
                        <!-- Bulletin -->
                        <form v-if="options.bulletin" @submit.prevent="createTask(_roomDetails.id, 'bulletin');">
                            <div class="input-group"
                                :style="errorStatus && errorMessage.title ? 'border: 2px solid #F94C66;' : ''">
                                <input type="text" name="" v-model="bulletin.title" class="modal-input"
                                    placeholder="Title">
                            </div>
                            <div class="input-group">
                                <input type="text" v-model="bulletinItem" class="modal-input" placeholder="Add item">
                                <svg @click="addItem()" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                    viewBox="0 0 20 20" fill="currentColor" v-if="bulletinItem">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <ul class="input-item" v-if="bulletin.items">
                                <li v-for="bullet in bulletin.items.slice().reverse()">
                                    {{ bullet }}
                                    <svg @click="deleteItem(bullet)" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </li>
                            </ul>
                            <div v-else class="input-item">
                                <li><b>No bulletin items</b></li>
                            </div>
                            <div class="input-group"
                                :style="errorStatus && errorMessage.description ? 'border: 2px solid #F94C66;' : ''">
                                <textarea type="text" name="work_description" class="modal-input text-area"
                                    v-model="bulletin.description" placeholder="Write a description"></textarea>
                            </div>
                            <input type="submit" value="Create" class="button-submit">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="error-msg" v-if="errorStatus" style="background-color: #D82148;">
            <div class="error-title" v-for="(error, index) in errorMessage"> {{ error[0] }}</div>
        </div>
        <div class="error-msg" v-else-if="msgStatus && success" style="background-color: #6BCB77;">
            <div class="error-title" style="color:#1B2430"> {{ msgStatus }}</div>
        </div>
    </div>

    <div v-else>
        <div class="load">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        </div>
    </div>

</template>

<script setup>
import { ref, onMounted, computed, reactive } from 'vue'
import store from '../../../../store'
import router from '../../../../router';
const props = defineProps({
    _roomStatus: Boolean,
    _modalStatus: Boolean,
    _modalToggle: Function,
    _errorStatus: Object,
    _roomDetails: Object,
    _loadTask: Function
})
const msgStatus = ref({})
const errorStatus = ref(false)
const success = ref(false)
const getComments = ref({})
getComments.value = computed(() => store.state.tasks)
const taskParams = reactive({
    class_code: router.currentRoute.value.params.class_code
})
const totalPage = ref()
const getComment = async (page) => {
    page ? page : page = 1
    const data = reactive({
        class_code: taskParams.class_code,
        page: page
    })
    const response = await store.dispatch('roomComments', data)
    if (response.result) {
        getComments.value = response.result.slice().reverse()
        totalPage.value = response.pages
    }
}
const routes = [
    { name: 'Dashboard', svg: '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /> </svg>', to: { name: 'Dashboard' } },
    { name: 'Classes', svg: '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /> </svg>', to: { name: 'Classes' } },
    { name: 'Profile', svg: '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" /> </svg>', to: { name: 'Profile' } },
]
const errorMessage = ref({})
const createTask = async (classroom_id, type) => {
    if (type === "announce") {
        announce.value.classroom_id = classroom_id
        announce.value.type = "announce"
        let data = announce.value
        const response = await store.dispatch('createTask', data)
        if (response) {
            msgStatus.value = response
            success.value = !success.value
            props._modalToggle()
            setTimeout(() => {
                msgStatus.value = {}
                success.value = !success.value
            }, 3000);
            if (response.errors) {
                errorMessage.value = response.errors
                errorStatus.value = !errorStatus.value
                setTimeout(() => {
                    msgStatus.value = {}
                    errorStatus.value = !errorStatus.value
                }, 3000);
            }
        }


    } else if (type === "quiz") {
        quiz.value.classroom_id = classroom_id
        quiz.value.type = type
        let data = quiz.value
        const response = await store.dispatch('createTask', data)
        if (response) {
            msgStatus.value = response
            success.value = !success.value
            props._modalToggle()
            setTimeout(() => {
                msgStatus.value = {}
                success.value = !success.value
            }, 3000);
            if (response.errors) {
                errorMessage.value = response.errors
                errorStatus.value = !errorStatus.value
                setTimeout(() => {
                    msgStatus.value = {}
                    errorStatus.value = !errorStatus.value
                }, 3000);
            }
        }
    } else if (type === "bulletin") {
        bulletin.value.classroom_id = classroom_id
        bulletin.value.type = type
        let data = bulletin.value
        const response = await store.dispatch('createTask', data)
        if (response) {
            msgStatus.value = response
            success.value = !success.value
            props._modalToggle()
            setTimeout(() => {
                msgStatus.value = {}
                success.value = !success.value
            }, 3000);
            if (response.errors) {
                errorMessage.value = response.errors
                errorStatus.value = !errorStatus.value
                setTimeout(() => {
                    msgStatus.value = {}
                    errorStatus.value = !errorStatus.value
                }, 3000);
            }
        }
    }
    props._loadTask()
}
const getAnswer = (answer) => {
    tempAnswer.value = answer
}
const tag = ref({
    choices: true,
    identification: false
})
const stateTag = (type, state) => {
    if (type === 'choices') {
        tag.value.choices = state
        tag.value.identification = false
        tempAnswer.value = ""
    } else if (type === 'identification') {
        tag.value.choices = false
        tag.value.identification = state
        tempChoices.value = []
    } else {
        props._modalToggle()
    }
}

const deleteQuestion = (item) => {
    quiz.value.items = quiz.value.items.filter((data) => {
        return data !== item
    })
    count.value -= 1
}
const options = ref({
    announce: true,
    quiz: false,
    bulletin: false
})
const announce = ref({
    classroom_id: '',
    title: '',
    description: '',
    type: ''
})
const bulletin = ref({
    classroom_id: '',
    title: '',
    items: [],
    description: '',
    type: '',
})

const quiz = ref({
    classroom_id: '',
    title: '',
    tag: '',
    items: [],
    type: '',
    status: 1
})
const count = ref(0)
const tempQuestion = ref('')
const tempChoices = ref([])
const tempAnswer = ref('')
const bulletinItem = ref('')
const status = ref(false)

const addQuestion = () => {
    status.value = true
}
const cancelQuestion = () => {
    status.value = false
    tempQuestion.value = ""
    tempChoices.value = []
    tempChoice.value = ""
    tempAnswer.value = ""
}
// choices 
const tempChoice = ref('')

const tempTag = ref('')


const addChoices = (choice) => {
    tempChoices.value.push(choice)
    tempChoice.value = ""
}

const deleteChoices = (choice) => {
    tempChoices.value = tempChoices.value.filter(item => { return item !== choice })
}
const saveQuestion = (question, choices, answer) => {
    quiz.value.items.push({
        question: question,
        choices: [choices],
        answer: answer
    })
    count.value++
    status.value = false
    tempQuestion.value = ""
    tempChoices.value = []
    tempChoice.value = ""
    tempAnswer.value = ""
}


const stateOption = (type, state) => {
    if (type === "announce") {
        options.value.announce = state
        options.value.quiz = false
        options.value.bulletin = false
        announce.value.type = "announce"
    } else if (type === "quiz") {
        options.value.announce = false
        options.value.quiz = state
        options.value.bulletin = false
        quiz.value.type = "quiz"
    } else if (type === "bulletin") {
        options.value.announce = false
        options.value.quiz = false
        options.value.bulletin = state
        bulletin.value.type = "bulletin"
    }
    status.value = false
    count.value = 0
    tempQuestion.value = ""
    tempChoices.value = []
    tempChoice.value = ""
    tempAnswer.value = ""
    bulletin.value.title = ""
    bulletin.value.items = []
    bulletin.value.description = ""
    bulletinItem.value = ""
    announce.value.description = ""
    announce.value.title = ""
    quiz.value.title = ""
}

const addItem = () => {
    bulletin.value.items.push(bulletinItem.value)
    bulletinItem.value = ''
}
const deleteItem = (data) => {
    bulletin.value.items = bulletin.value.items.filter(item => { return item !== data })
}


onMounted(async () => {
    getComment()
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

.page-number {
    padding: 10px;
    color: #F1EEE9;
    cursor: pointer;
    background-color: #1B2430;
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
}

.error-title {
    padding: 10px;
    font-size: 18px;
    color: #F5F3F5;
    width: auto;
}

.user {
    padding: 5px;
}

.update-field {
    padding: 10px;
    margin: 5px 0px 5px 0px;
    background-color: #1363DF;
}

.frame {
    margin: 5%;
    position: relative;
    height: 90%;
    border-radius: 2px;
    overflow-y: auto;
    max-height: 90%;

}

.bulletin {
    box-shadow: 1px 1px 10px;
    width: 100%;
    height: 400px;
    margin: 5% 0% 5% 0%;

}

.latest-title {
    padding: 10px;
    background-color: #1B2430;
}

.latest-title h1 {
    text-align: center;
    color: #F5F3F5;
}

.latest {
    padding: 10px;
    width: 400px;
}

.input-item li:hover {
    box-shadow: 1px 1px 5px;
    cursor: pointer;
}

.question-delete {
    display: flex;
    justify-content: flex-end;
}

.question-delete svg {
    width: 30px;
    height: 30px;
    cursor: pointer;
}

.choices div {
    list-style: upper-alpha;
    display: list-item;
    padding: 5px 0px 5px 0px;
}

.input-item {
    overflow-y: auto;
    height: 100%;
    max-height: 200px;
}

::-webkit-scrollbar {
    display: none;
}

.question-list {
    overflow-y: scroll;
    height: 100%;
    max-height: 260px;
}

.question-list li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    margin: 5px 0px 5px 0px;
    font-weight: 300;
    background-color: #F5F3F5;
}

.input-item li {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px;
    margin: 5px 0px 5px 0px;
    font-weight: 300;
    background-color: #F5F3F5;
}

.input-item li svg {
    width: 20px;
    height: 20px;
    cursor: pointer;
}


.input-group svg {
    width: 30px;
    height: 30px;
    cursor: pointer;
}


.input-group textarea {
    width: 100%;
    height: 300px;
    border: 0px;
    background-color: #F1EEE9;
    padding: 10px;
    font-size: 17px;
    resize: vertical;
}

.input-group textarea:focus {
    outline: none;
}

.question-type {
    padding: 5px 0px 5px 0px;
}

.q-type {
    padding: 10px;
    cursor: pointer;
    border: 0px;
    box-shadow: 1px 1px 5px;
    margin: 0px 5px 0px 5px;
    border-radius: 5px;
}

.q-type:hover {
    box-shadow: 1px 1px 10px;
}

.errors {
    border-radius: 5px;
}

.error {
    padding: 5px;
    background-color: #D82148;
    color: #fff;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.due {
    display: flex;
    align-items: center;
}

.due b {
    color: #F1EEE9;
    padding: 10px 0px 10px 0px;
}

.due svg {
    width: 25px;
    height: 25px;
    margin: 0px 5px 0px 5px;
}

.button-submit {
    padding: 10px;
    width: 100%;
    border: 0px;
    cursor: pointer;
    font-size: 18px;
    box-shadow: 1px 1px 5px;
    background-color: #1B2430;
    color: #F5F3F5;
}

.load svg {
    width: 50px;
    height: 50px;
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

.button-submit:hover {
    box-shadow: 1px 1px 10px #1B2430;
}

.close-modal svg {
    width: 30px;
}

.close-modal svg:hover {
    cursor: pointer;
}

.modal-post {
    background-color: rgba(0, 0, 0, 0.8);
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1;
    height: 100vh;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    animation: fadeIn .5s;
}

.modal {
    display: flex;
    justify-content: center;
    flex-direction: column;
    margin: 0%;
    background-color: #FFFFFF;
    width: 50%;
}

.modal-header {
    position: relative;
    top: 1%;
    width: auto;
    padding: 10px;
    background-color: #1B2430;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.modal-header h1 {
    padding: 10px;
}

.modal-body {
    padding: 10px;

}

.modal-content {
    padding: 10px;
    height: 80vh;
    padding: 10px;
    overflow-y: auto;
}

.input-group {
    padding: 0px 12px 0px 12px;
    display: flex;
    align-items: center;
    background-color: #F1EEE9;
    margin: 10px 0px 5px 0px;
}

.input-group:focus-within {
    box-shadow: 1px 1px 15px;
}

.input-group label {
    font-size: 17px;
    font-weight: bold;
}

.modal-input {
    padding: 12px;
    width: 100%;
    border: 0px solid;
    background-color: #F1EEE9;
    outline: none;
    font-size: 17px;
}

.load svg {
    width: 40px;
    height: 40px;
}

.profile {
    margin: 0px 5px 0px 5px;
    padding: 15px;
}

.profile svg {
    width: 35px;
    height: 35px;
}

.works {
    padding: 10px;
    max-height: 345px;
    overflow-y: auto;
    border-radius: 10px;
}

.works::-webkit-scrollbar {
    display: none;
}

.class-assign {
    margin: 5px 0 5px 0%;
    border-radius: 2px;
    box-shadow: 1px 1px 5px;
    display: flex;
    align-items: center;
    animation: fadeIn 1s;
    width: 100%;

}

.class-assign div {
    color: #161616;
}

.class-info {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #1B2430;
    width: 100%;
}

.class-info h3 {
    padding: 0px 10px 0px 10px;
    color: #F1EEE9;
}

.class-info {
    color: #3A3845;
}

@media (max-width: 800px) {
    .works {
        width: 100%;
    }

}

@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.class-assign:hover {
    box-shadow: 1px 1px 10px;
    cursor: pointer;
}

.room {
    display: grid;
    grid-template-columns: 1fr auto;
    grid-template-rows: 1fr;
    padding: 5px;
}

.room-description {
    padding: 5%;
    font-size: 18px;
    animation: fadeIn 1s;
    background-color: #1B2430;
}

.room-schedule {
    padding: 10px 10px 0px 0%;
    animation: fadeIn 1s;
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