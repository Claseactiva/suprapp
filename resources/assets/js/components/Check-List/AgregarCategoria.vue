<template>

    <div id="AgregarCategoria" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Crear Categoria</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-12">
                            <form action="POST" v-on:submit.prevent="agregarCategoria">
                                <div class="row align-items-end">
                                    <div class="col-9">

                                        <label for="categoria">Categoria</label>
                                        <input required type="text" name="categoria" class="form-control"
                                            v-model="checkListForm.categoria">

                                    </div>
                                    <div class="col-3">
                                        <button type="submit" class="btn btn-success btn-block">
                                            Agregar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mt-3 table-sm text-white bg-dark">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Categoria</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr v-for="(checklist, index) in checklists" :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ checklist.categoria }}</td>
                                            <td class="text-right">
                                                <a href="#" class="btn btn-danger btn-sm"
                                                    @click.prevent="eliminarCategoria" data-toggle="tooltip"
                                                    data-placement="top" title="Eliminar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>



                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-danger" v-on:click="cerrarCategoria">
                        Cancelar
                    </button>


                    <button type="submit" class="btn btn-success" v-on:click="crearCategoria">
                        Siguiente <i class="fas fa-arrow-right"></i>
                    </button>

                </div>
            </div>
        </div>
    </div>

</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex';


export default {
    components: {},
    computed: {
        ...mapState(['checklists', 'checkListForm', 'errorsLaravel']),
        ...mapGetters([''])
    },
    methods: {
        ...mapActions(['agregarCategoria', 'crearCategoria', 'eliminarCategoria', 'cerrarCategoria'])
    },
}
</script>
