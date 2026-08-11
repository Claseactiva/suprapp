<template>

    <div class="col-lg-12 vehicle-admin">

        <h5 class="text-white">
            Nuevo Vehículo
            <a v-if="rol !== 'Quote'" href="#" class="btn btn-success pull-right btn-sm" data-toggle="modal" data-target="#create"
                title="Agregar">
                <i class="fas fa-plus-circle"></i>
            </a>
            <a v-if="rol !== 'Quote'" href="#" class="btn btn-secondary pull-right btn-sm mr-2" @click.prevent="toggleTrash">
                <i class="fas" :class="showTrash ? 'fa-list' : 'fa-trash-alt'"></i>
                {{ showTrash ? 'Volver al listado' : 'Papelera' }}
            </a>
        </h5>
        <div v-if="!showTrash" class="vehicle-filter-row mt-3">
            <input type="text" class="form-control form-control-sm vehicle-filter-input" placeholder="Cliente"
                v-model="searchVehicle.client" @keyup="getVehicles({ page: 1, per_page: pagination.per_page })">

            <input type="text" class="form-control form-control-sm vehicle-filter-input" placeholder="Patente"
                v-model="searchVehicle.patent" @keyup="getVehicles({ page: 1, per_page: pagination.per_page })">

            <input type="text" class="form-control form-control-sm vehicle-filter-input" placeholder="Marca"
                v-model="searchVehicle.name" @keyup="getVehicles({ page: 1, per_page: pagination.per_page })">

            <input type="text" class="form-control form-control-sm vehicle-filter-input" placeholder="Año"
                v-model="searchVehicle.year" @keyup="getVehicles({ page: 1, per_page: pagination.per_page })">

            <select v-if="rol === ''" class="form-control form-control-sm vehicle-filter-input"
                v-model="searchVehicle.owner_scope" @change="getVehicles({ page: 1, per_page: pagination.per_page })">
                <option value="">Todos</option>
                <option value="mine">Mis Vehículos</option>
                <option value="workshops">Vehículos de Talleres</option>
            </select>
        </div>
        <div v-if="!showTrash" class="vehicle-table-shell mt-3">
            <table class="table table-responsive-new table-dark table-sm vehicle-table mb-0">
                <thead>
                    <tr>
                        <th class="vehicle-col-id">ID</th>
                        <th class="vehicle-col-cliente">Cliente</th>
                        <th class="vehicle-col-patente">Patente</th>
                        <th class="vehicle-col-chasis">Chasis</th>
                        <th class="vehicle-col-marca">Marca</th>
                        <th class="vehicle-col-modelo">Modelo</th>
                        <th class="vehicle-col-anio">Año</th>
                        <th class="vehicle-col-motor">Motor</th>
                        <th class="vehicle-col-color">Color</th>
                        <th class="vehicle-col-km">Kilometraje</th>
                        <th class="vehicle-col-fecha">Fecha</th>
                        <th class="vehicle-col-actions"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="vehicleLocal in vehicles" :key="vehicleLocal.id">
                        <td data-table-label="ID" class="vehicle-col-id">{{ vehicleLocal.id }}</td>
                        <td data-table-label="Cliente" class="vehicle-col-cliente vehicle-cell-wrap" :title="vehicleLocal.user.name">{{ vehicleLocal.user.name }}</td>
                        <td data-table-label="Patente" class="vehicle-col-patente vehicle-cell-strong" :title="vehicleLocal.patent">{{ vehicleLocal.patent }}</td>
                        <td data-table-label="Chasis" class="vehicle-col-chasis vehicle-cell-wrap" :title="vehicleLocal.chasis">{{ vehicleLocal.chasis }}</td>
                        <td data-table-label="Marca" class="vehicle-col-marca vehicle-cell-wrap" :title="vehicleLocal.brand">{{ vehicleLocal.brand }}</td>
                        <td data-table-label="Modelo" class="vehicle-col-modelo vehicle-cell-wrap" :title="vehicleLocal.model">{{ vehicleLocal.model }}</td>
                        <td data-table-label="Año" class="vehicle-col-anio vehicle-cell-meta">{{ vehicleLocal.year }}</td>
                        <td data-table-label="Motor" class="vehicle-col-motor vehicle-cell-wrap" :title="vehicleLocal.engine">{{ vehicleLocal.engine }}</td>
                        <td data-table-label="Color" class="vehicle-col-color vehicle-cell-meta">{{ vehicleLocal.color }}</td>
                        <td data-table-label="Kilometraje" class="vehicle-col-km vehicle-cell-meta">{{ vehicleLocal.km }}</td>
                        <td data-table-label="Fecha" class="vehicle-col-fecha vehicle-cell-meta">{{ vehicleLocal.created_at | moment('DD/MM/YYYY') }}</td>

                        <td class="vehicle-col-actions vehicle-action-cell">
                            <a v-if="rol !== 'Quote'" href="#" class="btn btn-warning btn-icon-sm" @click.prevent="editVehicle({ vehicleLocal })"
                                data-toggle="tooltip" data-placement="top" title="Editar">
                                <i class="far fa-edit"></i>
                            </a>

                            <a v-if="rol !== 'Quote'" href="#" class="btn btn-danger btn-icon-sm" @click.prevent="deleteVehicle({ id: vehicleLocal.id })"
                                data-toggle="tooltip" data-placement="top" title="Eliminar">
                                <i class="fas fa-ban"></i>
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

                            <a href="#" class="btn btn-primary btn-sm"
                                @click.prevent="modalRequestParts({ vehicleLocal })" data-toggle="tooltip"
                                data-placement="top" title="Cotizar Repuestos">
                                <i class="fas fa-cog"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="!showTrash" class="table-list-toolbar">
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

        <div v-else class="vehicle-table-shell mt-3">
            <table class="table table-responsive-new table-dark table-sm vehicle-table mb-0">
                <thead>
                    <tr>
                        <th class="vehicle-col-id">ID</th>
                        <th class="vehicle-col-cliente">Cliente</th>
                        <th class="vehicle-col-patente">Patente</th>
                        <th class="vehicle-col-marca">Marca</th>
                        <th class="vehicle-col-modelo">Modelo</th>
                        <th class="vehicle-col-anio">Año</th>
                        <th class="vehicle-col-fecha">Eliminado</th>
                        <th class="vehicle-col-actions"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="vehicleLocal in vehiclesTrash" :key="vehicleLocal.id">
                        <td data-table-label="ID" class="vehicle-col-id">{{ vehicleLocal.id }}</td>
                        <td data-table-label="Cliente" class="vehicle-col-cliente vehicle-cell-wrap" :title="vehicleLocal.user.name">{{ vehicleLocal.user.name }}</td>
                        <td data-table-label="Patente" class="vehicle-col-patente vehicle-cell-strong" :title="vehicleLocal.patent">{{ vehicleLocal.patent }}</td>
                        <td data-table-label="Marca" class="vehicle-col-marca vehicle-cell-wrap" :title="vehicleLocal.brand">{{ vehicleLocal.brand }}</td>
                        <td data-table-label="Modelo" class="vehicle-col-modelo vehicle-cell-wrap" :title="vehicleLocal.model">{{ vehicleLocal.model }}</td>
                        <td data-table-label="Año" class="vehicle-col-anio vehicle-cell-meta">{{ vehicleLocal.year }}</td>
                        <td data-table-label="Eliminado" class="vehicle-col-fecha vehicle-cell-meta">{{ vehicleLocal.deleted_at | moment('DD/MM/YYYY') }}</td>

                        <td class="vehicle-col-actions vehicle-action-cell">
                            <a href="#" class="btn btn-success btn-icon-sm" @click.prevent="restoreVehicle({ id: vehicleLocal.id })"
                                data-toggle="tooltip" data-placement="top" title="Restaurar">
                                <i class="fas fa-trash-restore"></i>
                            </a>

                            <a href="#" class="btn btn-danger btn-icon-sm" @click.prevent="confirmForceDelete(vehicleLocal.id)"
                                data-toggle="tooltip" data-placement="top" title="Eliminar definitivamente">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                    <tr v-if="vehiclesTrash.length === 0">
                        <td colspan="8" class="text-center">La papelera esta vacia</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="showTrash" class="table-list-toolbar">
            <div class="table-list-toolbar__rows">
                <span>Filas</span>
                <select class="custom-select custom-select-sm" v-model.number="pagination_vehicle_trash.per_page"
                    @change="getVehiclesTrash({ page: 1, per_page: pagination_vehicle_trash.per_page })">
                    <option :value="10">10</option>
                    <option :value="20">20</option>
                    <option :value="50">50</option>
                </select>
            </div>
            <nav>
            <ul class="pagination">
                <li class="page-item" v-if="pagination_vehicle_trash.current_page > 1">
                    <a class="page-link" href="#" @click.prevent="changePageVehicleTrash({ page: 1, per_page: pagination_vehicle_trash.per_page })">
                        <span>Primera</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination_vehicle_trash.current_page > 1">
                    <a class="page-link" href="#"
                        @click.prevent="changePageVehicleTrash({ page: pagination_vehicle_trash.current_page - 1, per_page: pagination_vehicle_trash.per_page })">
                        <span>Atrás</span>
                    </a>
                </li>

                <li class="page-item" v-for="page in pagesNumber_vehicle_trash" v-bind:class="[page == isActived_vehicle_trash ? 'active' : '']"
                    :key="page">
                    <a class="page-link" href="#" @click.prevent="changePageVehicleTrash({ page, per_page: pagination_vehicle_trash.per_page })">
                        {{ page }}
                    </a>
                </li>

                <li class="page-item" v-if="pagination_vehicle_trash.current_page < pagination_vehicle_trash.last_page">
                    <a class="page-link" href="#"
                        @click.prevent="changePageVehicleTrash({ page: pagination_vehicle_trash.current_page + 1, per_page: pagination_vehicle_trash.per_page })">
                        <span>Siguiente</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination_vehicle_trash.current_page < pagination_vehicle_trash.last_page">
                    <a class="page-link" href="#" @click.prevent="changePageVehicleTrash({ page: pagination_vehicle_trash.last_page, per_page: pagination_vehicle_trash.per_page })">
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
        <RequestParts></RequestParts>
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
import RequestParts from './RequestParts'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: { Agregar, Editar, Detalle, AgregarDetalle, OrdenTrabajo, CheckListVehicle, RequestParts },
    data() {
        return {
            showTrash: false
        }
    },
    computed: {
        ...mapState(['vehicles', 'pagination', 'offset', 'searchVehicle', 'rol',
            'vehiclesTrash', 'pagination_vehicle_trash']),
        ...mapGetters(['isActived', 'pagesNumber', 'isActived_vehicle_trash', 'pagesNumber_vehicle_trash'])
    },
    methods: {
        ...mapActions(['getVehicles', 'getVehiclesUser', 'editVehicle', 'deleteVehicle',
            'detailVehicle', 'modalDetailVehicle', 'modalOrdenTrabajo', 'changePageVehicle', 'modalCheckList',
            'getVehiclesTrash', 'changePageVehicleTrash', 'restoreVehicle', 'forceDeleteVehicle', 'modalRequestParts']),
        toggleTrash() {
            this.showTrash = !this.showTrash
            if (this.showTrash) {
                this.getVehiclesTrash({ page: 1 })
            }
        },
        confirmForceDelete(id) {
            if (window.confirm('Esto elimina el vehiculo de forma definitiva y no se puede deshacer. ¿Continuar?')) {
                this.forceDeleteVehicle({ id })
            }
        }
    },
    created() {
        loadProgressBar();
        this.$store.dispatch('getVehicles', { page: 1 })
    }
}

