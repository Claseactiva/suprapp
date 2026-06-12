<template>
    <div class="col-12">
        <h5 class="text-white">
            Envios
        </h5>
        <div class="input-group">
            <input type="text" class="form-control" v-model="linkenvio.url" readonly=readonly>
            <input type="hidden" id="testing-code" :value="linkenvio.url">
            <div class="input-group-append">
                <button class="btn btn-info btn-block" @click.stop.prevent="copyTestingCode">Copiar</button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-responsive-new table-dark table-sm mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Enviado</th>
                        <th>Nombre</th>
                        <th>RUT</th>
                        <th>Telefono</th>
                        <th>Ciudad</th>
                        <th>Dirección</th>
                        <th>Sucursal</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="10">
                            <input type="text" class="form-control" style="width : 60px" v-model="searchShipping.id"
                                @keyup="getQuotationShipping">
                        </td>
                    </tr>
                    <tr v-for="quotationshippingLocal in quotationshipping" :key="quotationshippingLocal.id">

                        <td data-table-label="ID">{{ quotationshippingLocal.id }}</td>
                        <td data-table-label="ENVIADO" v-if="quotationshippingLocal.enviado > 0">

                            <button class="button"
                                @click.prevent="deleteEnviado({ id: quotationshippingLocal.id })"><span>Enviado</span></button>

                        </td>
                        <td data-table-label="ENVIADO" v-else>

                            <input type="checkbox" :id="quotationshippingLocal.id" :value="quotationshippingLocal.id"
                                v-model="checkEnviado">
                            <label :for="quotationshippingLocal.id"></label>

                        </td>


                        <td data-table-label="Nombre">{{ quotationshippingLocal.nombre }}</td>
                        <td data-table-label="rut">{{ quotationshippingLocal.rut }}</td>
                        <td data-table-label="telefono">{{ quotationshippingLocal.telefono }}</td>
                        <td data-table-label="ciudad">{{ quotationshippingLocal.ciudad }}</td>
                        <td data-table-label="direccion">{{ quotationshippingLocal.direccion }}</td>
                        <td data-table-label="sucursal">{{ quotationshippingLocal.sucursal }}</td>
                        <td data-table-label="fecha">{{ quotationshippingLocal.created_at }}</td>
                        <td class="text-right">
                            <a class="btn btn-primary btn-sm" href="#" role="button"
                                @click.prevent="editFacebook({ quotationshippingLocal })"><i
                                    class="fab fa-facebook-f"></i>
                            </a>
                            <a class="btn btn-success btn-sm"
                                :href="'https://wa.me/+569' + quotationshippingLocal.telefono" target="_blank"
                                role="button"><i class="fab fa-whatsapp"></i></a>
                            <a class="btn btn-secondary btn-sm" href="#" role="button"
                                @click.prevent="showQuotationShipping({ id: quotationshippingLocal.id })"><i
                                    class="fas fa-shipping-fast"></i>
                            </a>
                            <a class="btn btn-info btn-sm" href="#" role="button"
                                @click.prevent="pdfQuotationShipping({ id: quotationshippingLocal.id })"><i
                                    class="far fa-file-alt"></i>
                            </a>
                            <a class="btn btn-danger btn-sm" href="#" role="button"
                                @click.prevent="showdeleteQuotationShipping({ id: quotationshippingLocal.id })"><i
                                    class="far fa-trash-alt"></i>
                            </a>

                        </td>
                    </tr>
                </tbody>
            </table>

            <nav>
                <ul class="pagination">
                    <li class="page-item" v-if="pagination_shipping.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#"
                            @click.prevent="changePageQuotationShipping({ page: 1 })">
                            <span>Primera</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_shipping.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#"
                            @click.prevent="changePageQuotationShipping({ page: pagination_shipping.current_page - 1 })">
                            <span>Atrás</span>
                        </a>
                    </li>

                    <li class="page-item" v-for="page in pagesNumber_shipping"
                        v-bind:class="[page == isActived_shipping ? 'active' : '']" :key="page">
                        <a class="page-link border-light bg-dark" href="#"
                            @click.prevent="changePageQuotationShipping({ page })">
                            {{ page }}
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_shipping.current_page < pagination_shipping.last_page">
                        <a class="page-link border-light bg-dark" href="#"
                            @click.prevent="changePageQuotationShipping({ page: pagination_shipping.current_page + 1 })">
                            <span>Siguiente</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_shipping.current_page < pagination_shipping.last_page">
                        <a class="page-link border-light bg-dark" href="#"
                            @click.prevent="changePageQuotationShipping({ page: pagination_shipping.last_page })">
                            <span>Última</span>
                        </a>
                    </li>
                </ul>
            </nav>
            <EliminarShipping></EliminarShipping>
            <EnvioShipping></EnvioShipping>
            <EditFacebook></EditFacebook>
        </div>
    </div>
</template>

<script>

import EliminarShipping from '../QuotationShipping/EliminarShipping'
import EnvioShipping from '../QuotationShipping/EnvioShipping'
import EditFacebook from '../QuotationShipping/EditFacebook'
import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapActions, mapGetters } from 'vuex'
import toastr from 'toastr'

export default {
    components: { EliminarShipping, EnvioShipping, EditFacebook },
    computed: {
        ...mapState(['quotationshipping', 'linkenvio', 'errorsLaravel', 'pagination_shipping', 'offset_shipping', 'searchShipping', 'checkEnviado']),
        ...mapGetters(['isActived_shipping', 'pagesNumber_shipping']),
        checkEnviado: {
            get() {
                return this.$store.state.checkEnviado
            },
            set(value) {
                this.$store.commit('setCheckEnviado', value)
                this.$store.commit('getQuotationShipping', 1)
            }
        },

    },
    methods: {
        ...mapActions(['getQuotationShipping', 'pdfQuotationShipping', 'showdeleteQuotationShipping', 'showQuotationShipping', 'changePageQuotationShipping', 'editFacebook', 'deleteEnviado']),
        copyTestingCode() {
            let testingCodeToCopy = document.querySelector('#testing-code')
            testingCodeToCopy.setAttribute('type', 'text')    // 不是 hidden 才能複製
            testingCodeToCopy.select()

            try {
                var successful = document.execCommand('copy');
                var msg = successful ? ' con exito' : ' sin exito';
                toastr.success('Se copio el link' + msg)
            } catch (err) {
                toastr.error('No se pudo copiar el link')
            }

            /* unselect the range */
            testingCodeToCopy.setAttribute('type', 'hidden')
            window.getSelection().removeAllRanges()
        },
    },
    created() {
        this.$store.dispatch('getQuotationShipping', { page: 1 }),
            //this.$store.dispatch('getQuotationShipping'),
            this.$store.dispatch('getQuotationlinkenvio')
    }

}
</script>
<style>
.button {
    border-radius: 0.2rem;
    background-color: #28a745;
    border: none;
    color: #FFFFFF;
    text-align: center;
    font-size: 0.875rem;
    padding: 0.25rem 0.5rem;
    width: 100%;
    transition: all 0.5s;
    cursor: pointer;
}


.button:hover {
    background-color: #dc3545;
}

.button:hover span {
    display: none
}

.button:hover:before {
    content: '\26CC'
}

.button:hover span {
    padding-right: 25px;
}

.button:hover span:after {
    opacity: 1;
    right: 0;
}
</style>