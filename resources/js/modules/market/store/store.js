import * as types from './types'
import {actions} from './actions'

export const store = {
    state: {
        markets: [],
        marketsMeta: [],
        marketsLoading: true,
    },
    getters: {
        markets: state => state.markets,
        marketsMeta: state => state.marketsMeta,
        marketsLoading: state => state.marketsLoading,
    },
    mutations: {
        [types.MARKET_OBTAIN](state, markets) {
            state.markets = markets
        },
        [types.MARKET_CLEAR](state) {
            state.markets = []
        },
        [types.MARKET_SET_LOADING](state, loading) {
            state.marketsLoading = loading
        },
        [types.MARKET_META](state, meta) {
            state.marketsMeta = meta
        },
    },
    actions
}
