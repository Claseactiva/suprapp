<template>
    <div class="table-responsive">
        <table class="table table-responsive-new table-dark table-sm mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Generado</th>
                    <th>Estado</th>
                    <th>Razón Social</th>
                    <th>Cliente</th>
                    <th>Vehículo</th>
                    <th>Fecha</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <input type="text" class="form-control" placeholder="ID" style="width : 60px"
                            v-model="searchQuotationClientForm.id" @keyup="getQuotationclientsform">
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>
                        <input type="text" class="form-control" placeholder="Razon Social"
                            v-model="searchQuotationClientForm.razonSocial" @keyup="getQuotationclientsform">
                    </td>
                    <td>
                        <input type="text" class="form-control" placeholder="Cliente"
                            v-model="searchQuotationClientForm.client" @keyup="getQuotationclientsform">
                    </td>
                    <td>
                        <input type="text" class="form-control" placeholder="Vehículo"
                            v-model="searchQuotationClientForm.vehicle" @keyup="getQuotationclientsform">
                    </td>
                    <td>
                        <div class="form-inline">
                            <input type="text" class="form-control" placeholder="Dia" style="width : 50px"
                                v-model="searchQuotationClientForm.day" @keyup="getQuotationclientsform">
                            <h5>/</h5>
                            <input type="text" class="form-control" placeholder="Mes" style="width : 50px"
                                v-model="searchQuotationClientForm.month" @keyup="getQuotationclientsform">
                            <h5>/</h5>
                            <input type="text" class="form-control" placeholder="Año" style="width : 60px"
                                v-model="searchQuotationClientForm.year" @keyup="getQuotationclientsform">
                        </div>
                    </td>
                    <td></td>
                </tr>

                <tr v-for="quotationformLocal in quotationclientsform" :key="quotationformLocal.id">

                    <td data-table-label="ID">{{ quotationformLocal.id }}</td>
                    <td data-table-label="Generado">
                        <a v-if="quotationformLocal.generado == 5" class="btn btn-secondary btn-sm font-weight-bold"
                            type="button" @click.prevent="showModalDetail({ id: quotationformLocal.id })">Formulario
                            Cotizar Express</a>
                        <a v-if="quotationformLocal.generado == 3" class="btn btn-success btn-sm font-weight-bold"
                            type="button" @click.prevent="showModalDetail({ id: quotationformLocal.id })">Formulario
                            Cotizar</a>
                    </td>
                    <td data-table-label="Estado">{{ quotationformLocal.state }}</td>
                    <td data-table-label="Razon Social">{{ quotationformLocal.razonSocial }}</td>
                    <td data-table-label="Cliente">{{ quotationformLocal.client_text }}</td>
                    <td data-table-label="Vehiculo">{{ quotationformLocal.vehicle }}</td>
                    <td data-table-label="Fecha">{{ quotationformLocal.created_at | moment('DD/MM/YYYY H:mm a') }}</td>
                    <td>


                        <a href="#" v-if="quotationformLocal.generado_client == 0 && quotationformLocal.generado == 3"
                            class="btn btn-light btn-sm"
                            @click.prevent="modalCreateUserFromQuotation({ id: quotationformLocal.id })"
                            data-toggle="tooltip" data-placeemnt="top" title="Crear Usuario">
                            <i class="fas fa-user"></i>
                        </a>


                        <a href="#" v-if="quotationformLocal.tipo_detalle == 0" class="btn btn-info btn-sm"
                            @click.prevent="showModalDetailclient({ id: quotationformLocal.id })" data-toggle="tooltip"
                            data-placement="top" title="Detalle">
                            <i class="far fa-plus-square"></i>
                            Detalle
                        </a>

                        <a href="#" v-if="quotationformLocal.tipo_detalle == 1" class="btn btn-info btn-sm"
                            @click.prevent="showModalDetailclientMechanic({ id: quotationformLocal.id })"
                            data-toggle="tooltip" data-placement="top" title="Detalle">
                            <i class="far fa-plus-square"></i>
                            Detalle
                        </a>

                        <a href="#" class="btn btn-danger btn-sm"
                            @click.prevent="showModalDeleteQuotationclient({ id: quotationformLocal.id })"
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
                <select class="custom-select custom-select-sm" v-model.number="searchQuotationClientForm.per_page"
                    @change="getQuotationclientsform({ page: 1 })">
                    <option :value="10">10</option>
                    <option :value="20">20</option>
                    <option :value="50">50</option>
                </select>
            </div>
            <nav>
            <ul class="pagination">
                <li class="page-item" v-if="pagination_form.current_page > 1">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageQuotationclientForm({ page: 1 })">
                        <span>Primera</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination_form.current_page > 1">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageQuotationclientForm({ page: pagination_form.current_page - 1 })">
                        <span>Atrás</span>
                    </a>
                </li>

                <li class="page-item" v-for="page in pagesNumber_form"
                    v-bind:class="[page == isActived_form ? 'active' : '']" :key="page">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageQuotationclientForm({ page })">
                        {{ page }}
                    </a>
                </li>

                <li class="page-item" v-if="pagination_form.current_page < pagination_form.last_page">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageQuotationclientForm({ page: pagination_form.current_page + 1 })">
                        <span>Siguiente</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination_form.current_page < pagination_form.last_page">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageQuotationclientForm({ page: pagination_form.last_page })">
                        <span>Última</span>
                    </a>
                </li>
            </ul>
            </nav>
        </div>
        <CreateUser></CreateUser>
        <CreateUserMechanic></CreateUserMechanic>
        <DetalleCliente></DetalleCliente>
        <Detalle></Detalle>
        <DetalleMechanic></DetalleMechanic>
        <DetalleClienteMechanic></DetalleClienteMechanic>
        <DetalleEditarC></DetalleEditarC>
        <DetalleEditarCM></DetalleEditarCM>
        <EliminarCotizacionCliente></EliminarCotizacionCliente>
    </div>
