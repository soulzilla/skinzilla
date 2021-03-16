import * as types from './types'
import rouletteApi from '../api'

export const actions = {
    async [types.ROULETTE_FETCH]({commit}, data = null) {
        commit(types.ROULETTE_SET_LOADING, true)
        const response = await rouletteApi.all(data)
        commit(types.ROULETTE_OBTAIN, response.data.data)
        commit(types.ROULETTE_META, response.data.meta)
        commit(types.ROULETTE_SET_LOADING, false)
    },
}
