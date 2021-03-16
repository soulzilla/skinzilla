import * as types from './types'
import {actions} from './actions'

export const store = {
    state: {
        gamblings: [],
        gamblingsMeta: [],
        gamblingsLoading: true,
    },
    getters: {
        gamblings: state => state.gamblings,
        gamblingsMeta: state => state.gamblingsMeta,
        gamblingsLoading: state => state.gamblingsLoading,
    },
    mutations: {
        [types.GAMBLING_OBTAIN](state, gamblings) {
            state.gamblings = gamblings
        },
        [types.GAMBLING_CLEAR](state) {
            state.gamblings = []
        },
        [types.GAMBLING_SET_LOADING](state, loading) {
            state.gamblingsLoading = loading
        },
        [types.GAMBLING_META](state, meta) {
            state.gamblingsMeta = meta
        },
    },
    actions
}
