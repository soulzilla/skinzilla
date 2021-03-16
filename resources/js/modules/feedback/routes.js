/**
 * AutoImporting components
 * @param path
 * @returns {function(): *}
 */
const page = path => () => import(/* webpackChunkName: '' */ `./components/${path}`).then(m => m.default || m)

export const routes = [
    {
        path: '/admin/feedback',
        name: 'Feedback',
        component: page('FeedbackList'),
    },
    {
        path: '/admin/feedback/:id',
        name: 'Show Feedback',
        component: page('FeedbackView'),
        hidden: true
    }
]
