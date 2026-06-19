<template>
    <form action="POST" v-on:submit.prevent="updateProductsUtilidad">
        <div id="editProductsUtilidad" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Productos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-responsive-new table-dark table-sm mt-3">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Producto</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td></td>
                                        <td width="400px">
                                            <input type="text" class="form-control form-control-sm"
                                                v-model="searchProduct.name" @keyup="getProducts">
                                        </td>
                                        <td class="text-center">
                                            <input class="form-check-input" type="checkbox" v-model="checkedSelect1">
                                            <label class="form-check-label">Seleccionar todo</label>
                                        </td>
                                    </tr>

                                    <tr v-for="productLocal in products" :key="productLocal.id">
                                        <td width="10px">{{ productLocal.id }}</td>
                                        <td>{{ productLocal.name }}</td>
                                        <td class="text-center"> <input type="checkbox" v-model="checkedSelect2"
                                                :value="productLocal.id"> </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-dark"
                            style="padding-left: 450px; padding-top: 0.5rem;padding-right: 0.5rem;padding-bottom: 0.5rem;">
                            <SelectTiposPagos></SelectTiposPagos>
                        </div>
                        <nav>
                            <ul class="pagination mt-2">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link border-light bg-dark" href="#"
                                        @click.prevent="changePageProduct({ page: 1 })">
                                        <span>Primera</span>
                                    </a>
                                </li>

                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link border-light bg-dark" href="#"
                                        @click.prevent="changePageProduct({ page: pagination.current_page - 1 })">
                                        <span>Atrás</span>
                                    </a>
                                </li>

                                <li class="page-item" v-for="page in pagesNumber"
                                    v-bind:class="[page == isActived ? 'active' : '']" :key="page">
                                    <a class="page-link border-light bg-dark" href="#"
                                        @click.prevent="changePageProduct({ page })">
                                        {{ page }}
                                    </a>
                                </li>

                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link border-light bg-dark" href="#"
                                        @click.prevent="changePageProduct({ page: pagination.current_page + 1 })">
                                        <span>Siguiente</span>
                                    </a>
                                </li>

                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link border-light bg-dark" href="#"
                                        @click.prevent="changePageProduct({ page: pagination.last_page })">
                                        <span>Última</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
<script>

import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapActions, mapGetters } from 'vuex'
import SelectTiposPagos from './SelectTiposPagos'


export default {
    components: { SelectTiposPagos },

    computed: {
        ...mapState(['products', 'searchProduct', 'pagination', 'checkedSelect1', 'checkedSelect2', 'offset']),
        ...mapGetters(['isActived', 'pagesNumber']),
        checkedSelect1: {
            get() {
                return this.$store.state.checkedSelect1
            },
            set(value) {
                this.$store.commit('setCheckedSelect', value)
            }
        },
        checkedSelect2: {
            get() {
                return this.$store.state.checkedSelect2
            },
            set(value) {
                this.$store.commit('setCheckedSelect2', value)
            }
        },
    },
    methods: {
        ...mapActions(['getProducts', 'changePageProduct', 'updateProductsUtilidad'])
    },
    created() {
        loadProgressBar();
        this.$store.dispatch('getProducts', { page: 1 })

    }
}

</script>
<style>
.v-select .dropdown-toggle {
    background-color: white;
}
</style>