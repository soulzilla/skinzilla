import * as types from './types'
import userApi from '../api'

export const actions = {
    async [types.USER_FETCH]({commit}, data = null) {
        commit(types.USER_SET_LOADING, true)
        const response = await userApi.all(data)
        commit(types.USER_OBTAIN, response.data.data)
        commit(types.USER_META, response.data.meta)
        commit(types.USER_SET_LOADING, false)
    },
}
