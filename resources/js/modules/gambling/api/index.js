import axios from 'axios'

const API_ENDPOINT = 'gamblings'

export default {

    top(data) {
        return axios.get('gambling/top', {params: data})
    },

    one(id) {
        return axios.get('gambling/one/' + id,)
    },

    list(data) {
        return axios.get('gambling/list', {params: data})
    },

    all(data) {
        return axios.get(API_ENDPOINT, {params: data})
    },

    find(id) {
        return axios.get(API_ENDPOINT + '/' + id)
    },

    create(model) {
        return axios.post(API_ENDPOINT, model)
    },

    update(model) {
        return axios.put(API_ENDPOINT + '/' + model.id, model)
    },

    delete(id) {
        return  axios.delete(API_ENDPOINT + '/' + id)
    },

}
