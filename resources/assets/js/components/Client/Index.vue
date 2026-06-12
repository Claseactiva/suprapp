<template>
    <div class="col-lg-12">

        <h5 class="text-white">
            Administración de Cliente / Proveedores
        </h5>

        <div id="accordion">
            <div class="card">

                <div class="card-header p-0" id="headingOne">
                    <h5 class="mb-0">
                        <button id="btn-client-card" class="btn btn-block text-left p-3" data-toggle="collapse"
                            data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Nuevo Cliente / Proveedor
                            <span class="text-right"><i class="fas fa-arrows-alt-v"></i></span>
                        </button>
                    </h5>
                </div>

                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <form action="POST" v-on:submit.prevent="createClient">
                            <div class="row">

                                <div class="col-lg-4">
                                    <label for="rut">Rut</label>
                                    <input v-validate="'required|min:4'"
                                        :class="{ 'input': true, 'is-invalid': errors.has('rut') }" type="text"
                                        name="rut" class="form-control" v-model="newClient.rut">
                                    <p v-show="errors.has('rut')" class="text-danger">{{ errors.first('rut') }}</p>

                                    <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                        <p>{{ error.rut }}</p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for=""></label>
                                    <a href="#" class="btn btn-info btn-block mt-2" @click.prevent="searchSii"
                                        data-toggle="tooltip" data-placement="top" title="Buscar Datos en el Sii">
                                        <i class="far fa-edit"></i> Cargar Datos
                                    </a>
                                </div>

                                <div class="col-lg-4">
                                    <label for="cliente">Cliente</label>
                                    <input v-validate="'required'"
                                        :class="{ 'input': true, 'is-invalid': errors.has('cliente') }" type="text"
                                        name="cliente" class="form-control" v-model="newClient.name">
                                    <p v-show="errors.has('cliente')" class="text-danger">{{ errors.first('cliente') }}
                                    </p>

                                    <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                        <p>{{ error.name }}</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="razonSocial">Razón Social</label>
                                    <input v-validate="'required'"
                                        :class="{ 'input': true, 'is-invalid': errors.has('razonSocial') }" type="text"
                                        name="razonSocial" class="form-control" v-model="newClient.razonSocial">
                                    <p v-show="errors.has('razonSocial')" class="text-danger">{{
                                        errors.first('razonSocial') }}</p>

                                    <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                        <p>{{ error.razonSocial }}</p>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <label for="giro">Giro</label>
                                    <input v-validate="'required|min:4|max:190'"
                                        :class="{ 'input': true, 'is-invalid': errors.has('giro') }" type="text"
                                        name="giro" class="form-control" v-model="newClient.giro">
                                    <p v-show="errors.has('giro')" class="text-danger">{{ errors.first('giro') }}</p>

                                    <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                        <p>{{ error.giro }}</p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for="email">Email</label>
                                    <input v-validate="'required|max:190'"
                                        :class="{ 'input': true, 'is-invalid': errors.has('email') }" type="email"
                                        name="email" class="form-control" v-model="newClient.email">
                                    <p v-show="errors.has('email')" class="text-danger">{{ errors.first('email') }}
                                    </p>

                                    <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                        <p>{{ error.email }}</p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for="telefono">Teléfono</label>
                                    <input v-validate="'required'"
                                        :class="{ 'input': true, 'is-invalid': errors.has('telefono') }" type="text"
                                        name="telefono" class="form-control" v-model="newClient.phone">
                                    <p v-show="errors.has('telefono')" class="text-danger">{{ errors.first('telefono')
                                        }}</p>

                                    <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                        <p>{{ error.phone }}</p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for="comuna">Comuna</label>
                                    <input v-validate="'required'"
                                        :class="{ 'input': true, 'is-invalid': errors.has('comuna') }" type="text"
                                        name="comuna" class="form-control" v-model="newClient.comuna">
                                    <p v-show="errors.has('comuna')" class="text-danger">{{ errors.first('comuna') }}
                                    </p>

                                    <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                        <p>{{ error.comuna }}</p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for="direccion">Dirección</label>
                                    <input v-validate="'required'"
                                        :class="{ 'input': true, 'is-invalid': errors.has('direccion') }" type="text"
                                        name="direccion" class="form-control" v-model="newClient.address">
                                    <p v-show="errors.has('direccion')" class="text-danger">{{ errors.first('direccion')
                                        }}</p>

                                    <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                        <p>{{ error.address }}</p>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <label for="tipo">Tipo</label>
                                    <select class="form-control" v-model="newClient.type">
                                        <option>Cliente</option>
                                        <option>Proveedor</option>
                                        <option>Empresa</option>
                                    </select>
                                </div>

                                <div class="col-lg-3 mt-2">
                                    <label></label>
                                    <button type="submit" class="btn btn-success form-control"
                                        :disabled="!completeClientCreate">
                                        <i class="fas fa-plus-square"></i> Guardar
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


        <div class="table-responsive">
            <table class="table table-responsive-new table-dark table-sm mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Rut</th>
                        <th>Razón Social</th>
                        <th>Dirección</th>
                        <th>Giro</th>
                        <th>Tipo</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="clientLocal in clients" :key="clientLocal.id">
                        <td data-table-label="ID">{{ clientLocal.id }}</td>
                        <td data-table-label="RUT">{{ clientLocal.rut }}</td>
                        <td data-table-label="Razón Social">{{ clientLocal.razonSocial }}</td>
                        <td data-table-label="Dirección">{{ clientLocal.address }}</td>
                        <td data-table-label="Giro" class="w-lg-25 w-md-100">{{ clientLocal.giro }}</td>
                        <td data-table-label="Tipo">{{ clientLocal.type }}</td>
                        <td>

                            <a href="#" class="btn btn-info btn-sm" @click.prevent="detailClient({ clientLocal })"
                                data-toggle="tooltip" data-placement="top" title="Detalle">
                                <i class="fas fa-info"></i>
                            </a>

                            <a href="#" class="btn btn-warning btn-sm" @click.prevent="editClient({ clientLocal })"
                                data-toggle="tooltip" data-placement="top" title="Editar">
                                <i class="far fa-edit"></i>
                            </a>

                            <a href="#" class="btn btn-danger btn-sm"
                                @click.prevent="modalDeleteClient({ id: clientLocal.id })" data-toggle="tooltip"
                                data-placement="top" title="Eliminar">
                                <i class="fas fa-ban"></i>
                            </a>

                        </td>
                    </tr>
                </tbody>
            </table>

            <nav>
                <ul class="pagination">
                    <li class="page-item bg-" v-if="pagination.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#"
                            @click.prevent="changePageClient({ page: 1 })">
                            <span>Primera</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination.current_page > 1">
                        <a class="page-link border-light bg-dark" href="#"
                            @click.prevent="changePageClient({ page: pagination.current_page - 1 })">
                            <span>Atrás</span>
                        </a>
                    </li>

                    <li class="page-item" v-for="page in pagesNumber" v-bind:class="[page == isActived ? 'active' : '']"
                        :key="page">
                        <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageClient({ page })">
                            {{ page }}
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                        <a class="page-link border-light bg-dark" href="#"
                            @click.prevent="changePageClient({ page: pagination.current_page + 1 })">
                            <span>Siguiente</span>
                        </a>
                    </li>

                    <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                        <a class="page-link border-light bg-dark" href="#"
                            @click.prevent="changePageClient({ page: pagination.last_page })">
                            <span>Última</span>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>

        <Detalle></Detalle>
        <Editar></Editar>
        <Eliminar></Eliminar>


    </div>
</template>

<script>

import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapActions, mapGetters } from 'vuex'
import Detalle from './Detalle'
import Editar from './Editar'
import Eliminar from './Delete'

export default {
    components: { Detalle, Editar, Eliminar },
    computed: {
        ...mapState(['clients', 'newClient', 'pagination', 'offset', 'errorsLaravel']),
        ...mapGetters(['isActived', 'pagesNumber', 'completeClientCreate'])
    },
    methods: {
        ...mapActions(['getClients', 'createClient', 'detailClient',
            'editClient', 'modalDeleteClient', 'deleteClient', 'changePageClient', 'searchSii'])
    },
    created() {
        loadProgressBar()
        this.$store.dispatch('getClients', { page: 1 })
    }
}

</script>

<style></style>
