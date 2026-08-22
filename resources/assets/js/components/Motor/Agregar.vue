<template>
    <div class="row">
        <div class="col-12">
            <div id="accordionMotor">
                <div class="card">

                    <div class="card-header p-0" id="headingMotor">
                    <h5 class="mb-0">
                        <button id="btn-motor-card" class="btn btn-block text-left vehiclebrand-collapse-header" data-toggle="collapse" data-target="#nuevo_motor_asset"
                            aria-expanded="true" aria-controls="collapseOne">
                        Motores
                        <span class="text-right"><i class="fas fa-arrows-alt-v"></i></span>
                        </button>
                    </h5>
                    </div>

                    <div id="nuevo_motor_asset" class="collapse" aria-labelledby="headingMotor" data-parent="#accordionMotor">
                        <div class="card-body">
                            <form action="POST" v-on:submit.prevent="createMotor">
                                <div class="row">

                                    <div class="col">
                                        <label for="motor_number">N° de Serie</label>
                                        <input type="text" name="motor_number" class="form-control" v-model="newMotor.motor_number">

                                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                            <p>{{ error.motor_number }}</p>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <label for="numero_interno">N° Interno</label>
                                        <input type="text" name="numero_interno" class="form-control" v-model="newMotor.numero_interno">

                                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                            <p>{{ error.numero_interno }}</p>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <label for="modelo_motor">Modelo Motor</label>
                                        <input type="text" name="modelo_motor" class="form-control" v-model="newMotor.modelo_motor">

                                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                            <p>{{ error.modelo_motor }}</p>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <label for="arreglo_cpl">Arreglo / CPL</label>
                                        <input type="text" name="arreglo_cpl" class="form-control" v-model="newMotor.arreglo_cpl">

                                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                            <p>{{ error.arreglo_cpl }}</p>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <label for="client_id">Cliente (flota, opcional)</label>
                                        <v-select name="client_id" placeholder="Sin cliente asignado..."
                                            :options="optionsClient" :reduce="option => option.value"
                                            v-model="newMotor.client_id"></v-select>
                                    </div>

                                    <div class="col-lg-3 mt-2">
                                        <label></label>
                                        <button type="submit" class="btn btn-success form-control">
                                            <i class="fas fa-plus-square"></i> Guardar
                                        </button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <input type="text" class="form-control mb-3 vehiclebrand-search-input" id="search_motor_asset" placeholder="Buscar motor, arreglo/CPL o patente..."
                v-model="searchMotor" @input="getMotors({ page: 1, search: searchMotor })">
            <div class="table-responsive">
                <table class="table table-responsive-new table-dark table-sm mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>N° Serie</th>
                            <th>N° Interno</th>
                            <th>Modelo Motor</th>
                            <th>Arreglo/CPL</th>
                            <th>Vehículo</th>
                            <th>Cliente</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="motorLocal in motors" :key="motorLocal.id">
                            <td data-table-label="ID">{{ motorLocal.id }}</td>
                            <td data-table-label="N° SERIE">{{ motorLocal.motor_number }}</td>
                            <td data-table-label="N° INTERNO">{{ motorLocal.numero_interno }}</td>
                            <td data-table-label="MODELO MOTOR">{{ motorLocal.modelo_motor }}</td>
                            <td data-table-label="ARREGLO/CPL">{{ motorLocal.arreglo_cpl }}</td>
                            <td data-table-label="VEHICULO">
                                <span v-if="motorLocal.vehicle_id">{{ motorLocal.vehicle_patent }} - {{ motorLocal.vehicle_brand }} {{ motorLocal.vehicle_model }}</span>
                                <span v-else class="text-muted">Suelto</span>
                            </td>
                            <td data-table-label="CLIENTE">
                                <span v-if="motorLocal.client_name">{{ motorLocal.client_name }}</span>
                                <span v-else class="text-muted">-</span>
                            </td>

                            <td>
                                <div class="d-flex align-items-center">
                                    <input type="text" class="form-control form-control-sm mr-1" style="width:120px"
                                        placeholder="Patente"
                                        v-model="linkPatents[motorLocal.id]">
                                    <a href="#" class="btn btn-primary btn-icon-sm mr-1"
                                        @click.prevent="linkMotor({ id: motorLocal.id, patent: linkPatents[motorLocal.id] })"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Vincular a vehículo">
                                        <i class="fas fa-link"></i>
                                    </a>
                                    <a href="#" class="btn btn-secondary btn-icon-sm mr-1"
                                        v-if="motorLocal.vehicle_id"
                                        @click.prevent="unlinkMotor(motorLocal.id)"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Desvincular">
                                        <i class="fas fa-unlink"></i>
                                    </a>
                                    <template v-if="motorLocal.can_edit">
                                        <a href="#" class="btn btn-warning btn-icon-sm mr-1"
                                            @click.prevent="editMotor( { motorLocal } )"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Editar">
                                            <i class="far fa-edit"></i>
                                        </a>
                                        <a href="#" class="btn btn-danger btn-icon-sm mr-1"
                                            @click.prevent="deleteMotor({ id: motorLocal.id })"
                                            data-toggle="tooltip"
                                            data-placement="top"
                                            title="Eliminar">
                                            <i class="fas fa-ban"></i>
                                        </a>
                                    </template>
                                    <a href="#" class="btn btn-primary btn-icon-sm mr-1"
                                        @click.prevent="requestPartsForMotor(motorLocal)"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Cotizar Repuestos">
                                        <i class="fas fa-oil-can"></i>
                                    </a>
                                    <a href="#" class="btn btn-info btn-icon-sm"
                                        @click.prevent="getMotorHistory(motorLocal.id)"
                                        data-toggle="tooltip"
                                        data-placement="top"
                                        title="Historial">
                                        <i class="fas fa-history"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="table-list-toolbar">
                <div class="table-list-toolbar__rows">
                    <span>Filas</span>
                    <select class="custom-select custom-select-sm" v-model.number="pagination_motors.per_page"
                        @change="changePageMotors({ page: 1, per_page: pagination_motors.per_page, search: searchMotor })">
                        <option :value="10">10</option>
                        <option :value="20">20</option>
                        <option :value="50">50</option>
                    </select>
                </div>
            </div>
            <nav class="mt-3">
                <ul class="pagination">
                    <li class="page-item" v-if="pagination_motors.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageMotors({page: 1, per_page: pagination_motors.per_page, search: searchMotor})">
                            <span>Primera</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_motors.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageMotors({page: pagination_motors.current_page - 1, per_page: pagination_motors.per_page, search: searchMotor})">
                            <span>Atrás</span>
                        </a>
                    </li>

                    <li class="page-item" v-for="page in pagesNumber_motors"
                        v-bind:class="[ page == isActived_motors ? 'active' : '' ]" :key="page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageMotors({page, per_page: pagination_motors.per_page, search: searchMotor})">
                            {{ page }}
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_motors.current_page < pagination_motors.last_page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageMotors({page: pagination_motors.current_page + 1, per_page: pagination_motors.per_page, search: searchMotor})">
                            <span>Siguiente</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_motors.current_page < pagination_motors.last_page">
                        <a class="page-link border-light bg-dark" href="#"  @click.prevent="changePageMotors({page:pagination_motors.last_page, per_page: pagination_motors.per_page, search: searchMotor})">
                            <span>Última</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <RequestParts></RequestParts>
    </div>