</template>

<script>

import { loadProgressBar } from 'axios-progress-bar'
import SelectClient from '../Client/Select'
import DetalleCliente from './DetalleCliente'
import DetalleMechanic from './DetalleMechanic'
import DetalleClienteMechanic from './DetalleClienteMechanic'
import Detalle from './Detalle'
import DetalleEditarC from './DetalleEditar'
import DetalleEditarCM from './DetalleEditarMechanic'
import CreateUserMechanic from './CreateUserMechanic'
import EliminarCotizacionCliente from './Eliminar'
import CreateUser from './CreateUser'
import { mapState, mapActions, mapGetters } from 'vuex'


import BrandSelector from '../Quotationuser/BrandSelector'
import ModelSelector from '../Quotationuser/ModelSelector'
import YearSelector from '../Quotationuser/YearSelector'
import EngineSelector from '../Quotationuser/EngineSelector'

export default {
    components: { SelectClient, BrandSelector, ModelSelector, YearSelector, EngineSelector, DetalleCliente, Detalle, DetalleEditarC, DetalleEditarCM, EliminarCotizacionCliente, CreateUser, CreateUserMechanic, DetalleMechanic, DetalleClienteMechanic },
    computed: {
        ...mapState(['quotationclientsform', 'newQuotationclient', 'searchQuotationClientForm', 'pagination_form', 'offset_form', 'errorsLaravel']),
        ...mapGetters(['isActived_form', 'pagesNumber_form'])
    },
    methods: {
        ...mapActions(['getQuotationclientsform', 'createQuotationclient', 'showModalDetailclient', 'showModalDetail', 'showModalDetailMechanic', 'showModalDetailUserMechanic', 'showModalDetailclientMechanic',
            'showModalDeleteQuotationclient', 'changePageQuotationclientForm', 'modalCreateUserFromQuotation'])
    },
    created() {
        loadProgressBar();
        this.$store.dispatch('getQuotationclientsform', { page: 1 })
    }

}
</script>
