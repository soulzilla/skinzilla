import * as types from './types'
import gamblingApi from '../api'

export const actions = {
    async [types.GAMBLING_FETCH]({commit}, data = null) {
        commit(types.GAMBLING_SET_LOADING, true)
        const response = await gamblingApi.all(data)
        commit(types.GAMBLING_OBTAIN, response.data.data)
        commit(types.GAMBLING_META, response.data.meta)
        commit(types.GAMBLING_SET_LOADING, false)
    },
}
