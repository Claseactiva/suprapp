<template>
    <div class="row">
        <div class="col-12">
            <div id="accordion">
                <div class="card">

                    <div class="card-header p-0" id="headingOne">
                    <h5 class="mb-0">
                        <button id="btn-brand-card" class="btn btn-block text-left p-3" data-toggle="collapse" data-target="#nueva_marca"
                            aria-expanded="true" aria-controls="collapseOne">
                        Nueva Marca
                        <span class="text-right"><i class="fas fa-arrows-alt-v"></i></span>
                        </button>
                    </h5>
                    </div>

                    <div id="nueva_marca" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <form action="POST" v-on:submit.prevent="createVehicleBrand">
                                <div class="row">

                                    <div class="col">

                                        <label for="marca">Marca</label>
                                        <input v-validate="'required|min:2|max:190'"
                                                :class="{'input': true, 'is-invalid': errors.has('marca') }"
                                                type="text"
                                                name="marca"
                                                class="form-control" v-model="newVehicleBrand.brand">
                                        <p v-show="errors.has('marca')" class="text-danger">{{ errors.first('marca') }}</p>

                                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                            <p>{{ error.brand }}</p>
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
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="vehiclebrandLocal in vehiclebrands" :key="vehiclebrandLocal.id">
                            <td data-table-label="ID">{{ vehiclebrandLocal.id }}</td>
                            <td data-table-label="MARCA">{{ vehiclebrandLocal.brand }}</td>
                            <td width="10px">
                                <a href="#" class="btn btn-warning btn-sm"
                                    @click.prevent="editVehicleBrand( { vehiclebrandLocal } )"
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
                    <li class="page-item" v-if="pagination_marca.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehicleBrand({page: 1})">
                            <span>Primera</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_marca.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehicleBrand({page: pagination_marca.current_page - 1})">
                            <span>Atrás</span>
                        </a>
                    </li>

                    <li class="page-item" v-for="page in pagesNumber_marca"
                        v-bind:class="[ page == isActived_marca ? 'active' : '' ]" :key="page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehicleBrand({page})">
                            {{ page }}
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_marca.current_page < pagination_marca.last_page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehicleBrand({page: pagination_marca.current_page + 1})">
                            <span>Siguiente</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_marca.current_page < pagination_marca.last_page">
                        <a class="page-link border-light bg-dark" href="#"  @click.prevent="changePageVehicleBrand({page:pagination_marca.last_page})">
                            <span>Última</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <EditarMarca></EditarMarca>
    </div>
</template>
<script>

import { loadProgressBar } from 'axios-progress-bar'
import EditarMarca from './EditarMarca'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: {EditarMarca},
    computed:{
        ...mapState(['newVehicleBrand', 'errorsLaravel', 'vehiclebrands', 'pagination_marca', 'offset_marca']),
        ...mapGetters(['isActived_marca', 'pagesNumber_marca'])
    },
    methods:{
        ...mapActions(['createVehicleBrand', 'editVehicleBrand','changePageVehicleBrand'])
    },
    created(){
        loadProgressBar();
        this.$store.dispatch('getVehicleBrands', { page: 1 })
    }
}

</script>