/**
 * AutoImporting components
 * @param path
 * @returns {function(): *}
 */
const page = path => () => import(/* webpackChunkName: '' */ `./components/${path}`).then(m => m.default || m)

export const routes = [
    {
        path: '/admin/reviews',
        name: 'Reviews',
        component: page('ReviewList'),
    },
    {
        path: '/admin/reviews/:id',
        name: 'Show Review',
        component: page('ReviewView'),
        hidden: true
    }
]
