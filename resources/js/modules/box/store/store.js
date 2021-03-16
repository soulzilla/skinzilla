import * as types from './types'
import {actions} from './actions'

export const store = {
    state: {
        boxes: [],
        boxesMeta: [],
        boxesLoading: true,
    },
    getters: {
        boxes: state => state.boxes,
        boxesMeta: state => state.boxesMeta,
        boxesLoading: state => state.boxesLoading,
    },
    mutations: {
        [types.BOX_OBTAIN](state, boxes) {
            state.boxes = boxes
        },
        [types.BOX_CLEAR](state) {
            state.boxes = []
        },
        [types.BOX_SET_LOADING](state, loading) {
            state.boxesLoading = loading
        },
        [types.BOX_META](state, meta) {
            state.boxesMeta = meta
        },
    },
    actions
}
