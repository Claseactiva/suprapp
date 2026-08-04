<template>
    <div class="row">
        <div class="col-12">
            <div id="accordion2">
                <div class="card">

                    <div class="card-header p-0" id="headingMotorSpec">
                    <h5 class="mb-0">
                        <button id="btn-type-card" class="btn btn-block text-left vehiclebrand-collapse-header" data-toggle="collapse" data-target="#nuevo_motorspec"
                            aria-expanded="true" aria-controls="collapseOne">
                        Especificaciones de Motor
                        <span class="text-right"><i class="fas fa-arrows-alt-v"></i></span>
                        </button>
                    </h5>
                    </div>

                    <div id="nuevo_motorspec" class="collapse" aria-labelledby="headingMotorSpec" data-parent="#accordion2">
                        <div class="card-body">
                            <form action="POST" v-on:submit.prevent="createMotorSpec">
                                <div class="row">

                                    <div class="col">
                                        <label for="cilindrada">Cilindrada</label>
                                        <input v-validate="'required|decimal:2|min_value:0'"
                                                :class="{'input': true, 'is-invalid': errors.has('cilindrada') }"
                                                type="number" step="0.1"
                                                name="cilindrada"
                                                class="form-control" v-model="newMotorSpec.cilindrada">
                                        <p v-show="errors.has('cilindrada')" class="text-danger">{{ errors.first('cilindrada') }}</p>

                                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                            <p>{{ error.cilindrada }}</p>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <label for="combustible">Combustible</label>
                                        <select v-validate="'required'"
                                                :class="{'input': true, 'is-invalid': errors.has('combustible') }"
                                                name="combustible"
                                                class="form-control" v-model="newMotorSpec.combustible">
                                            <option value="">Seleccionar...</option>
                                            <option value="BENCINA">BENCINA</option>
                                            <option value="DIESEL">DIESEL</option>
                                            <option value="ELECTRICO">ELECTRICO</option>
                                        </select>
                                        <p v-show="errors.has('combustible')" class="text-danger">{{ errors.first('combustible') }}</p>

                                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                            <p>{{ error.combustible }}</p>
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
            <input type="text" class="form-control mb-3 vehiclebrand-search-input" id="search_motorspec" placeholder="Buscar motor..."
                v-model="searchMotorSpec" @input="getMotorSpecs({ page: 1, search: searchMotorSpec })">
            <div class="table-responsive">
                <table class="table table-responsive-new table-dark table-sm mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Motor</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="motorspecLocal in motorspecs" :key="motorspecLocal.id">
                            <td data-table-label="ID">{{ motorspecLocal.id }}</td>
                            <td data-table-label="MOTOR">{{ motorspecLocal.raw_label }}</td>

                            <td width="20px">
                                <a href="#" class="btn btn-warning btn-icon-sm"
                                    @click.prevent="editMotorSpec( { motorspecLocal } )"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Editar">
                                    <i class="far fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-icon-sm"
                                    @click.prevent="deleteMotorSpec(motorspecLocal.id)"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Eliminar">
                                    <i class="far fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <nav class="mt-3">
                <ul class="pagination">
                    <li class="page-item" v-if="pagination_motorspec.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageMotorSpec({page: 1})">
                            <span>Primera</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_motorspec.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageMotorSpec({page: pagination_motorspec.current_page - 1})">
                            <span>Atrás</span>
                        </a>
                    </li>

                    <li class="page-item" v-for="page in pagesNumber_motorspec"
                        v-bind:class="[ page == isActived_motorspec ? 'active' : '' ]" :key="page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageMotorSpec({page})">
                            {{ page }}
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_motorspec.current_page < pagination_motorspec.last_page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageMotorSpec({page: pagination_motorspec.current_page + 1})">
                            <span>Siguiente</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination_motorspec.current_page < pagination_motorspec.last_page">
                        <a class="page-link border-light bg-dark" href="#"  @click.prevent="changePageMotorSpec({page:pagination_motorspec.last_page})">
                            <span>Última</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <EditarMotorSpec></EditarMotorSpec>
    </div>
</template>
<script>

import { loadProgressBar } from 'axios-progress-bar'
import EditarMotorSpec from './EditarMotorSpec'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: {EditarMotorSpec},
    data() {
        return {
            searchMotorSpec: ''
        }
    },
    computed:{
        ...mapState(['newMotorSpec', 'errorsLaravel' ,'motorspecs', 'pagination_motorspec', 'offset_motorspec']),
        ...mapGetters(['isActived_motorspec', 'pagesNumber_motorspec'])
    },
    methods:{
        ...mapActions(['createMotorSpec', 'editMotorSpec', 'deleteMotorSpec', 'changePageMotorSpec', 'getMotorSpecs'])
    },
    created(){
        loadProgressBar();
        this.$store.dispatch('getMotorSpecs', { page: 1 })
    }
}

</script>
