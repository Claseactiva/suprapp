<template>

    <div>

        <div class="col-lg-12">
            <h5 class="text-white">
                Administración de Clientes (Mecánico)
            </h5>

            <div id="accordion">
                <div class="card">

                    <div class="card-header p-0" id="headingOne">
                        <h5 class="mb-0">
                            <button id="btn-user-card" class="btn btn-block text-left p-3" data-toggle="collapse"
                                data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Nuevo Usuario
                                <span class="text-right"><i class="fas fa-arrows-alt-v"></i></span>
                            </button>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <form action="POST" v-on:submit.prevent="createMechanicClient2">
                                <div class="row">

                                    <div class="form-group col-lg-3">
                                        <label for="nombre">Nombre</label>
                                        <input required type="text" name="nombre" class="form-control"
                                            v-model="newUser.name">
                                    </div>

                                    <div class="form-group col-lg-3">
                                        <label for="correo">Email</label>
                                        <input required type="email" name="correo" class="form-control"
                                            v-model="newUser.email">
                                    </div>

                                    <div class="form-group col-lg-3">
                                        <label for="password">Contraseña</label>
                                        <input required type="password" name="password" class="form-control"
                                            v-model="newUser.password">
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

            <div class="table-responsive">
                <table class="table table-responsive-new table-dark table-sm mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Puede crear</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="userLocal in users" :key="userLocal.id">
                            <td data-table-label="ID">{{ userLocal.id }}</td>
                            <td data-table-label="nombre">{{ userLocal.name }}</td>
                            <td data-table-label="email">{{ userLocal.email }}</td>
                            <td data-table-label="Puede crear">1 - {{ userLocal.cant_vehicle }} <i
                                    class="fas fa-car"></i></td>

                            <td class="text-right">

                                <button class="btn btn-secondary" @click.prevent="editCantVehicle({ userLocal })"
                                    data-toggle="tooltip" data-placement="top" title="Cantidad de vehiculos">
                                    <i class="fas fa-info-circle"></i>
                                </button>

                                <a href="#" class="btn btn-warning" @click.prevent="editUser({ userLocal })"
                                    data-toggle="tooltip" data-placement="top" title="Editar">
                                    <i class="far fa-edit"></i>
                                </a>

                            </td>
                        </tr>
                    </tbody>
                </table>

                <nav>
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link border-light bg-dark" href="#"
                                @click.prevent="changePageUser({ page: 1 })">
                                <span>Primera</span>
                            </a>
                        </li>

                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link border-light bg-dark" href="#"
                                @click.prevent="changePageUser({ page: pagination.current_page - 1 })">
                                <span>Atrás</span>
                            </a>
                        </li>

                        <li class="page-item" v-for="page in pagesNumber"
                            v-bind:class="[page == isActived ? 'active' : '']" :key="page">
                            <a class="page-link border-light bg-dark" href="#"
                                @click.prevent="changePageUser({ page })">
                                {{ page }}
                            </a>
                        </li>

                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link border-light bg-dark" href="#"
                                @click.prevent="changePageUser({ page: pagination.current_page + 1 })">
                                <span>Siguiente</span>
                            </a>
                        </li>

                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link border-light bg-dark" href="#"
                                @click.prevent="changePageUser({ page: pagination.last_page })">
                                <span>Última</span>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>
            <EditCantVehicle></EditCantVehicle>
            <EditUser></EditUser>
        </div>
    </div>

</template>


<script>

import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapActions, mapGetters } from 'vuex'
import EditUser from './EditUser'
import EditCantVehicle from './EditCantVehicle'

export default {
    components: { EditUser, EditCantVehicle },
    computed: {
        ...mapState(['users', 'newUser', 'pagination', 'offset', 'errorsLaravel']),
        ...mapGetters(['isActived', 'pagesNumber'])
    },
    methods: {
        ...mapActions(['getMechanicClients', 'createMechanicClient2', 'editUser', 'changePageUser', 'editCantVehicle'])
    },
    created() {
        loadProgressBar()
        this.$store.dispatch('getMechanicClients', { page: 1 })
    }
}

</script>

<style></style>
