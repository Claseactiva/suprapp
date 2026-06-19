<template>

    <div>

        <div class="col-lg-12">
            <h5 class="text-white">
                Administración de Usuarios

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
                            <form action="POST" v-on:submit.prevent="createUser">
                                <div class="row">

                                    <div class="form-group col-lg-3">
                                        <label for="nombre">Nombre</label>
                                        <input v-validate="'required|min:4|max:190'"
                                            :class="{ 'input': true, 'is-invalid': errors.has('nombre') }" type="text"
                                            name="nombre" class="form-control" v-model="newUser.name">

                                        <div v-for="(error, key) in errorsLaravel" class="text-danger"
                                            :key="key['errors']">
                                            <div v-for="(errorItem, index) in error" :key="index">
                                                <div v-if="index === 'name'">
                                                    {{ errorItem[0] }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-lg-3">
                                        <label for="email">Email</label>
                                        <input v-validate="'required'"
                                            :class="{ 'input': true, 'is-invalid': errors.has('email') }" type="email"
                                            name="email" class="form-control" v-model="newUser.email">

                                        <div v-for="(error, key) in errorsLaravel" class="text-danger"
                                            :key="key['errors']">
                                            <div v-for="(errorItem, index) in error" :key="index">
                                                <div v-if="index === 'email'">
                                                    {{ errorItem[0] }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group col-lg-3">
                                        <label for="password">Contraseña</label>
                                        <input v-validate="'required'"
                                            :class="{ 'input': true, 'is-invalid': errors.has('password') }"
                                            type="password" name="password" class="form-control"
                                            v-model="newUser.password">

                                        <div v-for="(error, key) in errorsLaravel" class="text-danger"
                                            :key="key['errors']">
                                            <div v-for="(errorItem, index) in error" :key="index">
                                                <div v-if="index === 'password'">
                                                    {{ errorItem[0] }}
                                                </div>
                                            </div>
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


            <div class="table-responsive">
                <table class="table table-responsive-new table-dark table-sm mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="userLocal in users" :key="userLocal.id">
                            <td data-table-label="ID">{{ userLocal.id }}</td>
                            <td data-table-label="Nombre">{{ userLocal.name }}</td>
                            <td data-table-label="email">{{ userLocal.email }}</td>
                            <td class="text-right">

                                <button class="btn btn-success" @click.prevent="editUserRoles({ userLocal })"
                                    data-toggle="tooltip" data-placement="top" title="Asignar Rol">
                                    <i class="fas fa-user-cog"></i>
                                </button>


                                <button class="btn btn-secondary" @click.prevent="editCantCliVehi({ userLocal })"
                                    data-toggle="tooltip" data-placement="top" title="Cantidad de vehiculos">
                                    <i class="fas fa-cogs"></i>
                                </button>

                                <a href="#" class="btn btn-warning" @click.prevent="editUser({ userLocal })"
                                    data-toggle="tooltip" data-placement="top" title="Editar">
                                    <i class="far fa-edit"></i>
                                </a>

                                <a href="#" class="btn btn-danger"
                                    @click.prevent="modalDeleteUser({ id: userLocal.id })" data-toggle="tooltip"
                                    data-placement="top" title="Eliminar">
                                    <i class="fas fa-ban"></i>
                                </a>

                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="table-list-toolbar">
                    <div class="table-list-toolbar__rows">
                        <span>Filas</span>
                        <select class="custom-select custom-select-sm" v-model.number="pagination.per_page"
                            @change="getUsers({ page: 1, per_page: pagination.per_page })">
                            <option :value="10">10</option>
                            <option :value="20">20</option>
                            <option :value="50">50</option>
                        </select>
                    </div>
                    <nav>
                    <ul class="pagination">
                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link border-light bg-dark" href="#"
                                @click.prevent="changePageUser({ page: 1, per_page: pagination.per_page })">
                                <span>Primera</span>
                            </a>
                        </li>

                        <li class="page-item" v-if="pagination.current_page > 1">
                            <a class="page-link border-light bg-dark" href="#"
                                @click.prevent="changePageUser({ page: pagination.current_page - 1, per_page: pagination.per_page })">
                                <span>Atrás</span>
                            </a>
                        </li>

                        <li class="page-item" v-for="page in pagesNumber"
                            v-bind:class="[page == isActived ? 'active' : '']" :key="page">
                            <a class="page-link border-light bg-dark" href="#"
                                @click.prevent="changePageUser({ page, per_page: pagination.per_page })">
                                {{ page }}
                            </a>
                        </li>

                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link border-light bg-dark" href="#"
                                @click.prevent="changePageUser({ page: pagination.current_page + 1, per_page: pagination.per_page })">
                                <span>Siguiente</span>
                            </a>
                        </li>

                        <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                            <a class="page-link border-light bg-dark" href="#"
                                @click.prevent="changePageUser({ page: pagination.last_page, per_page: pagination.per_page })">
                                <span>Última</span>
                            </a>
                        </li>
                    </ul>
                    </nav>
                </div>

            </div>
        </div>

        <EditUser></EditUser>
        <DeleteUser></DeleteUser>
        <EditCantClientVehicle></EditCantClientVehicle>
        <UpdateUserRoles></UpdateUserRoles>

    </div>

</template>


<script>

import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapActions, mapGetters } from 'vuex'
import EditUser from './Edit'
import DeleteUser from './Delete'

import UpdateUserRoles from '../Roles/UpdateUserRoles'
import EditCantClientVehicle from './EditCantClientVehicle'

export default {
    components: { EditUser, DeleteUser, UpdateUserRoles, EditCantClientVehicle },
    computed: {
        ...mapState(['users', 'newUser', 'pagination', 'offset', 'errorsLaravel']),
        ...mapGetters(['isActived', 'pagesNumber'])
    },
    methods: {
        ...mapActions(['getUsers', 'createUser',
            'editUser', 'modalDeleteUser', 'deleteUser', 'editCantCliVehi', 'editUserRoles', 'changePageUser'])
    },
    created() {
        loadProgressBar()
        this.$store.dispatch('getUsers', { page: 1 })
    }
}

</script>

<style></style>
