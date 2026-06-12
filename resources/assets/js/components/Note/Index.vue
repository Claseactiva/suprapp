<template>

    <div class="col-lg-12">

        <h5 class="text-white">
            Nueva Nota
            <a href="#" class="btn btn-success pull-right btn-sm" data-toggle="modal" data-target="#create"
                title="Agregar">
                <i class="fas fa-plus-circle"></i>
            </a>
        </h5>

        <table class="table table-responsive-new table-dark table-sm mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Precio</th>
                    <th>Detalle</th>
                    <th>Fecha</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="noteLocal in notes" :key="noteLocal.id">
                    <td data-table-label="ID">{{ noteLocal.id }}</td>
                    <td data-table-label="PRECIO">{{ noteLocal.price }}</td>
                    <td data-table-label="DETALLE">
                        {{ noteLocal.detail }}
                    </td>
                    <td data-table-label="FECHA">{{ noteLocal.created_at | moment('DD/MM/YYYY') }}</td>

                    <td>
                        <a href="#" class="btn btn-warning btn-sm" @click.prevent="editNote({ noteLocal })"
                            data-toggle="tooltip" data-placement="top" title="Editar">
                            <i class="far fa-edit"></i>
                        </a>

                        <a href="#" class="btn btn-danger btn-sm" @click.prevent="deleteNote({ id: noteLocal.id })"
                            data-toggle="tooltip" data-placement="top" title="Eliminar">
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
                    @change="getNotes({ page: 1, per_page: pagination.per_page })">
                    <option :value="10">10</option>
                    <option :value="20">20</option>
                    <option :value="50">50</option>
                </select>
            </div>
            <nav>
            <ul class="pagination">
                <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageNote({ page: 1, per_page: pagination.per_page })">
                        <span>Primera</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageNote({ page: pagination.current_page - 1, per_page: pagination.per_page })">
                        <span>Atrás</span>
                    </a>
                </li>

                <li class="page-item" v-for="page in pagesNumber" v-bind:class="[page == isActived ? 'active' : '']"
                    :key="page">
                    <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageNote({ page, per_page: pagination.per_page })">
                        {{ page }}
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageNote({ page: pagination.current_page + 1, per_page: pagination.per_page })">
                        <span>Siguiente</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageNote({ page: pagination.last_page, per_page: pagination.per_page })">
                        <span>Última</span>
                    </a>
                </li>
            </ul>
            </nav>
        </div>

        <Agregar></Agregar>
        <Editar></Editar>

    </div>

</template>


<script>

import { loadProgressBar } from 'axios-progress-bar'
import Agregar from './Agregar'
import Editar from './Editar'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: { Agregar, Editar },
    computed: {
        ...mapState(['notes', 'pagination', 'offset']),
        ...mapGetters(['isActived', 'pagesNumber'])
    },
    methods: {
        ...mapActions(['getNotes', 'editNote', 'deleteNote', 'changePageNote'])
    },
    created() {
        loadProgressBar();
        this.$store.dispatch('getNotes', { page: 1 })
    }
}

</script>

<style></style>
