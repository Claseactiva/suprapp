<template>
    <div class="col-lg-12 purchaseorder-admin">
        <h5 class="text-white">
            Administración de Órdenes de Compra
        </h5>
        <div id="accordionPurchaseOrder">
            <div class="card">
                <div class="card-header p-0" id="headingPurchaseOrder">
                    <h5 class="mb-0">
                        <button id="btn-purchaseorder-card" class="btn btn-block text-left p-3" data-toggle="collapse"
                            data-target="#collapsePurchaseOrder" aria-expanded="true" aria-controls="collapsePurchaseOrder">
                            Nueva Orden de Compra
                            <span class="text-right"><i class="fas fa-arrows-alt-v"></i></span>
                        </button>
                    </h5>
                </div>

                <div id="collapsePurchaseOrder" class="collapse" aria-labelledby="headingPurchaseOrder" data-parent="#accordionPurchaseOrder">
                    <div class="card-body">
                        <form action="POST" v-on:submit.prevent="createPurchaseOrder">
                            <div class="row purchaseorder-form-layout">
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="proveedor">
                                            Proveedor
                                            <a href="#" class="ml-2" @click.prevent="showNewSupplierForm = !showNewSupplierForm">
                                                {{ showNewSupplierForm ? '– Cancelar' : '+ Nuevo proveedor' }}
                                            </a>
                                        </label>
                                        <SupplierSelector></SupplierSelector>
                                    </div>

                                    <div class="purchaseorder-supplier-preview" v-if="selectedSupplierDetails && !showNewSupplierForm">
                                        <p class="mb-1"><strong>RUT:</strong> {{ selectedSupplierDetails.rut }}</p>
                                        <p class="mb-1"><strong>Giro:</strong> {{ selectedSupplierDetails.giro }}</p>
                                        <p class="mb-1"><strong>Dirección:</strong> {{ selectedSupplierDetails.address }}<span v-if="selectedSupplierDetails.comuna">, {{ selectedSupplierDetails.comuna }}</span></p>
                                        <p class="mb-1" v-if="selectedSupplierDetails.contacto"><strong>Contacto:</strong> {{ selectedSupplierDetails.contacto }}</p>
                                        <p class="mb-0" v-if="selectedSupplierDetails.phone || selectedSupplierDetails.email">
                                            <strong>Teléfono/Email:</strong> {{ selectedSupplierDetails.phone }} <span v-if="selectedSupplierDetails.email">- {{ selectedSupplierDetails.email }}</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="col-12" v-if="showNewSupplierForm">
                                    <div class="purchaseorder-supplier-preview">
                                        <div class="row align-items-end">
                                            <div class="col-lg-3 col-md-6 mb-2">
                                                <label class="mb-1">RUT</label>
                                                <input type="text" class="form-control form-control-sm" v-model="newSupplier.rut">
                                            </div>
                                            <div class="col-lg-2 col-md-6 mb-2">
                                                <button type="button" class="btn btn-info btn-sm form-control" :class="{ disabled: siiLoading }"
                                                    @click.prevent="!siiLoading && searchSupplierSii()">
                                                    <span v-if="siiLoading"><i class="fas fa-spinner fa-spin"></i> Buscando...</span>
                                                    <span v-else><i class="far fa-edit"></i> Buscar en SII</span>
                                                </button>
                                            </div>
                                            <div class="col-lg-7 col-md-12 mb-2">
                                                <label class="mb-1">Razón Social</label>
                                                <input required type="text" class="form-control form-control-sm" v-model="newSupplier.razonSocial">
                                            </div>
                                            <div class="col-lg-12 mb-2">
                                                <label class="mb-1">Giro</label>
                                                <input type="text" class="form-control form-control-sm" v-model="newSupplier.giro">
                                            </div>
                                            <div class="col-lg-6 mb-2">
                                                <label class="mb-1">Dirección</label>
                                                <input type="text" class="form-control form-control-sm" v-model="newSupplier.address">
                                            </div>
                                            <div class="col-lg-6 mb-2">
                                                <label class="mb-1">Ciudad / Comuna</label>
                                                <input type="text" class="form-control form-control-sm" v-model="newSupplier.comuna">
                                            </div>
                                            <div class="col-lg-4 mb-2">
                                                <label class="mb-1">Contacto</label>
                                                <input type="text" class="form-control form-control-sm" v-model="newSupplier.contacto">
                                            </div>
                                            <div class="col-lg-4 mb-2">
                                                <label class="mb-1">Teléfono</label>
                                                <input type="text" class="form-control form-control-sm" v-model="newSupplier.phone">
                                            </div>
                                            <div class="col-lg-4 mb-2">
                                                <label class="mb-1">Email</label>
                                                <input type="text" class="form-control form-control-sm" v-model="newSupplier.email">
                                            </div>
                                            <div class="col-12 text-right">
                                                <button type="button" class="btn btn-success btn-sm" @click.prevent="submitNewSupplier">
                                                    <i class="fas fa-plus-square"></i> Crear proveedor
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <hr class="purchaseorder-form-divider">
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="mb-3">
                                        <label for="order_number">N° Orden de Compra <small v-if="nextOrderNumberPreview" class="text-muted">(OC actual: {{ nextOrderNumberPreview }})</small></label>
                                        <input type="text" name="order_number" class="form-control"
                                            v-model="newPurchaseOrder.order_number" :placeholder="nextOrderNumberPreview">
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="mb-3">
                                        <label for="buyer">Comprador</label>
                                        <input type="text" name="buyer" class="form-control"
                                            v-model="newPurchaseOrder.buyer">
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="mb-3">
                                        <label for="currency">Moneda</label>
                                        <input type="text" name="currency" class="form-control"
                                            v-model="newPurchaseOrder.currency" placeholder="PESO CHILENO">
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="mb-3">
                                        <label for="promised_date">Fecha Acordada</label>
                                        <input type="date" name="promised_date" class="form-control"
                                            v-model="newPurchaseOrder.promised_date">
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="mb-3">
                                        <label for="shipping_method">Método de Envío</label>
                                        <input type="text" name="shipping_method" class="form-control"
                                            v-model="newPurchaseOrder.shipping_method">
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="mb-3">
                                        <label for="pago">Forma de Pago</label>
                                        <SelectTiposPagos />
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="mb-3">
                                        <label for="requested_by">Solicitado Por</label>
                                        <input type="text" name="requested_by" class="form-control"
                                            v-model="newPurchaseOrder.requested_by">
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6">
                                    <div class="mb-3">
                                        <label for="ship_to">Enviar a</label>
                                        <input type="text" name="ship_to" class="form-control"
                                            v-model="newPurchaseOrder.ship_to" placeholder="Dirección de despacho">
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="observaciones">Observaciones</label>
                                        <textarea name="observaciones" class="form-control" rows="2"
                                            v-model="newPurchaseOrder.observaciones"></textarea>
                                    </div>
                                </div>

                                <div class="col-12 text-right">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-plus-square"></i> Guardar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-responsive-new table-dark table-sm mt-3">
                <thead>
                    <tr>
                        <th width="6%">ID</th>
                        <th width="9%">Estado</th>
                        <th width="10%">Rut</th>
                        <th width="20%">Razón Social</th>
                        <th width="17%">Producto</th>
                        <th width="13%">Fecha</th>
                        <th width="25%">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" class="form-control" placeholder="ID" v-model="searchPurchaseOrder.id"
                                @keyup="getPurchaseOrders({ page: 1 })">
                        </td>
                        <td>
                            <input type="text" class="form-control" placeholder="Estado" v-model="searchPurchaseOrder.state"
                                @keyup="getPurchaseOrders({ page: 1 })">
                        </td>
                        <td></td>
                        <td>
                            <input type="text" class="form-control" placeholder="Razon Social"
                                v-model="searchPurchaseOrder.razonSocial" @keyup="getPurchaseOrders({ page: 1 })">
                        </td>
                        <td>
                            <input type="text" class="form-control" placeholder="Ej: rodamiento"
                                v-model="searchPurchaseOrder.product" @keyup="getPurchaseOrders({ page: 1 })">
                        </td>
                        <td>
                            <date-picker v-model="dateRange" range value-type="format" format="YYYY-MM-DD"
                                placeholder="Rango de fechas" input-class="form-control" :clearable="true"
                                @change="getPurchaseOrders({ page: 1 })" @clear="getPurchaseOrders({ page: 1 })"></date-picker>
                        </td>
                        <td>
                            <div class="purchaseorder-per-page-inline" title="Filas por pagina">
                                <label for="purchaseorder-per-page" class="mb-0">Filas</label>
                                <select id="purchaseorder-per-page" class="form-control"
                                    v-model.number="searchPurchaseOrder.per_page"
                                    @change="getPurchaseOrders({ page: 1 })">
                                    <option :value="10">10</option>
                                    <option :value="20">20</option>
                                    <option :value="50">50</option>
                                </select>
                            </div>
                        </td>
                    </tr>

                    <tr v-for="purchaseOrderLocal in purchaseOrders" :key="purchaseOrderLocal.id">
                        <td data-table-label="ID">{{ purchaseOrderLocal.user_id === 1 ? purchaseOrderLocal.id : purchaseOrderLocal.correlativo }}</td>
                        <td data-table-label="Estado">{{ purchaseOrderLocal.state }}</td>
                        <td data-table-label="Rut">{{ purchaseOrderLocal.rut }}</td>
                        <td data-table-label="Razon Social" class="purchaseorder-wrap-cell" :title="purchaseOrderLocal.razonSocial || purchaseOrderLocal.supplier_text">
                            {{ purchaseOrderLocal.razonSocial || purchaseOrderLocal.supplier_text }}
                        </td>
                        <td data-table-label="Producto" class="purchaseorder-wrap-cell" :title="purchaseOrderLocal.product_preview">
                            {{ purchaseOrderLocal.product_preview }}
                        </td>
                        <td data-table-label="Fecha">{{ purchaseOrderLocal.created_at | moment('DD/MM/YYYY H:mm a') }}</td>
                        <td class="purchaseorder-actions-cell">
                            <a href="#" class="btn btn-info btn-sm purchaseorder-icon-btn"
                                @click.prevent="showModalPurchaseOrderDetail({ id: purchaseOrderLocal.id })"
                                data-toggle="tooltip" data-placement="top" title="Administrar detalle">
                                <i class="fas fa-list-ul"></i>
                            </a>

                            <a href="#" class="btn btn-secondary btn-sm purchaseorder-icon-btn"
                                @click.prevent="editPurchaseOrder({ purchaseOrder: purchaseOrderLocal })"
                                data-toggle="tooltip" data-placement="top" title="Editar cabecera">
                                <i class="fas fa-edit"></i>
                            </a>

                            <a href="#" class="btn btn-primary btn-sm purchaseorder-icon-btn"
                                @click.prevent="replicatePurchaseOrder({ id: purchaseOrderLocal.id })"
                                data-toggle="tooltip" data-placement="top" title="Duplicar">
                                <i class="far fa-copy"></i>
                            </a>

                            <a href="#" class="btn btn-danger btn-sm purchaseorder-icon-btn"
                                @click.prevent="showModalDeletePurchaseOrder({ id: purchaseOrderLocal.id })"
                                data-toggle="tooltip" data-placement="top" title="Eliminar">
                                <i class="far fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination">
                <li class="page-item" v-if="pagination_oc.current_page > 1">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePagePurchaseOrder({ page: 1 })">
                        <span>Primera</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination_oc.current_page > 1">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePagePurchaseOrder({ page: pagination_oc.current_page - 1 })">
                        <span>Atrás</span>
                    </a>
                </li>

                <li class="page-item" v-for="page in pagesNumber_oc" v-bind:class="[page == isActived_oc ? 'active' : '']"
                    :key="page">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePagePurchaseOrder({ page })">
                        {{ page }}
                    </a>
                </li>

                <li class="page-item" v-if="pagination_oc.current_page < pagination_oc.last_page">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePagePurchaseOrder({ page: pagination_oc.current_page + 1 })">
                        <span>Siguiente</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination_oc.current_page < pagination_oc.last_page">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePagePurchaseOrder({ page: pagination_oc.last_page })">
                        <span>Última</span>
                    </a>
                </li>
            </ul>
        </nav>

        <Detalle></Detalle>
        <DetalleEditar></DetalleEditar>
        <EditarPurchaseOrder></EditarPurchaseOrder>
        <Eliminar></Eliminar>
    </div>
