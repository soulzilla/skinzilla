import axios from 'axios'

const API_ENDPOINT = 'roulettes'

export default {

    top(data) {
        return axios.get('roulette/top', {params: data})
    },

    one(id) {
        return axios.get('roulette/one/' + id,)
    },

    all(data) {
        return axios.get(API_ENDPOINT, {params: data})
    },

    list(data) {
        return axios.get('roulette/list', {params: data})
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
