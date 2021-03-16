/**
 * AutoImporting components
 * @param path
 * @returns {function(): *}
 */
const page = path => () => import(/* webpackChunkName: '' */ `./components/${path}`).then(m => m.default || m)

export const routes = [
    {
        path: '/admin/skins',
        name: 'Skins',
        component: page('SkinList'),
        iconCls: 'el-icon-aim'
    },
    {
        path: '/admin/skins/:id',
        name: 'Show Skin',
        component: page('SkinView'),
        hidden: true
    }
]
