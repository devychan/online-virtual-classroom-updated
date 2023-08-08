<template>
    <div class="taskData" v-if="isMounted && $store.state.data.user.id">
        <header>
            <div class="current-route">
                <div class="load" v-if="!taskData.id">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="#ffff" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </div>
                <h1 v-else>
                    {{ taskData.class_subject }}
                </h1>
            </div>
        </header>
        <div class="task" v-for="task in taskData.tasks"
            :style="task.type === 'bulletin' ? 'grid-template-columns: auto' : ''" v-if="isMounted">
            <div class="content" v-if="task.type == 'announce'">
                <div class="task-title" style="
                    border-radius: 5px;
                    height: auto;
                    text-align: center;
                    margin: 5% 5% 0% 5%;
                    display:flex;
                    justify-content: center;
                    flex-direction: column;
                    border: 0;
                    padding: 10px 10px 10px 10px;
                    box-shadow: 1px 1px 10px;                    
                ">
                    <h2 style="padding: 50px">{{ task.title }}</h2>
                    <div class="quiz-item" style="
                    border: 0;
                    box-shadow: 0 0 0;
                    text-align: left;
                    white-space: pre-wrap;

                ">
                        {{ task.description }}
                    </div>
                </div>
            </div>
            <div class="comments" style="margin: 5%;" v-if="task.type == 'announce'">
                <div class="feedback-title" style="margin: 0">
                    <h2>Class comments</h2>
                </div>
                <div class="class-comments"
                    style="background-color: #EEEEEE;padding: 20px 5px 20px 5px;box-shadow: 1px 1px 5px;">
                    <div class="class-comment" style="background-color: #393E46;padding: 5px; margin: 5px 0px 5px 0px;"
                        v-for="comment in getcomments.comments" v-if="getcomments.comments">
                        <div style="font-size: 12px;color:#F6FBF4;display: flex;align-items: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="#F6FBF4" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ getcomments.user_id === $store.state.data.user.id ? 'You' : comment.user }}
                        </div>
                        <div style="display:flex;align-items: center;padding:5px;">
                            <pre
                                style="color:#F6FBF4;padding: 5px 5px 5px 10px;white-space: pre-wrap;">{{ comment.comment }}</pre>
                        </div>
                    </div>
                    <div class="load" v-else>
                        <div style="display:flex;align-items: center;padding:5px;" v-if="!getcomments.comments">
                            <pre style="color:#222831;padding: 5px 5px 5px 10px"> No recent comments </pre>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" v-else
                            stroke="#222831" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                </div>
                <div class="form-comments" style="width:100%;">
                    <form @submit.prevent="postComment(taskData.id)" style="margin: 5% 0% 5% 0%; width:100%;">
                        <div class="input-group"
                            style=" width:100%;display:flex;flex-direction: row; align-items: center; padding: 5px 8px 5px 8px">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" style="width: 30px; height: 30px;">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            <textarea class="input-text" cols="30" rows="10" placeholder="write a private comment"
                                v-model="tempComment" style="width:100%; height: 20px;"></textarea>
                            <input type="submit" class="form-input" value="Send"
                                style="width:auto;background-color: #1363DF;color:#F9F9F9">
                        </div>
                    </form>
                </div>
            </div>
            <div class="content" v-if="task.type == 'quiz' && isMounted">
                <div class="test-info"
                    style="box-shadow: 1px 1px 8px; margin: 5px 5% 5px 5%;padding: 10px;background-color: #1363DF; " >
                    <div class="task-title" style="padding: 10px; margin: 2%">
                        <h1 style="color:#F6FBF4;">{{ task.title }}</h1>
                    </div>
                    <div class="task-score" v-if="taskData.user_id === $store.state.data.user.id">
                        <h3 style="padding: 15px;color:#F6FBF4;">Total points: {{ task.total }}</h3>
                    </div>
                    <div class="task-score" v-else>
                        <h3 style="padding: 15px;color:#F6FBF4;">Total points: {{ task.total }}</h3>
                        <h3 style="padding: 15px;color:#F6FBF4;">
                            Your score: {{ getMyResult.score }}</h3>
                    </div>
                    <div style="padding: 10px 10px 0px 20px" v-if="task.user_id === $store.state.data.user.id">
                        <p style="color:#F6FBF4;padding: 5px;font-size: 18px;">{{ task.status ? 'Status: open' : 'Status close'}}</p>
                    </div>
                    <div style="padding: 10px" v-if="task.status">
                        <h2 style="padding: 15px;color:#F6FBF4;">{{ task.tag }}</h2>
                    </div>
                    <div style="padding: 10px" v-else>
                        <h2 style="padding: 15px;color:#F6FBF4;">Quiz is closed.</h2>
                    </div>
                    <form class="input-group" style="width:auto;box-shadow: 0px 0px 0px;justify-content:flex-start;"
                        @submit.prevent="setStatus(status)" v-if="task.user_id === $store.state.data.user.id">
                        <select name="status" class="modal-input" v-model="status" style="padding: 10px;">
                            <option value="1">Open quiz</option>
                            <option value="0">Close quiz</option>
                        </select>
                        <button style="padding: 10px;margin:5px 0px 5px 0px" type="submit">Submit</button>
                    </form>
                </div>
                <!-- answered -->

                <div v-if="task.status">
                    <div v-for="(answer, index) in JSON.parse(getMyResult.answer)" 
                    v-if="getMyResult.user_id === $store.state.data.user.id || getMyResult.answer && isMounted">
                    <div class="quiz-item">
                        <div class="quiz-question">
                            <h4>{{ index + 1 + ')' }} {{ JSON.parse(task.data)[index].question }}</h4>
                        </div>
                        <div class="quiz-answer" style="padding: 12px; margin: 5% 0% 5px 0% ;background-color: #50CB93;"
                            v-if="answer === JSON.parse(task.data)[index].answer">
                            <b style="color:#1B2430;">Correct: {{ answer }}</b>
                        </div>
                        <div class="quiz-answer" style="padding: 12px;margin: 5% 0% 5px 0% ;background-color: #F94C66;"
                            v-else>
                            <b style="color:#1B2430;">Wrong: {{ answer }}</b>
                        </div>
                    </div>
                </div>
                <div v-if="!getMyResult.answer || task.user_id === $store.state.data.user.id && isMounted ">
                    <form @submit.prevent="getData(task.id, tempAnswer); getResult()" ref="form" class="quiz-submit">
                        <div class="quiz-item" v-for="(quiz, index) in JSON.parse(task.data)">
                            <div class="quiz-question">
                                <h4>{{ index + 1 + ')' }} {{ quiz.question }}</h4>
                            </div>
                            <div class="quiz-choices">
                                <div class="group-choices" style="list-style-type: upper-alpha;padding: 5px;"
                                    v-if="typeof quiz.choices === 'object' && quiz.choices[0].length < 1">
                                    <input type="text" placeholder="Answer" :name="'answer-' + (index + 1)" style=" 
                                    padding: 7px 0px 7px 15px;
                                    border:0px;
                                    border-radius: 3px;
                                    box-shadow: 1px 1px 5px;
                                    font-size: 17px;" v-model="tempAnswer[index]" required
                                        v-if="taskData.user_id !== $store.state.data.user.id">
                                </div>
                                <div class="group-choices" style="list-style-type: upper-alpha;padding: 5px;"
                                    v-for="(choice, key) in quiz.choices[0]"
                                    v-if="taskData.user_id !== $store.state.data.user.id">
                                    <input class="choice" type="radio" :name="'answer-' + (index + 1)" :value="choice"
                                        required v-model="tempAnswer[index]">
                                    {{ choice }}
                                </div>
                            </div>
                            <div class="quiz-answer" v-if="taskData.user_id === $store.state.data.user.id"
                                style="padding: 15px;">
                                <b>Answer: {{ quiz.answer }}</b>
                            </div>
                        </div>
                        <div v-if="taskData.user_id !== $store.state.data.user.id">
                            <button class="quiz-button" type="submit">Submit</button>
                        </div>
                    </form>
                </div>
                </div>

            </div>
            <div class="comments" style="margin: 5%;" v-if="task.type == 'quiz'">
                <div class="feedback-title" style="margin: 0">
                    <h2>Class comments</h2>
                </div>
                <div class="class-comments"
                    style="background-color: #EEEEEE;padding: 20px 5px 20px 5px;box-shadow: 1px 1px 5px;">
                    <div class="class-comment" style="background-color: #393E46;padding: 5px; margin: 5px 0px 5px 0px;"
                        v-for="comment in getcomments.comments" v-if="getcomments.comments">
                        <div style="font-size: 12px;color:#F6FBF4;display: flex;align-items: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="#F6FBF4" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ getcomments.user_id === $store.state.data.user.id ? 'You' : comment.user }}
                        </div>
                        <div style="display:flex;align-items: center;padding:5px;">
                            <pre
                                style="color:#F6FBF4;padding: 5px 5px 5px 10px;white-space: pre-wrap;">{{ comment.comment }}</pre>
                        </div>
                    </div>
                    <div class="load" v-else>
                        <div style="display:flex;align-items: center;padding:5px;" v-if="!getcomments.comments">
                            <pre style="color:#222831;padding: 5px 5px 5px 10px"> No recent comments </pre>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" v-else
                            stroke="#222831" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                </div>
                <div class="form-comments" style="width:100%;">
                    <form @submit.prevent="postComment(taskData.id)" style="margin: 5% 0% 5% 0%; width:100%;">
                        <div class="input-group"
                            style=" width:100%;display:flex;flex-direction: row; align-items: center; padding: 5px 8px 5px 8px">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" style="width: 30px; height: 30px;">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            <textarea class="input-text" cols="30" rows="10" placeholder="write a private comment"
                                v-model="tempComment" style="width:100%; height: 20px;"></textarea>
                            <input type="submit" class="form-input" value="Send"
                                style="width:auto;background-color: #1363DF;color:#F9F9F9">
                        </div>
                    </form>
                </div>
            </div>
            <div class="content" v-if="task.type == 'bulletin'">
                <div class="task-title" style="
                    border-radius: 5px;
                    height: auto;
                    text-align: center;
                    margin: 5% 5% 0% 5%;
                    display:flex;
                    justify-content: center;
                    flex-direction: column;
                    border: 0;
                    padding: 10px 10px 10px 10px;
                    box-shadow: 1px 1px 10px;
                    
                ">
                    <h2 style="padding: 50px;font-size: 30px;">{{ task.title }}</h2>
                </div>
                <div class="grid" v-if="task.tag">
                    <div class="quiz-item" style="
                        border: 0;
                        box-shadow: 0 0 0;
                        margin:0;
                        padding: 20px 5px 20px 5px;
                        width: 100%;
                        text-align:center;
                    ">
                        {{ task.tag }}
                    </div>
                    <div class="grid-items">
                        <div class="grid-item" v-for="list in JSON.parse(task.data)">
                            {{ list }}
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div style="margin: 5% 0% 5% 0%" class="comments-not-found">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 style="text-align: center;opacity: 80%;">Class comments are hidden</h3>
                </div>
            </div> -->
        </div>
        <div v-else>
            <div class="load" v-if="!isMounted">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="#ffff"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
            </div>
        </div>

    </div>
