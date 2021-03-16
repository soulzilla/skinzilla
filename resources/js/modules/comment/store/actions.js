import * as types from './types'
import commentApi from '../api'

export const actions = {
    async [types.COMMENT_FETCH]({commit}, data = null) {
        commit(types.COMMENT_SET_LOADING, true)
        const response = await commentApi.all(data)
        commit(types.COMMENT_OBTAIN, response.data.data)
        commit(types.COMMENT_META, response.data.meta)
        commit(types.COMMENT_SET_LOADING, false)
    },
}
