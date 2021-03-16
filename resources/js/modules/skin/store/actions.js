import * as types from './types'
import skinApi from '../api'

export const actions = {
    async [types.SKIN_FETCH]({commit}, data = null) {
        commit(types.SKIN_SET_LOADING, true)
        const response = await skinApi.all(data)
        commit(types.SKIN_OBTAIN, response.data.data)
        commit(types.SKIN_META, response.data.meta)
        commit(types.SKIN_SET_LOADING, false)
    },
}