</template>
<script setup>
import router from '../../../../router';
import { nextTick, onMounted, computed, reactive, ref } from 'vue';
import store from '../../../../store'; ''

const tempAnswer = ref([])
const isMounted = ref(false)
const getMyResult = ref({})
const tempComment = ref('')
const taskData = ref({})
const getcomments = ref({})
const getData = async (id, data) => {
    const sendAnswer = reactive({
        id: id,
        answer: data
    })
    await store.dispatch('getScore', sendAnswer)
        .then(() => {
            isMounted.value = true
        })
}
const getResult = async () => {
    await store.dispatch('getResult', taskParams.id)
        .then((response) => {
            getMyResult.value = response
        })
}
const taskParams = reactive({
    id: router.currentRoute.value.params.id,
    class_code: router.currentRoute.value.params.class_code,
})
const status = ref(1)
const setStatus = async (status) => {
    const data = reactive({
        status: status,
        class_code: taskParams.class_code
    })
    await store.dispatch('setStatus', data)
        .then(response => {
            getTask()
        })
}
const postComment = (classroom_id) => {
    const data = reactive({
        classroom_id: classroom_id,
        task_id: taskParams.id,
        comment: tempComment.value,
        user: store.state.data.user.name
    })
    const response = store.dispatch('postComment', data)
    if (response) {
        getComment()
        tempComment.value = null
    }
}
const getComment = async () => {
    const data = reactive({
        class_code: taskParams.class_code,
        task_id: taskParams.id
    })
    const response = await store.dispatch('getComment', data)
    if (response) {
        getcomments.value = response
    }
}
const getTask = async () => {
    const response = await store.dispatch('getTask', taskParams)
    try {
        taskData.value = response
        isMounted.value = true
    } catch (e) {
        console.log(e)
    }
}
getcomments.value = computed(() => store.state.tasks)
onMounted(async () => {
    await nextTick()
    isMounted.value = true
    getResult()
    getTask()
    getComment()
})

