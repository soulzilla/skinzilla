/**
 * AutoImporting components
 * @param path
 * @returns {function(): *}
 */
const page = path => () => import(/* webpackChunkName: '' */ `./components/${path}`).then(m => m.default || m)

export const routes = [
    {
        path: '/admin/markets',
        name: 'Markets',
        component: page('MarketList'),
    },
    {
        path: '/admin/markets/:id',
        name: 'Show Market',
        component: page('MarketView'),
        hidden: true
    }
]