</script>

<style>
.vehicle-table-shell {
    overflow-x: visible;
}

.vehicle-filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.vehicle-filter-input {
    max-width: 160px;
    font-size: 0.78rem;
    padding: 0.2rem 0.4rem;
    height: auto;
}

.vehicle-cell-strong,
.vehicle-cell-wrap {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.vehicle-cell-strong {
    font-weight: 600;
}

.vehicle-cell-meta {
    font-size: 0.78rem;
    text-align: center;
}

.vehicle-action-cell {
    display: flex;
    flex-wrap: nowrap;
    justify-content: flex-end;
    align-items: center;
    gap: 0.25rem;
    white-space: nowrap;
}

.vehicle-action-cell .btn {
    margin: 0;
    width: 28px;
    height: 28px;
    min-width: 28px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
}

@media (min-width: 769px) {
    .vehicle-admin .vehicle-table {
        table-layout: fixed;
        width: 100%;
    }

    .vehicle-admin .vehicle-table th,
    .vehicle-admin .vehicle-table td {
        padding: 0.28rem 0.32rem !important;
        font-size: 0.74rem;
        white-space: nowrap !important;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .vehicle-admin .vehicle-col-id {
        width: 2.8rem;
    }

    .vehicle-admin .vehicle-col-cliente {
        width: 14%;
    }

    .vehicle-admin .vehicle-col-patente {
        width: 6.5rem;
    }

    .vehicle-admin .vehicle-col-chasis {
        width: 9%;
    }

    .vehicle-admin .vehicle-col-marca {
        width: 9%;
    }

    .vehicle-admin .vehicle-col-modelo {
        width: 9%;
    }

    .vehicle-admin .vehicle-col-anio {
        width: 3.5rem;
    }

    .vehicle-admin .vehicle-col-motor {
        width: 10%;
    }

    .vehicle-admin .vehicle-col-color {
        width: 5rem;
    }

    .vehicle-admin .vehicle-col-km {
        width: 5.5rem;
    }

    .vehicle-admin .vehicle-col-fecha {
        width: 5rem;
    }

    .vehicle-admin .vehicle-col-actions {
        width: 10rem;
        overflow: visible !important;
        text-overflow: clip !important;
    }
}
</style>
