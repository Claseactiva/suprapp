<template>
    <div class="col-lg-12">
        <h5 class="text-white">
            Administración de Cotizaciones
        </h5>
        <div id="accordion">
            <div class="card">
                <div class="card-header p-0" id="headingOne">
                    <h5 class="mb-0">
                        <button id="btn-quotation-card" class="btn btn-block text-left p-3" data-toggle="collapse" data-target="#collapseOne"
                            aria-expanded="true" aria-controls="collapseOne">
                            Nueva Cotización
                            <span class="text-right"><i class="fas fa-arrows-alt-v"></i></span>
                        </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <form action="POST" v-on:submit.prevent="createQuotation">
                            <div class="row">
                                <div class="col-lg-3">
                                    <label for="cliente">Cliente</label>
                                    <input v-validate="'min:3|max:190'"
                                        :class="{'input': true, 'is-invalid': errors.has('nombre') }"
                                            type="text"
                                            name="cliente"
                                            class="form-control" v-model="newQuotation.client">
                                    <p v-show="errors.has('cliente')" class="text-danger">{{ errors.first('cliente') }}</p>
                                    <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                        <p>{{ error.name }}</p>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label for="vehiculo">Vehículo</label>
                                    <input v-validate="'min:3|max:190'"
                                            :class="{'input': true, 'is-invalid': errors.has('vehiculo') }"
                                            type="text"
                                            name="vehiculo"
                                            class="form-control" v-model="newQuotation.vehicle">
                                    <p v-show="errors.has('vehiculo')"
                                        class="text-danger">{{ errors.first('vehiculo') }}</p>

                                    <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                        <p>{{ error.vehicle }}</p>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <label for="patent">Patente</label>
                                    <input v-validate="'min:3|max:190'"
                                            :class="{'input': true, 'is-invalid': errors.has('patent') }"
                                            type="text"
                                            name="patent"
                                            class="form-control" v-model="newQuotation.patent">
                                    <p v-show="errors.has('patent')"
                                        class="text-danger">{{ errors.first('patent') }}</p>

                                    <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                        <p>{{ error.patent }}</p>
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
        <div class="col-lg-12 table-responsive">
            <table class="table table-borderless table-dark table-hover table-striped mt-3 table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Estado</th>
                        <th>Cliente</th>
                        <th>Correo</th> 
                        <th>Teléfono</th>
                        <th>Patente o Chasis</th>
                        <th>Vehículo</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="quotationLocal in pendingQuotations" :key="quotationLocal.id">
                        <td width="10px">{{ quotationLocal.id }}</td>
                        <td>{{ quotationLocal.is_completed }}</td>
                        <td>{{ quotationLocal.name }}</td>
                        <td>{{ quotationLocal.email }}</td>
                        <td>{{ quotationLocal.phone }}</td>
                        <td>{{ quotationLocal.patentchasis }}</td>
                        <td>{{ quotationLocal.brand + ' ' + quotationLocal.model + ' ' + quotationLocal.year + ' ' +quotationLocal.engine}}</td>
                        <td>{{ quotationLocal.description }}</td>
                        <td>{{ quotationLocal.created_at |  moment('DD/MM/YYYY') }}</td>
                        <td width="150px">
                            <a href="#" class="btn btn-warning btn-sm"
                                @click.prevent="showModalDetail({ id: quotationLocal.id })"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-info btn-sm"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="Detalles">
                                <i class="fas fa-info"></i>
                            </a>
                            <a href="#" class="btn btn-success btn-sm"
                                data-toggle="tooltip"
                                data-placement="top"
                                title="Completar">
                                <i class="fas fa-check"></i>
                            </a>
                            <a href="#" class="btn btn-light btn-sm"
                                @click.prevent="modalCreateUserFromQuotation({ name: quotationLocal.name, email: quotationLocal.email })"
                                data-toggle="tooltip"
                                data-placeemnt="top"
                                title="Crear Usuario">
                                <i class="fas fa-user"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <nav>
                <ul class="pagination">
                    <li class="page-item" v-if="pagination.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageQuotation({page: 1})">
                            <span>Primera</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageQuotation({page: pagination.current_page - 1})">
                            <span>Atrás</span>
                        </a>
                    </li>

                    <li class="page-item" v-for="page in pagesNumber"
                        v-bind:class="[ page == isActived ? 'active' : '' ]" :key="page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageQuotation({page})">
                            {{ page }}
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageQuotation({page: pagination.current_page + 1})">
                            <span>Siguiente</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                        <a class="page-link border-light bg-dark" href="#"  @click.prevent="changePageQuotation({page:pagination.last_page})">
                            <span>Última</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <Detalle></Detalle>
        <create-user></create-user>
    </div>
</template>


<script>

import { loadProgressBar } from 'axios-progress-bar'
import Detalle from './Detalle'
import CreateUser from './CreateUser'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: { Detalle, CreateUser },
    computed:{
        ...mapState(['pendingQuotations', 'newQuotation', 'searchQuotation',
                    'pagination', 'offset', 'errorsLaravel']),
        ...mapGetters(['isActived', 'pagesNumber'])
    },
    methods:{
        ...mapActions(['getPendingQuotations', 'createQuotation', 'showModalDetail', 'modalCreateUserFromQuotation'])
    },
    created(){
        loadProgressBar();
        this.$store.dispatch('getPendingQuotations', { page: 1 })
    }
}

</script>

<style>
    table, nav {
        font-size: 13px;
    }
</style>
