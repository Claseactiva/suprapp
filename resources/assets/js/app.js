require('bootstrap')
import Vue from 'vue';

// Vue.config.devtools = false
// Vue.config.debug = false
// Vue.config.silent = true


import { ValidationProvider } from 'vee-validate'
import Vue2Filters from 'vue2-filters'
import vSelect from 'vue-select'

Vue.component('v-select', vSelect)
Vue.component('ValidationProvider', ValidationProvider)
Vue.use(Vue2Filters)

import store from './components/store/index'

Vue.component('vehicle-mechanic-component', require('./components/VehicleMechanic/Index.vue').default)
Vue.component('vehicle-component', require('./components/Vehicle/Index.vue').default)
Vue.component('vehicle-brand-component', require('./components/VehicleBrand/Index.vue').default)
Vue.component('vehicle-model-component', require('./components/VehicleModel/Index.vue').default)

//seccion de roles y permisos para permisos
Vue.component('componente-usuario', require('./components/User/Index.vue').default)
Vue.component('componente-mecanico-usuario', require('./components/UserMechanic/Index.vue').default)
Vue.component('roles-component', require('./components/Roles/Roles.vue').default)

Vue.component('notes-component', require('./components/Note/Index.vue').default)
// Vue.component('quotations-component', require('./components/Quotation/Index.vue').default)
Vue.component('quotationsclient-component', require('./components/Quotationclient/Index.vue').default)
Vue.component('quotationuser-component', require('./components/Quotationuser/Index.vue').default)
Vue.component('quotationexpress-component', require('./components/Quotationexpress/Index.vue').default)
Vue.component('quotationshipping-component', require('./components/QuotationShipping/Index.vue').default)

Vue.component('imports-component', require('./components/Import/Index.vue').default)

Vue.component('clients-component', require('./components/Client/Index.vue').default)
Vue.component('product-component', require('./components/Product/Index.vue').default)
Vue.component('inventario-component', require('./components/Inventario/Index.vue').default)

Vue.component('detalle-usuario-component', require('./components/User/Detalle.vue').default)
Vue.component('sales-component', require('./components/Sales/Index.vue').default)
Vue.component('utilidad-component', require('./components/Utilidad/Index.vue').default)
Vue.component('envios-component', require('./components/Quotationclient/ListarQuotationShipping.vue').default)

Vue.component('ordentrabajos-component', require('./components/OrdenTrabajos/Index.vue').default)
Vue.component('checklist-component', require('./components/Check-List/Index.vue').default)

Vue.component('lista-precios-component', require('./components/ListaPrecios/Index.vue').default)

/**
 * Boleta components
 */
Vue.component('boleta-component', require('./components/Boleta/Index.vue').default);



new Vue({
    el: '#app',
    store: store,
    ready: function () {
        this.$nextTick(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
    }
});