import axios from 'axios'

const API_ENDPOINT = 'comments'

export default {

    all(data) {
        return axios.get(API_ENDPOINT, {params: data})
    },

    allByEntity(data) {
        return axios.get(API_ENDPOINT + '/entity', {params: data})
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
