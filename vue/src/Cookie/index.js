import client from "../Axios"
import Cookies from 'js-cookie'
const cookie = () => {
    let token = Cookies.get('XSRF-TOKEN')
    if(token) {
        return new Promise(resolve => {
            resolve(token)
        })
    }
    return client.get('/csrf-cookie')
}
export default cookie