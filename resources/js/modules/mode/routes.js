/**
 * AutoImporting components
 * @param path
 * @returns {function(): *}
 */
const page = path => () => import(/* webpackChunkName: '' */ `./components/${path}`).then(m => m.default || m)

export const routes = [
    {
        path: '/admin/modes',
        name: 'Modes',
        component: page('ModeList'),
        iconCls: 'el-icon-info'
    },
    {
        path: '/admin/modes/:id',
        name: 'Show Mode',
        component: page('ModeView'),
        hidden: true
    }
]
