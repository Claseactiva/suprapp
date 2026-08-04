<template>
    <div class="row">
        <div class="col-12">
            <div id="accordion">
                <div class="card">

                    <div class="card-header p-0" id="headingOne">
                    <h5 class="mb-0">
                        <button id="btn-type-card" class="btn btn-block text-left vehiclebrand-collapse-header" data-toggle="collapse" data-target="#nuevo_tipo"
                            aria-expanded="true" aria-controls="collapseOne">
                        Nuevo Tipo de vehiculo
                        <span class="text-right"><i class="fas fa-arrows-alt-v"></i></span>
                        </button>
                    </h5>
                    </div>

                    <div id="nuevo_tipo" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <form action="POST" v-on:submit.prevent="createVehiculoTipo">
                                <div class="row">

                                    <div class="col">

                                        <label for="tipo_vehiculo">Nombre</label>
                                        <input v-validate="'required|min:2|max:190'"
                                                :class="{'input': true, 'is-invalid': errors.has('tipo_vehiculo') }"
                                                type="text"
                                                name="tipo_vehiculo"
                                                class="form-control" v-model="newVehiculoTipo.tipo_vehiculo">
                                        <p v-show="errors.has('tipo_vehiculo')" class="text-danger">{{ errors.first('tipo_vehiculo') }}</p>

                                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                            <p>{{ error.tipo_vehiculo }}</p>
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
            <input type="text" class="form-control mb-3 vehiclebrand-search-input" id="search_tipo" placeholder="Ej: camioneta, sedan..."
                v-model="searchVehiculoTipoText" @input="getVehiculoTipos({ page: 1, search: searchVehiculoTipoText })">
            <div class="table-responsive">
                <table class="table table-responsive-new table-dark table-sm mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo de vehiculo</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="vehiculoTipoLocal in vehiculotipos" :key="vehiculoTipoLocal.id">
                            <td data-table-label="ID">{{ vehiculoTipoLocal.id }}</td>
                            <td data-table-label="TIPO DE VEHICULO">{{ vehiculoTipoLocal.tipo_vehiculo }}</td>

                            <td width="10px">
                                <a href="#" class="btn btn-warning btn-icon-sm"
                                    @click.prevent="editVehiculoTipo( { vehiculoTipoLocal } )"
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
                    <li class="page-item" v-if="pagination_tipo.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehiculoTipo({page: 1, search: searchVehiculoTipoText})">
                            <span>Primera</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_tipo.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehiculoTipo({page: pagination_tipo.current_page - 1, search: searchVehiculoTipoText})">
                            <span>Atrás</span>
                        </a>
                    </li>

                    <li class="page-item" v-for="page in pagesNumber_tipo"
                        v-bind:class="[ page == isActived_tipo ? 'active' : '' ]" :key="page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehiculoTipo({page, search: searchVehiculoTipoText})">
                            {{ page }}
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_tipo.current_page < pagination_tipo.last_page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageVehiculoTipo({page: pagination_tipo.current_page + 1, search: searchVehiculoTipoText})">
                            <span>Siguiente</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_tipo.current_page < pagination_tipo.last_page">
                        <a class="page-link border-light bg-dark" href="#"  @click.prevent="changePageVehiculoTipo({page:pagination_tipo.last_page, search: searchVehiculoTipoText})">
                            <span>Última</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <EditarTipo></EditarTipo>
    </div>
</template>
<script>

import { loadProgressBar } from 'axios-progress-bar'
import EditarTipo from './EditarTipo'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: {EditarTipo},
    data() {
        return {
            searchVehiculoTipoText: ''
        }
    },
    computed:{
        ...mapState(['newVehiculoTipo', 'errorsLaravel' ,'vehiculotipos', 'pagination_tipo', 'offset_tipo']),
        ...mapGetters(['isActived_tipo', 'pagesNumber_tipo'])
    },
    methods:{
        ...mapActions(['createVehiculoTipo', 'editVehiculoTipo','changePageVehiculoTipo', 'getVehiculoTipos'])
    },
    created(){
        loadProgressBar();
        this.$store.dispatch('getVehiculoTipos', { page: 1 })
    }
}

</script>
