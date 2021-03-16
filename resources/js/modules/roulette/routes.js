/**
 * AutoImporting components
 * @param path
 * @returns {function(): *}
 */
const page = path => () => import(/* webpackChunkName: '' */ `./components/${path}`).then(m => m.default || m)

export const routes = [
    {
        path: '/admin/roulettes',
        name: 'Roulettes',
        component: page('RouletteList'),
        iconCls: 'el-icon-help'
    },
    {
        path: '/admin/roulettes/:id',
        name: 'Show Roulette',
        component: page('RouletteView'),
        hidden: true
    }
]
