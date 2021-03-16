import * as types from './types'
import boxApi from '../api'

export const actions = {
    async [types.BOX_FETCH]({commit}, data = null) {
        commit(types.BOX_SET_LOADING, true)
        const response = await boxApi.all(data)
        commit(types.BOX_OBTAIN, response.data.data)
        commit(types.BOX_META, response.data.meta)
        commit(types.BOX_SET_LOADING, false)
    },
}
