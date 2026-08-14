<template>
    <div class="col-lg-12 quotationclient-admin">
        <h5 class="text-white">
            {{ tipo === 'reparacion' ? 'Administración de Cotización Reparación' : 'Administración de Cotizaciones Formales' }}
            <!-- <button class="btn btn-primary btn-sm" @click.prevent="actualizarCorrelativo()">Actualizar Correlativo</button> -->
        </h5>
        <div id="accordion">
            <div class="card">

                <div class="card-header p-0" id="headingOne">
                    <h5 class="mb-0">
                        <button id="btn-quotation-card" class="btn btn-block text-left p-3" data-toggle="collapse"
                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Nueva Cotización Formal
                            <span class="text-right"><i class="fas fa-arrows-alt-v"></i></span>
                        </button>
                    </h5>
                </div>

                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <form action="POST" v-on:submit.prevent="createQuotationclient">

                            <div class="row quotationclient-form-layout">
                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                            <label for="cliente">Cliente</label>
                                            <SelectClient></SelectClient>
                                            <div class="quotationclient-checkbox-row mt-1">
                                                <input type="checkbox" name="es_empresa" id="es_empresa"
                                                    v-model="newQuotationclient.es_empresa">
                                                <label for="es_empresa" class="mb-0">Es una empresa nueva (no particular)</label>
                                            </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
                                    <div class="row quotationclient-form-row">
                                        <div class="col-lg-5 col-md-5 col-12 mb-3">
                                            <label for="marca">Marca</label>
                                            <BrandSelector />
                                        </div>

                                        <div class="col-lg-5 col-md-5 col-12 mb-3">
                                            <label for="modelo">Modelo</label>
                                            <ModelSelector />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-12 mb-3">
                                            <label for="anio">Año</label>
                                            <YearSelector />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
                                    <div class="mb-3">
                                        <label for="nombre">Nombre Cliente</label>
                                        <input type="text" name="nombre" class="form-control"
                                            v-model="newQuotationclient.client_text">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
                                    <div class="row quotationclient-form-row">
                                        <div class="col-lg-5 col-md-5 col-12 mb-3">
                                            <label for="engine">Motor</label>
                                            <EngineSelector />
                                        </div>

                                        <div class="col-lg-7 col-md-7 col-12 mb-3">
                                            <label for="ppu">PPU / VIN / Chasis / N° Interno / N° Serie</label>
                                            <input type="text" name="ppu" class="form-control"
                                                v-model="newQuotationclient.ppu">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
                                    <div class="row quotationclient-form-row">
                                        <div class="col-lg-3 col-md-3 col-12 mb-3">
                                            <label for="telefono">WhatsApp</label>
                                            <input type="text" name="telefono" class="form-control"
                                                v-model="newQuotationclient.telefono" placeholder="+56912345678">
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-12 mb-3">
                                            <label for="url">Facebook / Messenger</label>
                                            <input type="text" name="url" class="form-control"
                                                v-model="newQuotationclient.url" placeholder="https://...">
                                        </div>

                                        <div class="col-lg-5 col-md-5 col-12 mb-3">
                                            <label for="pago">Forma de Pago</label>
                                            <SelectTiposPagos />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-12">
                                    <div class="row quotationclient-form-row quotationclient-form-actions">
                                        <div v-if="tipo !== 'reparacion'" class="col-lg-8 col-md-8 col-12 mb-3 quotationclient-checkbox-row">
                                            <input type="checkbox" name="show_part_number" id="show_part_number"
                                                v-model="newQuotationclient.show_part_number">
                                            <label for="show_part_number" class="mb-0">Mostrar N° de parte en el PDF</label>
                                        </div>
                                        <div class="mb-3" :class="tipo !== 'reparacion' ? 'col-lg-4 col-md-4 col-12' : 'col-12'">
                                            <button type="submit" class="btn btn-success form-control" :disabled="savingQuotationclient">
                                                <i class="fas fa-plus-square"></i> {{ savingQuotationclient ? 'Guardando...' : 'Guardar' }}
                                            </button>
                                        </div>
                                    </div>
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
                        <th width="4%">ID</th>
                        <th width="5%">Generado</th>
                        <th width="7%">Estado</th>
                        <th width="8%">Rut</th>
                        <th width="17%">Razón Social</th>
                        <th width="15%">Cliente</th>
                        <th width="15%">Vehículo</th>
                        <th width="7%">Producto</th>
                        <th width="11%">Fecha</th>
                        <th width="11%">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" class="form-control" placeholder="ID" v-model="searchQuotationClient.id"
                                @keyup="getQuotationclients">
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <input type="text" class="form-control" placeholder="Razon Social"
                                v-model="searchQuotationClient.razonSocial" @keyup="getQuotationclients">
                        </td>
                        <td>
                            <input type="text" class="form-control" placeholder="Cliente"
                                v-model="searchQuotationClient.client" @keyup="getQuotationclients">
                        </td>
                        <td>
                            <input type="text" class="form-control" placeholder="Vehiculo"
                                v-model="searchQuotationClient.vehicle" @keyup="getQuotationclients">
                        </td>
                        <td>
                            <input type="text" class="form-control" placeholder="Ej: rodamiento"
                                v-model="searchQuotationClient.product" @keyup="getQuotationclients">
                        </td>
                        <td>
                            <date-picker v-model="dateRange" range value-type="format" format="YYYY-MM-DD"
                                placeholder="Rango de fechas" input-class="form-control" :clearable="true"
                                @change="getQuotationclients()" @clear="getQuotationclients()"></date-picker>
                        </td>
                        <td>
                            <div class="quotationclient-per-page-inline" title="Filas por pagina">
                                <label for="quotationclient-per-page" class="mb-0">Filas</label>
                                <select id="quotationclient-per-page" class="form-control"
                                    v-model.number="searchQuotationClient.per_page"
                                    @change="getQuotationclients({ page: 1 })">
                                    <option :value="10">10</option>
                                    <option :value="20">20</option>
                                    <option :value="50">50</option>
                                </select>
                            </div>
                        </td>
                    </tr>

                    <tr v-for="quotationLocal in quotationclients" :key="quotationLocal.id">
                        <td data-table-label="ID">
                            <span class="quotationclient-id-preview"
                                :title="productPreviewTitle(quotationLocal)">
                                {{ quotationLocal.user_id === 1 ? quotationLocal.id : quotationLocal.correlativo + (quotationLocal.correlativo_suffix ? '.' + quotationLocal.correlativo_suffix : '') }}
                            </span>
                        </td>
                        <td data-table-label="Generado" class="quotationclient-status-cell">
                            <span v-if="quotationLocal.generado == 1"
                                class="btn btn-warning btn-sm quotationclient-icon-btn"
                                data-toggle="tooltip" data-placement="top" title="Persona">
                                <i class="fas fa-user"></i>
                            </span>

                            <span v-if="quotationLocal.generado == 2"
                                class="btn btn-primary btn-sm quotationclient-icon-btn"
                                data-toggle="tooltip" data-placement="top" title="Empresa">
                                <i class="fas fa-building"></i>
                            </span>

                            <span v-if="quotationLocal.generado == 4"
                                class="btn btn-danger btn-sm quotationclient-icon-btn"
                                data-toggle="tooltip" data-placement="top" title="Repuestos a solicitar">
                                <i class="fas fa-tools"></i>
                            </span>

                            <span v-if="quotationLocal.shared_from_user_id" class="badge badge-info"
                                data-toggle="tooltip" data-placement="top" title="Compartida por una cuenta independiente">
                                <i class="fas fa-share-alt"></i> Compartida
                            </span>
                        </td>
                        <td data-table-label="Estado">{{ quotationLocal.state }}</td>
                        <td data-table-label="Rut">{{ quotationLocal.rut }}</td>
                        <td data-table-label="Razon Social" class="quotationclient-wrap-cell" :title="quotationLocal.razonSocial">{{ quotationLocal.razonSocial }}</td>
                        <td data-table-label="Cliente" class="quotationclient-wrap-cell" :title="quotationLocal.client_text">{{ quotationLocal.client_text }}</td>
                        <td data-table-label="Vehiculo" class="quotationclient-wrap-cell" :title="quotationLocal.vehicle">{{ quotationLocal.vehicle }}</td>
                        <td></td>
                        <td data-table-label="Fecha">{{ quotationLocal.created_at | moment('DD/MM/YYYY H:mm a') }}</td>
                        <td class="quotationclient-actions-cell">

                            <a v-if="contactLinks(quotationLocal).length === 1"
                                :href="contactLinks(quotationLocal)[0].href"
                                :class="contactLinks(quotationLocal)[0].buttonClass + ' btn btn-sm quotationclient-icon-btn'"
                                target="_blank" data-toggle="tooltip" data-placeemnt="top"
                                :title="contactLinks(quotationLocal)[0].title">
                                <i :class="contactLinks(quotationLocal)[0].icon"></i>
                            </a>

                            <div v-else-if="contactLinks(quotationLocal).length > 1"
                                class="btn-group dropleft quotationclient-contact-group">
                                <button type="button"
                                    class="btn btn-primary btn-sm dropdown-toggle dropdown-toggle-split quotationclient-icon-btn"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Contacto">
                                    <i class="fas fa-comments"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right quotationclient-contact-menu">
                                    <a v-for="contactLink in contactLinks(quotationLocal)" :key="contactLink.type"
                                        class="dropdown-item" :href="contactLink.href" target="_blank">
                                        <i :class="contactLink.icon + ' mr-2'"></i>{{ contactLink.title }}
                                    </a>
                                </div>
                            </div>

                            <a href="#" v-if="quotationLocal.tipo_detalle == 0"
                                class="btn btn-info btn-sm quotationclient-icon-btn"
                                @click.prevent="showModalDetailclient({ id: quotationLocal.id })" data-toggle="tooltip"
                                data-placement="top" title="Administrar detalle">
                                <i class="fas fa-list-ul"></i>
                            </a>

                            <a href="#" v-if="quotationLocal.tipo_detalle == 1"
                                class="btn btn-info btn-sm quotationclient-icon-btn"
                                @click.prevent="showModalDetailclientMechanic({ id: quotationLocal.id })"
                                data-toggle="tooltip" data-placement="top" title="Administrar detalle">
                                <i class="fas fa-list-ul"></i>
                            </a>

                            <div class="btn-group dropleft quotationclient-more-group">
                                <button type="button"
                                    class="btn btn-secondary btn-sm dropdown-toggle dropdown-toggle-split quotationclient-icon-btn"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Más acciones">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right quotationclient-contact-menu">
                                    <a href="#"
                                        v-if="quotationLocal.generado_client == 0 && (quotationLocal.generado == 1 || quotationLocal.generado == 2)"
                                        class="dropdown-item"
                                        @click.prevent="modalCreateUserFromQuotation({ id: quotationLocal.id })">
                                        <i class="fas fa-user-plus mr-2"></i>Crear Usuario
                                    </a>

                                    <a href="#" v-if="quotationLocal.generado_client == 0 && quotationLocal.generado == 5"
                                        class="dropdown-item"
                                        @click.prevent="modalCreateUserMechanicFromQuotation({ id: quotationLocal.id })">
                                        <i class="fas fa-user-plus mr-2"></i>Crear Usuario
                                    </a>

                                    <a href="#" class="dropdown-item"
                                        @click.prevent="editQuotationclient({ quotationclient: quotationLocal })">
                                        <i class="fas fa-edit mr-2"></i>Editar cabecera
                                    </a>

                                    <a href="#" class="dropdown-item"
                                        @click.prevent="replicateQuotationclient({ id: quotationLocal.id })">
                                        <i class="far fa-copy mr-2"></i>Duplicar
                                    </a>

                                    <a href="#" v-if="canShareWithAdmin" class="dropdown-item"
                                        @click.prevent="shareQuotationclient({ id: quotationLocal.id })">
                                        <i class="fas fa-share-alt mr-2"></i>Compartir con administrador
                                    </a>
                                </div>
                            </div>

                            <a href="#" class="btn btn-danger btn-sm quotationclient-icon-btn"
                                @click.prevent="showModalDeleteQuotationclient({ id: quotationLocal.id })"
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
                <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageQuotationclient({ page: 1 })">
                        <span>Primera</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageQuotationclient({ page: pagination.current_page - 1 })">
                        <span>Atrás</span>
                    </a>
                </li>

                <li class="page-item" v-for="page in pagesNumber" v-bind:class="[page == isActived ? 'active' : '']"
                    :key="page">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageQuotationclient({ page })">
                        {{ page }}
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageQuotationclient({ page: pagination.current_page + 1 })">
                        <span>Siguiente</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageQuotationclient({ page: pagination.last_page })">
                        <span>Última</span>
                    </a>
                </li>
            </ul>
        </nav>

        <template v-if="tipo !== 'reparacion'">
            <div v-for="quotationRolesLocal in quotationRoles" :key="quotationRolesLocal.id">
                <div v-if="quotationRolesLocal.roles[0].name == 'admin' || quotationRolesLocal.roles[0].name == 'sealer'">
                    <ListarClientesForm></ListarClientesForm>
                </div>
            </div>
        </template>


        <CreateUser></CreateUser>
        <CreateUserMechanic></CreateUserMechanic>
        <DetalleCliente></DetalleCliente>
        <Detalle></Detalle>
        <EditarCotizacion></EditarCotizacion>
        <DetalleMechanic></DetalleMechanic>
        <DetalleClienteMechanic></DetalleClienteMechanic>
        <DetalleEditarC></DetalleEditarC>
        <DetalleEditarCM></DetalleEditarCM>
        <EliminarCotizacionCliente></EliminarCotizacionCliente>

    </div>
