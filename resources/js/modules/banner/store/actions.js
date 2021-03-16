import * as types from './types'
import bannerApi from '../api'

export const actions = {
    async [types.BANNER_FETCH]({commit}, data = null) {
        commit(types.BANNER_SET_LOADING, true)
        const response = await bannerApi.all(data)
        commit(types.BANNER_OBTAIN, response.data.data)
        commit(types.BANNER_META, response.data.meta)
        commit(types.BANNER_SET_LOADING, false)
    },
}
