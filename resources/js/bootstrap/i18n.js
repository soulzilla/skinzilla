import Vue from 'vue'
import VueI18n from 'vue-i18n'
import messages from './../includes/lang'
import axios from 'axios'
import {changeMomentLocale} from "./moment";

Vue.use(VueI18n)

const DEFAULT_LANGUAGE = 'ru'

changeMomentLocale('ru')

const i18n = new VueI18n({
    locale: 'ru',
    messages,
    silentTranslationWarn: true
})

setI18nLanguage('ru', i18n)

function setI18nLanguage (lang, i18n) {
    i18n.locale = lang
    axios.defaults.headers.common['Accept-Language'] = lang
    document.querySelector('html').setAttribute('lang', lang)
    return lang
}

export default i18n
