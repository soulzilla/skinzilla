/**
 * AutoImporting components
 * @param path
 * @returns {function(): *}
 */
const page = path => () => import(/* webpackChunkName: '' */ `./components/${path}`).then(m => m.default || m)

export const routes = [
    {
        path: '/admin/users',
        name: 'Users',
        component: page('UserList'),
        iconCls: 'el-icon-s-custom'
    },
    {
        path: '/admin/users/:id',
        name: 'Show User',
        component: page('UserView'),
        hidden: true
    }
]