</template>


<script>
import { mapState, mapActions, mapGetters } from 'vuex'
import { loadProgressBar } from 'axios-progress-bar'
import SupplierSelector from './SupplierSelector'
import Detalle from './Detalle'
import DetalleEditar from './DetalleEditar'
import EditarPurchaseOrder from './EditarPurchaseOrder'
import Eliminar from './Eliminar'
import SelectTiposPagos from '../Utilidad/SelectTiposPagos'
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'

export default {
    components: { SupplierSelector, Detalle, DetalleEditar, EditarPurchaseOrder, Eliminar, SelectTiposPagos, DatePicker },
    data() {
        return {
            showNewSupplierForm: false
        }
    },
    computed: {
        ...mapState(['purchaseOrders', 'suppliers', 'newPurchaseOrder', 'newSupplier', 'searchPurchaseOrder', 'pagination_oc', 'errorsLaravel', 'selectedSupplier', 'siiLoading', 'nextOrderNumberPreview']),
        ...mapGetters(['isActived_oc', 'pagesNumber_oc']),
        selectedSupplierDetails() {
            if (!this.selectedSupplier || !this.selectedSupplier.value) {
                return null
            }

            return this.suppliers.find(supplier => supplier.id === this.selectedSupplier.value) || null
        },
        dateRange: {
            get() {
                return [this.searchPurchaseOrder.date_from || null, this.searchPurchaseOrder.date_to || null]
            },
            set(value) {
                this.searchPurchaseOrder.date_from = (value && value[0]) || ''
                this.searchPurchaseOrder.date_to = (value && value[1]) || ''
            }
        }
    },
    methods: {
        ...mapActions(['getPurchaseOrders', 'createPurchaseOrder', 'showModalPurchaseOrderDetail', 'editPurchaseOrder',
            'replicatePurchaseOrder', 'showModalDeletePurchaseOrder', 'changePagePurchaseOrder',
            'getSuppliers', 'createSupplier', 'searchSupplierSii']),
        submitNewSupplier() {
            this.createSupplier()
            this.showNewSupplierForm = false
        }
    },
    created() {
        loadProgressBar();
        this.$store.dispatch('getPurchaseOrders', { page: 1 })
        this.$store.dispatch('getSuppliers')
        this.$store.dispatch('allPagos')
        this.$store.dispatch('getNextOrderNumber')
        this.newPurchaseOrder.buyer = window.authUserName || ''
    }
}
</script>

