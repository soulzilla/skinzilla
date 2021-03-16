/**
 * AutoImporting components
 * @param path
 * @returns {function(): *}
 */
const page = path => () => import(/* webpackChunkName: '' */ `./components/${path}`).then(m => m.default || m)

export const routes = [
    {
        path: '/admin/boxes',
        name: 'Boxes',
        component: page('BoxList'),
        iconCls: 'el-icon-box'
    },
    {
        path: '/admin/boxes/:id',
        name: 'Show Box',
        component: page('BoxView'),
        hidden: true
    }
]
