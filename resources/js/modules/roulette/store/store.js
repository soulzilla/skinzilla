import * as types from './types'
import {actions} from './actions'

export const store = {
    state: {
        roulettes: [],
        roulettesMeta: [],
        roulettesLoading: true,
    },
    getters: {
        roulettes: state => state.roulettes,
        roulettesMeta: state => state.roulettesMeta,
        roulettesLoading: state => state.roulettesLoading,
    },
    mutations: {
        [types.ROULETTE_OBTAIN](state, roulettes) {
            state.roulettes = roulettes
        },
        [types.ROULETTE_CLEAR](state) {
            state.roulettes = []
        },
        [types.ROULETTE_SET_LOADING](state, loading) {
            state.roulettesLoading = loading
        },
        [types.ROULETTE_META](state, meta) {
            state.roulettesMeta = meta
        },
    },
    actions
}
