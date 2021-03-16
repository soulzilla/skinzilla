/**
 * AutoImporting components
 * @param path
 * @returns {function(): *}
 */
const page = path => () => import(/* webpackChunkName: '' */ `./components/${path}`).then(m => m.default || m)

export const routes = [
    {
        path: '/admin/videos',
        name: 'Videos',
        component: page('VideoList'),
        iconCls: 'el-icon-film'
    },
    {
        path: '/admin/videos/:id',
        name: 'Show Video',
        component: page('VideoView'),
        hidden: true
    }
]
