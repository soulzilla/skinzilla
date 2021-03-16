import * as types from './types'
import {actions} from './actions'

export const store = {
    state: {
        videos: [],
        videosMeta: [],
        videosLoading: true,
    },
    getters: {
        videos: state => state.videos,
        videosMeta: state => state.videosMeta,
        videosLoading: state => state.videosLoading,
    },
    mutations: {
        [types.VIDEO_OBTAIN](state, videos) {
            state.videos = videos
        },
        [types.VIDEO_CLEAR](state) {
            state.videos = []
        },
        [types.VIDEO_SET_LOADING](state, loading) {
            state.videosLoading = loading
        },
        [types.VIDEO_META](state, meta) {
            state.videosMeta = meta
        },
    },
    actions
}
