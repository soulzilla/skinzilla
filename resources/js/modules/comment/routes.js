/**
 * AutoImporting components
 * @param path
 * @returns {function(): *}
 */
const page = path => () => import(/* webpackChunkName: '' */ `./components/${path}`).then(m => m.default || m)

export const routes = [
    {
        path: '/admin/comments',
        name: 'Comments',
        component: page('CommentList'),
        iconCls: 'el-icon-s-comment'
    },
    {
        path: '/admin/comments/:id',
        name: 'Show Comment',
        component: page('CommentView'),
        hidden: true
    }
]
