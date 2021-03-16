import axios from 'axios'

const API_ENDPOINT = 'markets'

export default {

    top(data) {
        return axios.get('market/top', {params: data})
    },

    one(id) {
        return axios.get('market/one/' + id,)
    },

    list(data) {
        return axios.get('market/list', {params: data})
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
