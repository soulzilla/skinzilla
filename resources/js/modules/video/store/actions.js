import * as types from './types'
import videoApi from '../api'

export const actions = {
    async [types.VIDEO_FETCH]({commit}, data = null) {
        commit(types.VIDEO_SET_LOADING, true)
        const response = await videoApi.all(data)
        commit(types.VIDEO_OBTAIN, response.data.data)
        commit(types.VIDEO_META, response.data.meta)
        commit(types.VIDEO_SET_LOADING, false)
    },
}
