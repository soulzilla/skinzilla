/**
 * AutoImporting components
 * @param path
 * @returns {function(): *}
 */
const page = path => () => import(/* webpackChunkName: '' */ `./components/${path}`).then(m => m.default || m)

export const routes = [
    {
        path: '/admin/banners',
        name: 'Banners',
        component: page('BannerList'),
        iconCls: 'el-icon-data-board'
    },
    {
        path: '/admin/banners/:id',
        name: 'Show Banner',
        component: page('BannerView'),
        hidden: true
    }
]