</template>
<script>

import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapActions, mapGetters } from 'vuex'
import RequestParts from '../Vehicle/RequestParts'

export default {
    components: { RequestParts },
    data() {
        return {
            searchMotor: '',
            linkPatents: {}
        }
    },
    computed:{
        ...mapState(['newMotor', 'errorsLaravel', 'motors', 'pagination_motors', 'offset_motors', 'optionsClient']),
        ...mapGetters(['isActived_motors', 'pagesNumber_motors'])
    },
    methods:{
        ...mapActions(['createMotor', 'editMotor', 'deleteMotor', 'changePageMotors', 'getMotors', 'linkMotor', 'unlinkMotor', 'getMotorHistory', 'modalRequestParts']),
        requestPartsForMotor(motorLocal) {
            this.modalRequestParts({
                vehicleLocal: {
                    patent: motorLocal.vehicle_patent || motorLocal.numero_interno || '',
                    chasis: motorLocal.motor_number || '',
                    brand: motorLocal.vehicle_brand || '',
                    model: motorLocal.modelo_motor || motorLocal.vehicle_model || '',
                    year: '',
                    engine: motorLocal.motor_number || ''
                }
            })
        }
    },
    created(){
        loadProgressBar();
        this.$store.dispatch('allClients', { type: ['Cliente', 'Empresa'] })
        this.$store.dispatch('getMotors', { page: 1 })
    }
}

</script>
