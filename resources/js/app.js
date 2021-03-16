import Vue from 'vue'
import App from './core/App'
import ElementUI from 'element-ui'
import i18n from './bootstrap/i18n'
import router from './bootstrap/router'
import store from './core/store'
import globalMixin from './includes/mixins/globalMixin'
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue'
import VueQuillEditor from 'vue-quill-editor'
import VueClipboard from 'vue-clipboard2'
import VueToast from 'vue-toast-notification'
import VueScrollTo from 'vue-scrollto'

import './bootstrap/auth'
import './bootstrap/moment'
import 'quill/dist/quill.core.css'
import 'quill/dist/quill.snow.css'
import 'vue-toast-notification/dist/theme-sugar.css'

try {
    window.Popper = require('popper.js').default
    window.$ = window.jQuery = require('jquery')
    require('bootstrap')
    window._ = require('lodash')
    window.moment = require('moment')
} catch (e) {
    console.log('Error load main libraries')
}

Vue.use(ElementUI, {i18n: (key, value) => i18n.t(key, value)})
Vue.use(BootstrapVue)
Vue.use(BootstrapVueIcons)
Vue.use(VueQuillEditor, /* { default global options } */)
Vue.use(VueClipboard)
Vue.use(VueToast, {
    position: 'bottom-left',
    duration: 4000
})
Vue.use(VueScrollTo)

Vue.prototype.config = window.config

Vue.mixin(globalMixin);

window.Vue = new Vue({
    router,
    store,
    i18n,
    render: h => h(App)
}).$mount('#app')
