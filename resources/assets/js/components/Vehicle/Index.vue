<template>

    <div class="col-lg-12">

        <h5 class="text-white">
            Nuevo Vehículo
            <a href="#" class="btn btn-success pull-right btn-sm" data-toggle="modal" data-target="#create"
                title="Agregar">
                <i class="fas fa-plus-circle"></i>
            </a>
        </h5>
        <div class="table-responsive">
            <table class="table table-responsive-new table-dark table-sm mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Patente</th>
                        <th>Chasis</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Año</th>
                        <th>Motor</th>
                        <th>Color</th>
                        <th>Kilometraje</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4">
                            <input type="text" class="form-control form-control-sm" placeholder="Patente"
                                v-model="searchVehicle.patent" @keyup="getVehicles({ page: 1, per_page: pagination.per_page })">
                        </td>

                        <td colspan="4">
                            <input type="text" class="form-control form-control-sm" placeholder="Marca"
                                v-model="searchVehicle.name" @keyup="getVehicles({ page: 1, per_page: pagination.per_page })">
                        </td>

                        <td colspan="4">
                            <input type="text" class="form-control form-control-sm" placeholder="Año"
                                v-model="searchVehicle.year" @keyup="getVehicles({ page: 1, per_page: pagination.per_page })">
                        </td>

                    </tr>

                    <tr v-for="vehicleLocal in vehicles" :key="vehicleLocal.id">
                        <td data-table-label="ID">{{ vehicleLocal.id }}</td>
                        <td data-table-label="Cliente">{{ vehicleLocal.user.name }}</td>
                        <td data-table-label="Patente">{{ vehicleLocal.patent }}</td>
                        <td data-table-label="Chasis">{{ vehicleLocal.chasis }}</td>
                        <td data-table-label="Marca">{{ vehicleLocal.brand }}</td>
                        <td data-table-label="Modelo">{{ vehicleLocal.model }}</td>
                        <td data-table-label="Año">{{ vehicleLocal.year }}</td>
                        <td data-table-label="Motor">{{ vehicleLocal.engine }}</td>
                        <td data-table-label="Color">{{ vehicleLocal.color }}</td>
                        <td data-table-label="Kilometraje">{{ vehicleLocal.km }}</td>
                        <td data-table-label="Fecha">{{ vehicleLocal.created_at | moment('DD/MM/YYYY') }}</td>

                        <td>
                            <a href="#" class="btn btn-warning btn-sm" @click.prevent="editVehicle({ vehicleLocal })"
                                data-toggle="tooltip" data-placement="top" title="Editar">
                                <i class="far fa-edit"></i>
                            </a>

                            <a href="#" class="btn btn-info btn-sm" @click.prevent="detailVehicle({ vehicleLocal })"
                                data-toggle="tooltip" data-placement="top" title="Informacion">
                                <i class="fas fa-info"></i>
                            </a>

                            <a href="#" class="btn btn-success btn-sm"
                                @click.prevent="modalDetailVehicle({ vehicleLocal })" data-toggle="tooltip"
                                data-placement="top" title="Detalle">
                                <i class="fas fa-plus-circle"></i>
                            </a>


                            <a href="#" v-if="rol === 'mechanic'" class="btn btn-success btn-sm"
                                @click.prevent="modalOrdenTrabajo({ vehicleLocal })" data-toggle="tooltip"
                                data-placement="top" title="Orden de trabajo">
                                <i class="fas fa-wrench"></i>
                            </a>


                            <a href="#" v-if="rol === 'mechanic'" class="btn btn-success btn-sm"
                                @click.prevent="modalCheckList({ vehicleLocal })" data-toggle="tooltip"
                                data-placement="top" title="Check List">
                                <i class="fas fa-clipboard-check"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-list-toolbar">
            <div class="table-list-toolbar__rows">
                <span>Filas</span>
                <select class="custom-select custom-select-sm" v-model.number="pagination.per_page"
                    @change="getVehicles({ page: 1, per_page: pagination.per_page })">
                    <option :value="10">10</option>
                    <option :value="20">20</option>
                    <option :value="50">50</option>
                </select>
            </div>
            <nav>
            <ul class="pagination">
                <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link" href="#" @click.prevent="changePageVehicle({ page: 1, per_page: pagination.per_page })">
                        <span>Primera</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link" href="#"
                        @click.prevent="changePageVehicle({ page: pagination.current_page - 1, per_page: pagination.per_page })">
                        <span>Atrás</span>
                    </a>
                </li>

                <li class="page-item" v-for="page in pagesNumber" v-bind:class="[page == isActived ? 'active' : '']"
                    :key="page">
                    <a class="page-link" href="#" @click.prevent="changePageVehicle({ page, per_page: pagination.per_page })">
                        {{ page }}
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link" href="#"
                        @click.prevent="changePageVehicle({ page: pagination.current_page + 1, per_page: pagination.per_page })">
                        <span>Siguiente</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link" href="#" @click.prevent="changePageVehicle({ page: pagination.last_page, per_page: pagination.per_page })">
                        <span>Última</span>
                    </a>
                </li>
            </ul>
            </nav>
        </div>

        <Agregar></Agregar>
        <Editar></Editar>
        <Detalle></Detalle>
        <AgregarDetalle></AgregarDetalle>
        <OrdenTrabajo></OrdenTrabajo>
        <CheckListVehicle></CheckListVehicle>
    </div>

</template>


<script>

import { loadProgressBar } from 'axios-progress-bar'
import Agregar from './Agregar'
import Editar from './Editar'
import Detalle from './Detalle'
import AgregarDetalle from './AgregarDetalle'
import OrdenTrabajo from '../OrdenTrabajos/OrdenTrabajo'
import CheckListVehicle from '../Check-List/CheckListVehicle'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: { Agregar, Editar, Detalle, AgregarDetalle, OrdenTrabajo, CheckListVehicle },
    computed: {
        ...mapState(['vehicles', 'pagination', 'offset', 'searchVehicle', 'rol']),
        ...mapGetters(['isActived', 'pagesNumber'])
    },
    methods: {
        ...mapActions(['getVehicles', 'getVehiclesUser', 'editVehicle',
            'detailVehicle', 'modalDetailVehicle', 'modalOrdenTrabajo', 'changePageVehicle', 'modalCheckList'])
    },
    created() {
        loadProgressBar();
        this.$store.dispatch('getVehicles', { page: 1 })
    }
}

</script>

<style></style>
