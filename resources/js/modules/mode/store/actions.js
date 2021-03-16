import * as types from './types'
import modeApi from '../api'

export const actions = {
    async [types.MODE_FETCH]({commit}, data = null) {
        commit(types.MODE_SET_LOADING, true)
        const response = await modeApi.all(data)
        commit(types.MODE_OBTAIN, response.data.data)
        commit(types.MODE_META, response.data.meta)
        commit(types.MODE_SET_LOADING, false)
    },
}
