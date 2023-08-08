import axios from 'axios'
import store from '../store'
const client = axios.create({
    baseURL:'http://localhost:8000/api'
 
})
client.defaults.withCredentials = true
client.interceptors.response.use(response => {
        response.headers.Authorization = `Bearer ${store.state.data.api_token}`
        return response
    }
)


export default client