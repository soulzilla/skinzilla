import * as types from './types'
import feedbackApi from '../api'

export const actions = {
    async [types.FEEDBACK_FETCH]({commit}, data = null) {
        commit(types.FEEDBACK_SET_LOADING, true)
        const response = await feedbackApi.all(data)
        commit(types.FEEDBACK_OBTAIN, response.data.data)
        commit(types.FEEDBACK_META, response.data.meta)
        commit(types.FEEDBACK_SET_LOADING, false)
    },
}
