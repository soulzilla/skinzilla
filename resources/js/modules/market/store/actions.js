import * as types from './types'
import marketApi from '../api'

export const actions = {
    async [types.MARKET_FETCH]({commit}, data = null) {
        commit(types.MARKET_SET_LOADING, true)
        const response = await marketApi.all(data)
        commit(types.MARKET_OBTAIN, response.data.data)
        commit(types.MARKET_META, response.data.meta)
        commit(types.MARKET_SET_LOADING, false)
    },
}
