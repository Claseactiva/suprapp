<template>
    <div class="col-12 quotationshipping-admin">
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
        <div class="quotationshipping-filter-row mt-3">
            <input type="text" class="form-control form-control-sm quotationshipping-filter-input" placeholder="ID"
                v-model="searchShipping.id" @keyup="getQuotationShipping({ page: 1, per_page: pagination_shipping.per_page })">

            <input type="text" class="form-control form-control-sm quotationshipping-filter-input" placeholder="Nombre"
                v-model="searchShipping.nombre" @keyup="getQuotationShipping({ page: 1, per_page: pagination_shipping.per_page })">

            <input type="text" class="form-control form-control-sm quotationshipping-filter-input" placeholder="RUT"
                v-model="searchShipping.rut" @keyup="getQuotationShipping({ page: 1, per_page: pagination_shipping.per_page })">

            <input type="text" class="form-control form-control-sm quotationshipping-filter-input" placeholder="Telefono"
                v-model="searchShipping.telefono" @keyup="getQuotationShipping({ page: 1, per_page: pagination_shipping.per_page })">

            <input type="text" class="form-control form-control-sm quotationshipping-filter-input" placeholder="Ciudad"
                v-model="searchShipping.ciudad" @keyup="getQuotationShipping({ page: 1, per_page: pagination_shipping.per_page })">
        </div>
        <div class="table-responsive">
            <table class="table table-responsive-new table-dark table-sm mt-3">
                <thead>
                    <tr>
                        <th width="4%">ID</th>
                        <th width="5%">Enviado</th>
                        <th width="15%">Nombre</th>
                        <th width="8%">RUT</th>
                        <th width="9%">Telefono</th>
                        <th width="9%">Ciudad</th>
                        <th width="16%">Dirección</th>
                        <th width="15%">Sucursal</th>
                        <th width="8%">Fecha</th>
                        <th width="11%">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="quotationshippingLocal in quotationshipping" :key="quotationshippingLocal.id">

                        <td data-table-label="ID">{{ quotationshippingLocal.id }}</td>
                        <td data-table-label="ENVIADO" v-if="quotationshippingLocal.enviado > 0">
                            <button type="button" class="btn btn-sm quotationshipping-icon-btn quotationshipping-enviado-btn"
                                @click.prevent="deleteEnviado({ id: quotationshippingLocal.id })"
                                data-toggle="tooltip" data-placement="top" title="Enviado (clic para deshacer)">
                                <i class="fas fa-check"></i>
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                        <td data-table-label="ENVIADO" v-else>

                            <input type="checkbox" :id="quotationshippingLocal.id" :value="quotationshippingLocal.id"
                                v-model="checkEnviado">
                            <label :for="quotationshippingLocal.id"></label>

                        </td>


                        <td data-table-label="Nombre" class="quotationshipping-wrap-cell"
                            :title="quotationshippingLocal.nombre">{{ quotationshippingLocal.nombre }}</td>
                        <td data-table-label="rut">{{ quotationshippingLocal.rut }}</td>
                        <td data-table-label="telefono">{{ quotationshippingLocal.telefono }}</td>
                        <td data-table-label="ciudad">{{ quotationshippingLocal.ciudad }}</td>
                        <td data-table-label="direccion" class="quotationshipping-wrap-cell"
                            :title="quotationshippingLocal.direccion">{{ quotationshippingLocal.direccion }}</td>
                        <td data-table-label="sucursal" class="quotationshipping-wrap-cell"
                            :title="quotationshippingLocal.sucursal">{{ quotationshippingLocal.sucursal }}</td>
                        <td data-table-label="fecha">{{ quotationshippingLocal.created_at | moment('DD/MM/YYYY H:mm') }}</td>
                        <td class="quotationshipping-actions-cell">
                            <div class="btn-group dropleft quotationshipping-icon-group">
                                <button type="button"
                                    class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split quotationshipping-icon-btn"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Contacto">
                                    <i class="fas fa-comments"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right quotationshipping-menu">
                                    <a href="#" class="dropdown-item" @click.prevent="editFacebook({ quotationshippingLocal })">
                                        <i class="fab fa-facebook-f mr-2"></i>Facebook
                                    </a>
                                    <a class="dropdown-item" :href="'https://wa.me/+569' + quotationshippingLocal.telefono"
                                        target="_blank">
                                        <i class="fab fa-whatsapp mr-2"></i>WhatsApp
                                    </a>
                                </div>
                            </div>

                            <a class="btn btn-secondary btn-sm quotationshipping-icon-btn" href="#" role="button"
                                @click.prevent="showQuotationShipping({ id: quotationshippingLocal.id })"
                                data-toggle="tooltip" data-placement="top" title="Ver envío">
                                <i class="fas fa-shipping-fast"></i>
                            </a>

                            <a class="btn btn-info btn-sm quotationshipping-icon-btn" href="#" role="button"
                                @click.prevent="pdfQuotationShipping({ id: quotationshippingLocal.id })"
                                data-toggle="tooltip" data-placement="top" title="PDF">
                                <i class="far fa-file-alt"></i>
                            </a>

                            <a class="btn btn-danger btn-sm quotationshipping-icon-btn" href="#" role="button"
                                @click.prevent="showdeleteQuotationShipping({ id: quotationshippingLocal.id })"
                                data-toggle="tooltip" data-placement="top" title="Eliminar">
                                <i class="far fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="table-list-toolbar">
                <div class="table-list-toolbar__rows">
                    <span>Filas</span>
                    <select class="custom-select custom-select-sm" v-model.number="pagination_shipping.per_page"
                        @change="getQuotationShipping({ page: 1, per_page: pagination_shipping.per_page })">
                        <option :value="10">10</option>
                        <option :value="20">20</option>
                        <option :value="50">50</option>
                    </select>
                </div>
                <nav>
                <ul class="pagination">
                    <li class="page-item" v-if="pagination_shipping.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#"
                            @click.prevent="changePageQuotationShipping({ page: 1, per_page: pagination_shipping.per_page })">
                            <span>Primera</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_shipping.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#"
                            @click.prevent="changePageQuotationShipping({ page: pagination_shipping.current_page - 1, per_page: pagination_shipping.per_page })">
                            <span>Atrás</span>
                        </a>
                    </li>

                    <li class="page-item" v-for="page in pagesNumber_shipping"
                        v-bind:class="[page == isActived_shipping ? 'active' : '']" :key="page">
                        <a class="page-link border-light bg-dark" href="#"
                            @click.prevent="changePageQuotationShipping({ page, per_page: pagination_shipping.per_page })">
                            {{ page }}
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_shipping.current_page < pagination_shipping.last_page">
                        <a class="page-link border-light bg-dark" href="#"
                            @click.prevent="changePageQuotationShipping({ page: pagination_shipping.current_page + 1, per_page: pagination_shipping.per_page })">
                            <span>Siguiente</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_shipping.current_page < pagination_shipping.last_page">
                        <a class="page-link border-light bg-dark" href="#"
                            @click.prevent="changePageQuotationShipping({ page: pagination_shipping.last_page, per_page: pagination_shipping.per_page })">
                            <span>Última</span>
                        </a>
                    </li>
                </ul>
                </nav>
            </div>
            <EliminarShipping></EliminarShipping>
            <EnvioShipping></EnvioShipping>
            <EditFacebook></EditFacebook>
        </div>
        <div v-if="pdfPreviewUrl" class="quotation-pdf-preview" @click.self="resetPdfPreview">
            <div class="quotation-pdf-preview__dialog">
                <div class="quotation-pdf-preview__header bg-dark text-white">
                    <h5 class="mb-0">{{ pdfPreviewTitle }}</h5>
                    <button type="button" class="close text-white" aria-label="Close" @click="resetPdfPreview">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="quotation-pdf-preview__body">
                    <iframe :src="pdfPreviewUrl" class="quotation-pdf-preview__frame" title="Vista previa PDF"></iframe>
                </div>
            </div>
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
    data() {
        return {
            pdfPreviewUrl: '',
            pdfPreviewTitle: '',
        }
    },
    computed: {
        ...mapState(['quotationshipping', 'linkenvio', 'errorsLaravel', 'pagination_shipping', 'offset_shipping', 'searchShipping', 'checkEnviado']),
        ...mapGetters(['isActived_shipping', 'pagesNumber_shipping']),
        checkEnviado: {
            get() {
                return this.$store.state.checkEnviado
            },
            set(value) {
                this.$store.commit('setCheckEnviado', value)
            }
        },

    },
    methods: {
        ...mapActions(['getQuotationShipping', 'showdeleteQuotationShipping', 'showQuotationShipping', 'changePageQuotationShipping', 'editFacebook', 'deleteEnviado']),
        pdfQuotationShipping({ id }) {
            this.openPdfPreview(`quotationshipping-pdf/${id}`, `PDF Envío N°${id}`)
        },
        openPdfPreview(url, title) {
            const separator = url.indexOf('?') === -1 ? '?' : '&'
            this.pdfPreviewTitle = title
            this.pdfPreviewUrl = `${url}${separator}preview=${Date.now()}`
        },
        resetPdfPreview() {
            this.pdfPreviewUrl = ''
            this.pdfPreviewTitle = ''
        },
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

.quotationshipping-filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.quotationshipping-filter-input {
    max-width: 160px;
    font-size: 0.78rem;
    padding: 0.2rem 0.4rem;
    height: auto;
}

@media (min-width: 992px) {
    .quotationshipping-admin .table {
        table-layout: fixed;
    }

    .quotationshipping-admin .table th,
    .quotationshipping-admin .table td {
        white-space: nowrap;
        vertical-align: middle;
    }

    .quotationshipping-admin .quotationshipping-wrap-cell {
        white-space: nowrap !important;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .quotationshipping-admin .quotationshipping-actions-cell {
        text-align: right;
    }

    .quotationshipping-admin .quotationshipping-icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        padding: 0;
        margin-right: 0.18rem;
        border-radius: 0.2rem;
    }

    .quotationshipping-admin .quotationshipping-icon-group {
        vertical-align: middle;
        margin-right: 0.18rem;
    }

    .quotationshipping-admin .quotationshipping-icon-group .quotationshipping-icon-btn {
        width: 26px;
        margin-right: 0;
    }

    .quotationshipping-admin .quotationshipping-menu {
        min-width: 10rem;
        font-size: 0.85rem;
    }

    .quotationshipping-admin .quotationshipping-enviado-btn {
        background-color: #28a745;
        border: none;
        color: #fff;
        margin-right: 0;
    }

    .quotationshipping-admin .quotationshipping-enviado-btn .fa-times {
        display: none;
    }

    .quotationshipping-admin .quotationshipping-enviado-btn:hover {
        background-color: #dc3545;
    }

    .quotationshipping-admin .quotationshipping-enviado-btn:hover .fa-check {
        display: none;
    }

    .quotationshipping-admin .quotationshipping-enviado-btn:hover .fa-times {
        display: inline;
    }
}

.quotation-pdf-preview {
    position: fixed;
    inset: 0;
    z-index: 1065;
    background: rgba(0, 0, 0, 0.78);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem;
}

.quotation-pdf-preview__dialog {
    width: min(98vw, 1600px);
    height: calc(100vh - 1.5rem);
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
    display: flex;
    flex-direction: column;
}

.quotation-pdf-preview__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
}

.quotation-pdf-preview__body {
    flex: 1;
    background: #d9d9d9;
}

.quotation-pdf-preview__frame {
    width: 100%;
    height: 100%;
    border: 0;
    background: #fff;
}
</style>
