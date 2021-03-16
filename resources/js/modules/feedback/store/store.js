import * as types from './types'
import {actions} from './actions'

export const store = {
    state: {
        feedback: [],
        feedbackMeta: [],
        feedbackLoading: true,
    },
    getters: {
        feedback: state => state.feedback,
        feedbackMeta: state => state.feedbackMeta,
        feedbackLoading: state => state.feedbackLoading,
    },
    mutations: {
        [types.FEEDBACK_OBTAIN](state, feedback) {
            state.feedback = feedback
        },
        [types.FEEDBACK_CLEAR](state) {
            state.feedback = []
        },
        [types.FEEDBACK_SET_LOADING](state, loading) {
            state.feedbackLoading = loading
        },
        [types.FEEDBACK_META](state, meta) {
            state.feedbackMeta = meta
        },
    },
    actions
}