</template>


<script>
import { mapState, mapActions, mapGetters } from 'vuex'
import { loadProgressBar } from 'axios-progress-bar'
import SelectClient from '../Client/Select'
import DetalleCliente from './DetalleCliente'
import DetalleMechanic from './DetalleMechanic'
import DetalleClienteMechanic from './DetalleClienteMechanic'
import Detalle from './Detalle'
import EditarCotizacion from './EditarCotizacion'
import DetalleEditarC from './DetalleEditar'
import DetalleEditarCM from './DetalleEditarMechanic'
import CreateUserMechanic from './CreateUserMechanic'
import EliminarCotizacionCliente from './Eliminar'
import CreateUser from './CreateUser'
import ListarClientesForm from './ListarClientesForm'
import ListarQuotationShipping from './ListarQuotationShipping'

import BrandSelector from '../Quotationuser/BrandSelector'
import ModelSelector from '../Quotationuser/ModelSelector'
import YearSelector from '../Quotationuser/YearSelector'
import EngineSelector from '../Quotationuser/EngineSelector'
import SelectTiposPagos from '../Utilidad/SelectTiposPagos'
import DatePicker from 'vue2-datepicker'
import 'vue2-datepicker/index.css'


export default {
    components: { SelectClient, BrandSelector, ModelSelector, YearSelector, EngineSelector, DetalleCliente, Detalle, EditarCotizacion, DetalleEditarC, DetalleEditarCM, EliminarCotizacionCliente, CreateUser, CreateUserMechanic, DetalleMechanic, DetalleClienteMechanic, ListarClientesForm, ListarQuotationShipping, SelectTiposPagos, DatePicker },
    props: {
        tipo: {
            type: String,
            default: 'repuesto'
        }
    },
    computed: {
        ...mapState(['quotationRoles', 'quotationclients', 'quotationclientsform', 'newQuotationclient', 'searchQuotationClient', 'pagination', 'offset', 'errorsLaravel', 'idQuotationclient', 'savingQuotationclient', 'fillUser', 'myLinkRequests']),
        ...mapGetters(['isActived', 'pagesNumber']),
        canShareWithAdmin() {
            return this.fillUser.is_independent && this.myLinkRequests.some(link => link.status === 'accepted')
        },
        dateRange: {
            get() {
                return [this.searchQuotationClient.date_from || null, this.searchQuotationClient.date_to || null]
            },
            set(value) {
                this.searchQuotationClient.date_from = (value && value[0]) || ''
                this.searchQuotationClient.date_to = (value && value[1]) || ''
            }
        }
    },
    methods: {
        ...mapActions(['getRolesQuotation', 'getQuotationclients', 'createQuotationclient', 'showModalDetailclient', 'showModalDetailMechanic', 'modalCreateUserMechanicFromQuotation', 'showModalDetailclientMechanic',
            'showModalDeleteQuotationclient', 'changePageQuotationclient', 'modalCreateUserFromQuotation', 'actualizarCorrelativo', 'editQuotationclient', 'replicateQuotationclient', 'shareQuotationclient']),
        contactLinks(quotationLocal) {
            const links = []
            const messengerUrl = (quotationLocal.url || '').trim()
            const whatsAppHref = this.whatsAppUrl(quotationLocal.telefono)

            if (messengerUrl !== '') {
                links.push({
                    type: 'messenger',
                    title: 'Facebook / Messenger',
                    href: messengerUrl,
                    icon: 'fab fa-facebook-f',
                    buttonClass: 'btn-primary'
                })
            }

            if (whatsAppHref !== '#') {
                links.push({
                    type: 'whatsapp',
                    title: 'WhatsApp',
                    href: whatsAppHref,
                    icon: 'fab fa-whatsapp',
                    buttonClass: 'btn-success'
                })
            }

            return links
        },
        productPreviewTitle(quotationLocal) {
            const summary = []
            const previewItems = (quotationLocal.product_preview || '')
                .split('||')
                .map(product => product.trim())
                .filter(product => product !== '')
            const totalItems = parseInt(quotationLocal.detailclient_count || 0, 10)

            if ((quotationLocal.ppu || '').trim() !== '') {
                summary.push(`PPU: ${quotationLocal.ppu.trim()}`)
            }

            if ((quotationLocal.vehicle || '').trim() !== '') {
                summary.push(`Vehiculo: ${quotationLocal.vehicle.trim()}`)
            }

            if (previewItems.length) {
                summary.push('Productos:')
                summary.push(...previewItems)

                if (totalItems > previewItems.length) {
                    summary.push(`... y ${totalItems - previewItems.length} mas`)
                }
            } else {
                summary.push('Sin productos cargados')
            }

            return summary.join('\n')
        },
        whatsAppUrl(telefono) {
            const digits = (telefono || '').replace(/\D/g, '')

            if (!digits) {
                return '#'
            }

            if (digits.startsWith('56')) {
                return `https://wa.me/${digits}`
            }

            if (digits.length === 8) {
                return `https://wa.me/569${digits}`
            }

            if (digits.length === 9 && digits.startsWith('9')) {
                return `https://wa.me/56${digits}`
            }

            return `https://wa.me/${digits}`
        }
    },
    created() {
        loadProgressBar();
        this.$store.dispatch('setQuotationTipoContext', this.tipo)
        this.$store.dispatch('getQuotationclients', { page: 1 })
        this.$store.dispatch('getRolesQuotation')
        this.$store.dispatch('allPagos')
        this.$store.dispatch('getUser')
        this.$store.dispatch('getMyLinkRequests')
    }

}
</script>

