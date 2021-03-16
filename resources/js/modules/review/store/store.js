import * as types from './types'
import {actions} from './actions'

export const store = {
    state: {
        reviews: [],
        reviewsMeta: [],
        reviewsLoading: true,
    },
    getters: {
        reviews: state => state.reviews,
        reviewsMeta: state => state.reviewsMeta,
        reviewsLoading: state => state.reviewsLoading,
    },
    mutations: {
        [types.REVIEW_OBTAIN](state, reviews) {
            state.reviews = reviews
        },
        [types.REVIEW_CLEAR](state) {
            state.reviews = []
        },
        [types.REVIEW_SET_LOADING](state, loading) {
            state.reviewsLoading = loading
        },
        [types.REVIEW_META](state, meta) {
            state.reviewsMeta = meta
        },
    },
    actions
}
