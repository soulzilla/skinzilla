import * as types from './types'
import {actions} from './actions'

export const store = {
    state: {
        users: [],
        usersMeta: [],
        usersLoading: true,
    },
    getters: {
        users: state => state.users,
        usersMeta: state => state.usersMeta,
        usersLoading: state => state.usersLoading,
    },
    mutations: {
        [types.USER_OBTAIN](state, users) {
            state.users = users
        },
        [types.USER_CLEAR](state) {
            state.users = []
        },
        [types.USER_SET_LOADING](state, loading) {
            state.usersLoading = loading
        },
        [types.USER_META](state, meta) {
            state.usersMeta = meta
        },
    },
    actions
}
