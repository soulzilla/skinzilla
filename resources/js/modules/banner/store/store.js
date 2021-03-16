import * as types from './types'
import {actions} from './actions'

export const store = {
    state: {
        banners: [],
        bannersMeta: [],
        bannersLoading: true,
    },
    getters: {
        banners: state => state.banners,
        bannersMeta: state => state.bannersMeta,
        bannersLoading: state => state.bannersLoading,
    },
    mutations: {
        [types.BANNER_OBTAIN](state, banners) {
            state.banners = banners
        },
        [types.BANNER_CLEAR](state) {
            state.banners = []
        },
        [types.BANNER_SET_LOADING](state, loading) {
            state.bannersLoading = loading
        },
        [types.BANNER_META](state, meta) {
            state.bannersMeta = meta
        },
    },
    actions
}
