<template>
    <div class="row">
        <div class="col-12">
            <div id="accordion">
                <div class="card">

                    <div class="card-header p-0" id="headingOne">
                    <h5 class="mb-0">
                        <button id="btn-type-card" class="btn btn-block text-left vehiclebrand-collapse-header" data-toggle="collapse" data-target="#nuevo_motor"
                            aria-expanded="true" aria-controls="collapseOne">
                        Vincular Motor a Modelo
                        <span class="text-right"><i class="fas fa-arrows-alt-v"></i></span>
                        </button>
                    </h5>
                    </div>

                    <div id="nuevo_motor" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <form action="POST" v-on:submit.prevent="createVehicleMotor">
                                <div class="row">

                                    <div class="col-12">
                                        <label for="model">Modelo</label>
                                        <SelectModelMotor></SelectModelMotor>
                                    </div>

                                    <div class="col-12">
                                        <label for="motor_spec">Motor</label>
                                        <SelectMotorSpec></SelectMotorSpec>

                                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                            <p>{{ error.motor_spec_id }}</p>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label for="year">Año</label>
                                        <input v-validate="'required|numeric|min_value:1900|max_value:2100'"
                                                :class="{'input': true, 'is-invalid': errors.has('year') }"
                                                type="number"
                                                name="year"
                                                class="form-control" v-model="newVehicleMotor.year">
                                        <p v-show="errors.has('year')" class="text-danger">{{ errors.first('year') }}</p>

                                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                            <p>{{ error.year }}</p>
                                        </div>
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
            <input type="text" class="form-control mb-3 vehiclebrand-search-input" id="search_motor" placeholder="Ej: mazda, yaris..."
                v-model="searchVehicleMotor" @input="getVehiculoMotors({ page: 1, search: searchVehicleMotor })">
            <div class="table-responsive">
                <table class="table table-responsive-new table-dark table-sm mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Período</th>
                            <th>Motor</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="vehiculoMotorLocal in vehiclemotors" :key="vehiculoMotorLocal.id">
                            <td data-table-label="ID">{{ vehiculoMotorLocal.id }}</td>
                            <td data-table-label="MARCA">{{ vehiculoMotorLocal.brand }}</td>
                            <td data-table-label="MODELO">{{ vehiculoMotorLocal.model }}</td>
                            <td data-table-label="PERIODO">{{ vehiculoMotorLocal.year_from }}<span v-if="vehiculoMotorLocal.year_from !== vehiculoMotorLocal.year_to"> - {{ vehiculoMotorLocal.year_to }}</span></td>
                            <td data-table-label="MOTOR">{{ vehiculoMotorLocal.motor }}</td>

                            <td width="10px">
                                <a href="#" class="btn btn-warning btn-icon-sm"
                                    @click.prevent="editVehiculoMotor( { vehiculoMotorLocal } )"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Editar">
                                    <i class="far fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <nav class="mt-3">
                <ul class="pagination">
                    <li class="page-item" v-if="pagination_motor.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehiculoMotor({page: 1, search: searchVehicleMotor})">
                            <span>Primera</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_motor.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehiculoMotor({page: pagination_motor.current_page - 1, search: searchVehicleMotor})">
                            <span>Atrás</span>
                        </a>
                    </li>

                    <li class="page-item" v-for="page in pagesNumber_motor"
                        v-bind:class="[ page == isActived_motor ? 'active' : '' ]" :key="page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehiculoMotor({page, search: searchVehicleMotor})">
                            {{ page }}
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_motor.current_page < pagination_motor.last_page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehiculoMotor({page: pagination_motor.current_page + 1, search: searchVehicleMotor})">
                            <span>Siguiente</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_motor.current_page < pagination_motor.last_page">
                        <a class="page-link border-light bg-dark" href="#"  @click.prevent="changePageVehiculoMotor({page:pagination_motor.last_page, search: searchVehicleMotor})">
                            <span>Última</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <EditarMotor></EditarMotor>
    </div>
</template>
<script>

import { loadProgressBar } from 'axios-progress-bar'
import SelectModelMotor from './SelectModelMotor'
import SelectMotorSpec from './SelectMotorSpec'
import EditarMotor from './EditarMotor'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: {SelectModelMotor, SelectMotorSpec, EditarMotor},
    data() {
        return {
            searchVehicleMotor: ''
        }
    },
    computed:{
        ...mapState(['newVehicleMotor', 'errorsLaravel' ,'vehiclemotors', 'pagination_motor', 'offset_motor']),
        ...mapGetters(['isActived_motor', 'pagesNumber_motor'])
    },
    methods:{
        ...mapActions(['createVehicleMotor', 'editVehiculoMotor','changePageVehiculoMotor', 'getVehiculoMotors'])
    },
    created(){
        loadProgressBar();
        this.$store.dispatch('getVehiculoMotors', { page: 1 })
    }
}

</script>
