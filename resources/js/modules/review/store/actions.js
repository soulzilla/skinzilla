import * as types from './types'
import reviewApi from '../api'

export const actions = {
    async [types.REVIEW_FETCH]({commit}, data = null) {
        commit(types.REVIEW_SET_LOADING, true)
        const response = await reviewApi.all(data)
        commit(types.REVIEW_OBTAIN, response.data.data)
        commit(types.REVIEW_META, response.data.meta)
        commit(types.REVIEW_SET_LOADING, false)
    },
}