</script>

<style scoped>
@keyframes fadeIn {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.grid {
    display: grid;
    place-content: center;
    border: 0px;
    margin: 5%;
    padding: 40px;
    border-radius: 4px;
    box-shadow: 1px 1px 10px;
}

.grid-items {
    display: grid;
    grid-template-columns: auto auto auto auto;
}

.grid-item {
    padding: 20px;
    margin: 20px;
    width: 150px;
    height: 50px;
    display: grid;
    place-content: center;
    border-radius: 4px;
    box-shadow: 1px 1px 8px;
    background-color: #222831;
    color: #F5F3F5;
    font-size: 18px;
    cursor: pointer;
}

.grid-item:hover {
    background-color: #EEEEEE;
    color: #1B2430;
    box-shadow: 1px 1px 10px;
    animation: hoverEffect 0.5s;
}

@keyframes hoverEffect {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

.comments-not-found div {
    display: flex;
    align-items: center;
    justify-content: center;
}

.comments-not-found svg {
    width: 50px;
    height: 50px;
}

.class-comment {
    display: flex;
    flex-direction: column;
    padding: 10px;
    animation: 1s fadeIn;
}

.class-comment svg {
    width: 30px;
    height: 20px;
}

.class-comments {
    padding: 5px;
    width: 100%;
    overflow-y: auto;
    max-height: 300px;
    height: 100%;
}

* {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.form-input {
    padding: 10px;
    width: 100%;
    padding: 10px;
    font-size: 17px;
    border: 0px;
    border-radius: 5px;
    box-shadow: 1px 1px 5px;
}

.form-input:hover {
    box-shadow: 1px 1px 8px;
    cursor: pointer;
}

.input-text {
    resize: none;
    padding: 10px;
    width: 300px;
    height: 100px;
    font-size: 17px;
    border: 0px;
    border-radius: 5px;
}

.input-text:focus {
    outline: none;
}

.feedback-title {
    margin: 5% 5% 5% 5%;
    text-align: center;
}

.feedback-title h2 {
    padding: 10px;
}

.form-comments {
    display: flex;
    justify-content: center;
}

.input-group {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin: 5px 0px 5px 0px;
    box-shadow: 1px 1px 8px;
    padding: 10px;
    border-radius: 5px;
    width: 100%;
}

.input-group svg {
    padding: 10px;
    width: 200px;
    height: 150px;
}

.upload-file::-webkit-file-upload-button {
    margin: 0;
    padding: 10px;
    font-size: 17px;
    background-color: #F5F3F5;
    width: 100%;
    border: 0px;
}

.upload-file::-webkit-file-upload-button:hover {
    cursor: pointer;
    box-shadow: 1px 1px 10px;
}

.upload-file::before {
    outline: none;
}

.upload-file {
    box-shadow: 1px 1px 5px;
    border-radius: 5px;
}

input[type="file" i] {
    cursor: pointer;
}

input[type="file" i]:hover {
    box-shadow: 1px 1px 10px;
    cursor: pointer;
}

.quiz-submit {
    margin: 2% 0% 0% 0%;
}

.quiz-button {
    padding: 10px;
    margin: 2% 0% 0% 5%;
    border: 0;
    border-radius: 3px;
    box-shadow: 1px 1px 10px;
    background-color: #1363DF;
    color: #F5F3F5;
    font-size: 20px;
    cursor: pointer;
}

.quiz-button:hover {
    box-shadow: 1px 1px 15px;
}

.task-title {
    padding: 10px;
    margin: 2% 0% 0% 4%;
}

.task-score {
    padding: 10px;
}

.taskData {
    height: 100vh;
    max-height: 100vh;
}

.group-choices {
    display: flex;
    align-items: center;
    width: 100%;
}

.quiz-choices {
    padding: 10px 0px 10px 5px;
}

input[type=radio] {
    width: 25px;
    height: 20px;
    cursor: pointer;
}

.quiz-answer {
    padding: 5px 0px 5px 0px;
}

.task {
    display: grid;
    grid-template-columns: 1fr 0.5fr;
    background-color: #ffffff;
    height: 90vh;
}

.content {
    overflow-y: auto;
    z-index: 2;
    max-height: 90vh;
    height: 100vh;
    padding: 3px;
    box-shadow: 0px 0px 5px;
}

::-webkit-scrollbar {
    display: none;
}

.quiz-item {
    padding: 20px;
    margin: 2% 5% 5px 5%;
    border-radius: 1px;
    background-color: #EEEEEE;
    box-shadow: 0.5px 0.5px 10px;
}

.load svg {
    width: 30px;
    height: 30px;
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