import * as types from './types'
import {actions} from './actions'

export const store = {
    state: {
        modes: [],
        modesMeta: [],
        modesLoading: true,
    },
    getters: {
        modes: state => state.modes,
        modesMeta: state => state.modesMeta,
        modesLoading: state => state.modesLoading,
    },
    mutations: {
        [types.MODE_OBTAIN](state, modes) {
            state.modes = modes
        },
        [types.MODE_CLEAR](state) {
            state.modes = []
        },
        [types.MODE_SET_LOADING](state, loading) {
            state.modesLoading = loading
        },
        [types.MODE_META](state, meta) {
            state.modesMeta = meta
        },
    },
    actions
}
