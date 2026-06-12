import Vuex from 'vuex';
import Vue from 'vue';

import es from 'vee-validate/dist/locale/es'
import VeeValidate, {
    Validator
} from 'vee-validate'

Validator.localize('en', es) // changes the locale

Vue.use(Vuex)
Vue.use(require('vue-moment'))
Vue.use(VeeValidate, {
    classes: true,
})

import state from './state';
import getters from './getters';
import actions from './actions';
import mutations from './mutations';

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations,
})