<style>
.purchaseorder-admin .mx-datepicker,
.purchaseorder-admin .mx-datepicker-range {
    width: 130px !important;
    max-width: 100%;
}

.purchaseorder-admin .mx-input {
    padding-left: 6px;
    padding-right: 20px;
    font-size: 0.68rem !important;
}

.purchaseorder-supplier-preview {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 0.25rem;
    padding: 0.5rem 0.75rem;
    margin-bottom: 0.75rem;
    font-size: 0.78rem;
}

@media (min-width: 992px) {
    .purchaseorder-admin {
        font-size: 0.88rem;
    }

    .purchaseorder-admin h5 {
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .purchaseorder-admin label {
        margin-bottom: 0.2rem;
        font-size: 0.8rem;
    }

    .purchaseorder-admin .card {
        margin-bottom: 0.5rem;
    }

    .purchaseorder-admin .card-header {
        padding: 0.45rem 0.75rem;
    }

    .purchaseorder-admin .card-body {
        padding: 0.85rem;
    }

    .purchaseorder-admin .table {
        margin-top: 0.75rem !important;
        font-size: 0.77rem;
        table-layout: fixed;
    }

    .purchaseorder-admin .table th,
    .purchaseorder-admin .table td {
        padding: 0.32rem 0.42rem;
        vertical-align: middle;
    }

    .purchaseorder-admin .purchaseorder-actions-cell {
        text-align: right;
        white-space: nowrap;
    }

    .purchaseorder-admin .purchaseorder-wrap-cell {
        white-space: nowrap !important;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .purchaseorder-admin .purchaseorder-icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        padding: 0;
        margin-right: 0.18rem;
        border-radius: 0.2rem;
    }

    .purchaseorder-admin .purchaseorder-icon-btn:last-child {
        margin-right: 0;
    }

    .purchaseorder-admin .purchaseorder-per-page-inline {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.3rem;
    }

    .purchaseorder-admin .purchaseorder-per-page-inline .form-control {
        width: 72px;
    }

    .purchaseorder-admin .pagination {
        margin-bottom: 0;
    }

    .purchaseorder-admin .page-link {
        padding: 0.3rem 0.55rem;
        font-size: 0.78rem;
    }
}
</style>
