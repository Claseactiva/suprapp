<template>
    <div class="row">
        <div class="col-12">
            <div id="accordion">
                <div class="card">
                    <div class="card-header p-0" id="headingOne">
                    <h5 class="mb-0">
                        <button id="btn-type-card" class="btn btn-block text-left p-3" data-toggle="collapse" data-target="#nuevo_year"
                            aria-expanded="true" aria-controls="collapseOne">
                        Nuevo Año
                        <span class="text-right"><i class="fas fa-arrows-alt-v"></i></span>
                        </button>
                    </h5>
                    </div>

                    <div id="nuevo_year" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <form action="POST" v-on:submit.prevent="createVehicleYear">
                                <div class="row">

                                    <div class="col-12">
                                        <label for="model">Marca</label>
                                        <SelectBrandYear></SelectBrandYear>
                                    </div>

                                    <div class="col-12">
                                        <label for="model">Modelo</label>
                                        <SelectModelYear></SelectModelYear>
                                    </div>

                                    <div class="col-12">

                                        <label for="v_year">Año</label>
                                        <input v-validate="'required|numeric|max:9999'"
                                                :class="{'input': true, 'is-invalid': errors.has('v_year') }"
                                                type="number"
                                                name="v_year"
                                                class="form-control" v-model="newVehicleYear.v_year">
                                        <p v-show="errors.has('v_id')" class="text-danger">{{ errors.first('v_year') }}</p>

                                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                            <p>{{ error.v_year }}</p>
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
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Año</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="vehicleyearLocal in vehicleyears" :key="vehicleyearLocal.id">
                            <td data-table-label="ID">{{ vehicleyearLocal.id }}</td>
                            <td data-table-label="MARCA">{{ vehicleyearLocal.brand }}</td>
                            <td data-table-label="MODELO">{{ vehicleyearLocal.model }}</td>
                            <td data-table-label="AÑO">{{ vehicleyearLocal.year }}</td>
                            <td width="10px">
                                <a href="#" class="btn btn-warning btn-sm"
                                        @click.prevent="editVehicleYear( { vehicleyearLocal } )"
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
                    <li class="page-item" v-if="pagination_year.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehicleYear({page: 1})">
                            <span>Primera</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_year.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehicleYear({page: pagination_year.current_page - 1})">
                            <span>Atrás</span>
                        </a>
                    </li>

                    <li class="page-item" v-for="page in pagesNumber_year"
                        v-bind:class="[ page == isActived_year ? 'active' : '' ]" :key="page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehicleYear({page})">
                            {{ page }}
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_year.current_page < pagination_year.last_page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehicleYear({page: pagination_year.current_page + 1})">
                            <span>Siguiente</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_year.current_page < pagination_year.last_page">
                        <a class="page-link border-light bg-dark" href="#"  @click.prevent="changePageVehicleYear({page:pagination_year.last_page})">
                            <span>Última</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <EditarYear></EditarYear>
    </div>
</template>
<script>

import { loadProgressBar } from 'axios-progress-bar'
import SelectBrandYear from './SelectBrandYear'
import SelectModelYear from './SelectModelYear'
import EditarYear from './EditarYear'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: {SelectBrandYear, SelectModelYear, EditarYear},
    computed:{
        ...mapState(['newVehicleYear', 'errorsLaravel', 'vehicleyears', 'pagination_year', 'offset_year']),
        ...mapGetters(['isActived_year', 'pagesNumber_year'])
    },
    methods:{
        ...mapActions(['createVehicleYear','editVehicleYear', 'AgregarVehicleMotor','changePageVehicleYear'])
    },
    created(){
        loadProgressBar();
        this.$store.dispatch('getVehicleYears', { page: 1 })
    }
}
</script>