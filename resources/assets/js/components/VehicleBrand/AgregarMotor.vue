<template>
    <div class="row">
        <div class="col-12">
            <div id="accordion">
                <div class="card">

                    <div class="card-header p-0" id="headingOne">
                    <h5 class="mb-0">
                        <button id="btn-type-card" class="btn btn-block text-left p-3" data-toggle="collapse" data-target="#nuevo_motor"
                            aria-expanded="true" aria-controls="collapseOne">
                        Nuevo Motor
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
                                        <label for="year">Año</label>
                                        <SelectYearMotor></SelectYearMotor>
                                    </div>

                                    <div class="col-12">

                                        <label for="v_engine">Motor</label>
                                        <input v-validate="'required|min:2|max:190'"
                                                :class="{'input': true, 'is-invalid': errors.has('v_engine') }"
                                                type="text"
                                                name="v_engine"
                                                class="form-control" v-model="newVehicleMotor.v_engine">
                                        <p v-show="errors.has('v_engine')" class="text-danger">{{ errors.first('v_engine') }}</p>

                                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                            <p>{{ error.v_engine }}</p>
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
            <div class="table-responsive">
                <table class="table table-responsive-new table-dark table-sm mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Modelo</th>
                            <th>Año</th>
                            <th>Motor</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="vehiculoMotorLocal in vehiclemotors" :key="vehiculoMotorLocal.id">
                            <td data-table-label="ID">{{ vehiculoMotorLocal.id }}</td>
                            <td data-table-label="MODELO">{{ vehiculoMotorLocal.model }}</td>
                            <td data-table-label="AÑO">{{ vehiculoMotorLocal.year }}</td>
                            <td data-table-label="MOTOR">{{ vehiculoMotorLocal.motor }}</td>
                            
                            <td width="10px">
                                <a href="#" class="btn btn-warning btn-sm"
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
            <nav>
                <ul class="pagination">
                    <li class="page-item" v-if="pagination_motor.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehiculoMotor({page: 1})">
                            <span>Primera</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_motor.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehiculoMotor({page: pagination_motor.current_page - 1})">
                            <span>Atrás</span>
                        </a>
                    </li>

                    <li class="page-item" v-for="page in pagesNumber_motor"
                        v-bind:class="[ page == isActived_motor ? 'active' : '' ]" :key="page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehiculoMotor({page})">
                            {{ page }}
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_motor.current_page < pagination_motor.last_page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehiculoMotor({page: pagination_motor.current_page + 1})">
                            <span>Siguiente</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_motor.current_page < pagination_motor.last_page">
                        <a class="page-link border-light bg-dark" href="#"  @click.prevent="changePageVehiculoMotor({page:pagination_motor.last_page})">
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
import SelectYearMotor from './SelectYearMotor'
import EditarMotor from './EditarMotor'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: {SelectModelMotor, SelectYearMotor, EditarMotor},
    computed:{
        ...mapState(['newVehicleMotor', 'errorsLaravel' ,'vehiclemotors', 'pagination_motor', 'offset_motor']),
        ...mapGetters(['isActived_motor', 'pagesNumber_motor'])
    },
    methods:{
        ...mapActions(['createVehicleMotor', 'editVehiculoMotor','changePageVehiculoMotor'])
    },
    created(){
        loadProgressBar();
        this.$store.dispatch('getVehiculoMotors', { page: 1 })
    }
}

</script>
