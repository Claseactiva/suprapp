<template>

    <div class="col-12">

        <h5 class="text-white">
            Nuevo Rol
            <a href="#" class="btn btn-success pull-right btn-sm" data-toggle="modal" data-target="#create"
                title="Agregar">
                <i class="fas fa-plus-circle"></i>
            </a>
        </h5>

        <div class="table-responsive">
            <table class="table table-responsive-new table-dark table-sm mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="roleLocal in roles" :key="roleLocal.id">
                        <td data-table-label="ID">{{ roleLocal.id }}</td>
                        <td data-table-label="nombre">{{ roleLocal.name }}</td>
                        <td data-table-label="descripcion">{{ roleLocal.description }}</td>
                        <td>
                            <a href="#" class="btn btn-warning btn-sm" @click.prevent="editRole({ roleLocal })"
                                data-toggle="tooltip" data-placement="top" title="Editar">
                                <i class="far fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm"
                                @click.prevent="deleteRole({ id: roleLocal.id })" data-toggle="tooltip"
                                data-placement="top" title="Eliminar">
                                <i class="fas fa-ban"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <nav>
            <ul class="pagination">
                <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageUser({ page: 1 })">
                        <span>Primera</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageUser({ page: pagination.current_page - 1 })">
                        <span>Atrás</span>
                    </a>
                </li>

                <li class="page-item" v-for="page in pagesNumber" v-bind:class="[page == isActived ? 'active' : '']"
                    :key="page">
                    <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageUser({ page })">
                        {{ page }}
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageUser({ page: pagination.current_page + 1 })">
                        <span>Siguiente</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageUser({ page: pagination.last_page })">
                        <span>Última</span>
                    </a>
                </li>
            </ul>
        </nav>

        <CreateRole></CreateRole>
        <EditRole></EditRole>

    </div>

</template>


<script>

import { loadProgressBar } from 'axios-progress-bar'
import CreateRole from './CreateRole'
import EditRole from './EditRole'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: { CreateRole, EditRole },
    computed: {
        ...mapState(['roles', 'pagination', 'offset']),
        ...mapGetters(['isActived', 'pagesNumber']),
    },
    methods: {
        ...mapActions(['getRoles', 'editRole', 'deleteRole', 'changePageUser'])
    },
    created() {
        loadProgressBar()
        this.$store.dispatch('getRoles', { page: 1 })
    },
}

</script>

<style></style>
