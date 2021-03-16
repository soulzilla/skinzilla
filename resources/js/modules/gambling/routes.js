/**
 * AutoImporting components
 * @param path
 * @returns {function(): *}
 */
const page = path => () => import(/* webpackChunkName: '' */ `./components/${path}`).then(m => m.default || m)

export const routes = [
    {
        path: '/admin/gamblings',
        name: 'Gamblings',
        component: page('GamblingList'),
        iconCls: 'el-icon-goods'
    },
    {
        path: '/admin/gamblings/:id',
        name: 'Show Gambling',
        component: page('GamblingView'),
        hidden: true
    }
]