<style>
.quotationclient-admin .mx-datepicker,
.quotationclient-admin .mx-datepicker-range {
    width: 130px !important;
    max-width: 100%;
}

.quotationclient-admin .mx-input {
    padding-left: 6px;
    padding-right: 20px;
    font-size: 0.68rem !important;
}

.quotationclient-admin .mx-icon-calendar,
.quotationclient-admin .mx-icon-clear {
    right: 3px;
    font-size: 13px;
}

@media (min-width: 992px) {
    .quotationclient-admin {
        font-size: 0.88rem;
    }

    .quotationclient-admin h5 {
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }

    .quotationclient-admin label {
        margin-bottom: 0.2rem;
        font-size: 0.8rem;
    }

    .quotationclient-admin .mb-3,
    .quotationclient-admin .row.mb-3 {
        margin-bottom: 0.6rem !important;
    }

    .quotationclient-admin .quotationclient-form-layout,
    .quotationclient-admin .quotationclient-form-row {
        align-items: flex-end;
    }

    .quotationclient-admin .quotationclient-checkbox-row {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .quotationclient-admin .quotationclient-checkbox-row input[type="checkbox"] {
        margin-top: 0;
    }

    .quotationclient-admin .quotationclient-form-actions {
        margin-top: 0.1rem;
    }

    .quotationclient-admin .card {
        margin-bottom: 0.5rem;
    }

    .quotationclient-admin .card-header {
        padding: 0.45rem 0.75rem;
    }

    .quotationclient-admin .card-body {
        padding: 0.85rem;
    }

    .quotationclient-admin #btn-quotation-card {
        padding: 0.7rem 0.9rem !important;
        font-size: 0.9rem;
    }

    .quotationclient-admin .form-control,
    .quotationclient-admin .v-select .dropdown-toggle {
        min-height: 31px;
        height: 31px;
        padding-top: 0.22rem;
        padding-bottom: 0.22rem;
        font-size: 0.8rem;
    }

    .quotationclient-admin .v-select .selected-tag,
    .quotationclient-admin .v-select input {
        font-size: 0.8rem;
    }

    .quotationclient-admin .btn {
        padding: 0.24rem 0.5rem;
        font-size: 0.78rem;
        line-height: 1.25;
    }

    .quotationclient-admin .btn.form-control {
        height: 31px;
        padding-top: 0.22rem;
        padding-bottom: 0.22rem;
    }

    .quotationclient-admin .table {
        margin-top: 0.75rem !important;
        font-size: 0.77rem;
        table-layout: fixed;
    }

    .quotationclient-admin .table th,
    .quotationclient-admin .table td {
        padding: 0.32rem 0.42rem;
        vertical-align: middle;
    }

    .quotationclient-admin .quotationclient-status-cell,
    .quotationclient-admin .quotationclient-actions-cell {
        white-space: nowrap;
    }

    .quotationclient-admin .quotationclient-actions-cell {
        text-align: right;
    }

    .quotationclient-admin .quotationclient-wrap-cell {
        white-space: nowrap !important;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .quotationclient-admin .quotationclient-id-preview {
        cursor: help;
        display: inline-block;
        border-bottom: 1px dotted rgba(255, 255, 255, 0.35);
        line-height: 1.1;
    }

    .quotationclient-admin .quotationclient-per-page-inline {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.3rem;
    }

    .quotationclient-admin .quotationclient-per-page-inline label {
        margin-bottom: 0;
        font-size: 0.74rem;
        white-space: nowrap;
    }

    .quotationclient-admin .quotationclient-per-page-inline .form-control {
        width: 72px;
    }

    .quotationclient-admin .quotationclient-icon-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        padding: 0;
        margin-right: 0.18rem;
        border-radius: 0.2rem;
    }

    .quotationclient-admin .quotationclient-icon-btn:last-child {
        margin-right: 0;
    }

    .quotationclient-admin .quotationclient-contact-group,
    .quotationclient-admin .quotationclient-more-group {
        vertical-align: middle;
        margin-right: 0.18rem;
    }

    .quotationclient-admin .quotationclient-contact-group .quotationclient-icon-btn,
    .quotationclient-admin .quotationclient-more-group .quotationclient-icon-btn {
        width: 26px;
        margin-right: 0;
    }

    .quotationclient-admin .quotationclient-contact-menu {
        min-width: 11.5rem;
        font-size: 0.78rem;
    }

    .quotationclient-admin .quotationclient-contact-menu .dropdown-item {
        padding: 0.35rem 0.75rem;
    }

    .quotationclient-admin .table .form-control {
        height: 28px;
        min-height: 28px;
        padding-left: 0.35rem;
        padding-right: 0.35rem;
    }

    .quotationclient-admin .pagination {
        margin-bottom: 0;
    }

    .quotationclient-admin .page-link {
        padding: 0.3rem 0.55rem;
        font-size: 0.78rem;
    }
}
</style>
