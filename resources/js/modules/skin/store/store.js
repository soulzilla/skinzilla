import * as types from './types'
import {actions} from './actions'

export const store = {
    state: {
        skins: [],
        skinsMeta: [],
        skinsLoading: true,
    },
    getters: {
        skins: state => state.skins,
        skinsMeta: state => state.skinsMeta,
        skinsLoading: state => state.skinsLoading,
    },
    mutations: {
        [types.SKIN_OBTAIN](state, skins) {
            state.skins = skins
        },
        [types.SKIN_CLEAR](state) {
            state.skins = []
        },
        [types.SKIN_SET_LOADING](state, loading) {
            state.skinsLoading = loading
        },
        [types.SKIN_META](state, meta) {
            state.skinsMeta = meta
        },
    },
    actions
}
