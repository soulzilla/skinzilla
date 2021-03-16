import axios from 'axios'

const API_ENDPOINT = 'settings'

export default {

    update(model) {
        return axios.patch(API_ENDPOINT + '/profile', model)
    },

    password(data) {
        return axios.post(API_ENDPOINT + '/password', data);
    }

}
