import * as types from './types'
import {actions} from './actions'

export const store = {
    state: {
        comments: [],
        commentsMeta: [],
        commentsLoading: true,
    },
    getters: {
        comments: state => state.comments,
        commentsMeta: state => state.commentsMeta,
        commentsLoading: state => state.commentsLoading,
    },
    mutations: {
        [types.COMMENT_OBTAIN](state, comments) {
            state.comments = comments
        },
        [types.COMMENT_CLEAR](state) {
            state.comments = []
        },
        [types.COMMENT_SET_LOADING](state, loading) {
            state.commentsLoading = loading
        },
        [types.COMMENT_META](state, meta) {
            state.commentsMeta = meta
        },
    },
    actions
}
