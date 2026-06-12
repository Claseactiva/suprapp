<template>

    <div class="col-lg-12">

        <h5 class="text-white">
            Nuevo Vehículo (Mecánico)
            <a href="#" class="btn btn-success pull-right btn-sm" data-toggle="modal"
                data-target="#createVehicleMechanic" title="Agregar">
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
                    <tr v-for="vehicleLocal in vehicles" :key="vehicleLocal.id">
                        <td data-table-label="id">{{ vehicleLocal.id }}</td>
                        <td data-table-label="cliente">{{ vehicleLocal.user.name }}</td>
                        <td data-table-label="patente">{{ vehicleLocal.patent }}</td>
                        <td data-table-label="chasis">{{ vehicleLocal.chasis }}</td>
                        <td data-table-label="marca">{{ vehicleLocal.brand }}</td>
                        <td data-table-label="modelo">{{ vehicleLocal.model }}</td>
                        <td data-table-label="año">{{ vehicleLocal.year }}</td>
                        <td data-table-label="motor">{{ vehicleLocal.engine }}</td>
                        <td data-table-label="color">{{ vehicleLocal.color }}</td>
                        <td data-table-label="kilometraje">{{ vehicleLocal.km }}</td>
                        <td data-table-label="fecha">{{ vehicleLocal.created_at | moment('DD/MM/YYYY') }}</td>

                        <td class="text-right">
                            <a href="#" class="btn btn-info btn-sm" @click.prevent="detailVehicle({ vehicleLocal })"
                                data-toggle="tooltip" data-placement="top" title="Informacion">
                                <i class="fas fa-info"></i>
                            </a>

                            <a href="#" class="btn btn-success btn-sm"
                                @click.prevent="modalDetailVehicle({ vehicleLocal, rol: 'mechanic' })"
                                data-toggle="tooltip" data-placement="top" title="Detalle de vehiculo">
                                <i class="fas fa-plus-circle"></i>
                            </a>

                            <a href="#" class="btn btn-primary btn-sm"
                                @click.prevent="modalRequestParts({ vehicleLocal })" data-toggle="tooltip"
                                data-placement="top" title="Cotizar Repuestos">
                                <i class="fas fa-cog"></i>
                            </a>

                            <a href="#" class="btn btn-success btn-sm"
                                @click.prevent="modalOrdenTrabajo({ vehicleLocal })" data-toggle="tooltip"
                                data-placement="top" title="Orden de trabajo">
                                <i class="fas fa-wrench"></i>
                            </a>

                            <a href="#" class="btn btn-success btn-sm"
                                @click.prevent="modalCheckList({ vehicleLocal })" data-toggle="tooltip"
                                data-placement="top" title="Check List">
                                <i class="fas fa-clipboard-check"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination">
                <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link" href="#" @click.prevent="changePageVehicle({ page: 1 })">
                        <span>Primera</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link" href="#"
                        @click.prevent="changePageVehicle({ page: pagination.current_page - 1 })">
                        <span>Atrás</span>
                    </a>
                </li>

                <li class="page-item" v-for="page in pagesNumber" v-bind:class="[page == isActived ? 'active' : '']"
                    :key="page">
                    <a class="page-link" href="#" @click.prevent="changePageVehicle({ page })">
                        {{ page }}
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link" href="#"
                        @click.prevent="changePageVehicle({ page: pagination.current_page + 1 })">
                        <span>Siguiente</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link" href="#" @click.prevent="changePageVehicle({ page: pagination.last_page })">
                        <span>Última</span>
                    </a>
                </li>
            </ul>
        </nav>

        <Agregar></Agregar>
        <!-- <Editar></Editar> -->
        <Detalle></Detalle>
        <AgregarDetalle></AgregarDetalle>
        <RequestParts></RequestParts>
        <OrdenTrabajo></OrdenTrabajo>
        <CheckListVehicle></CheckListVehicle>
        <AgregarObservacionCheckList></AgregarObservacionCheckList>
    </div>

</template>


<script>

import { loadProgressBar } from 'axios-progress-bar'
import Agregar from './Agregar'
import Editar from './Editar'
import Detalle from '../Vehicle/Detalle'
import AgregarDetalle from '../Vehicle/AgregarDetalle'
import RequestParts from '../Vehicle/RequestParts'
import OrdenTrabajo from '../OrdenTrabajos/OrdenTrabajo'
import CheckListVehicle from '../Check-List/CheckListVehicle'
import AgregarObservacionCheckList from '../Check-List/AgregarObservacionCheckList'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: { Agregar, Editar, Detalle, AgregarDetalle, RequestParts, OrdenTrabajo, CheckListVehicle, AgregarObservacionCheckList },
    computed: {
        ...mapState(['vehicles', 'pagination', 'offset', 'searchVehicle', 'errorsLaravel']),
        ...mapGetters(['isActived', 'pagesNumber'])
    },
    methods: {
        ...mapActions(['getClientVehicles', 'editVehicle', 'detailVehicle', 'modalDetailVehicle', 'modalOrdenTrabajo', 'changePageVehicle', 'modalRequestParts', 'modalCheckList'])
    },
    created() {
        loadProgressBar();
        this.$store.dispatch('getClientVehicles')
    }
}

</script>
