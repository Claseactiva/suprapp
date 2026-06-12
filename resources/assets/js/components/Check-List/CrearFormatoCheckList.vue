<template>

    <div id="CrearFormatoCheckList" class="modal fade" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div v-show="crearFormatoCheckList">
                        <h4>Crear Formato Check List</h4>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <form action="POST" v-on:submit.prevent="agregarCategoria">
                                    <div class="row align-items-end">
                                        <div class="col-8">

                                            <label for="categoria">Categoria</label>
                                            <input required type="text" name="categoria" class="form-control"
                                                v-model="checkListForm.categoria">

                                        </div>
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-block btn-success">
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
                                                <td data-table-label="#">{{ index + 1 }}</td>
                                                <td data-table-label="Categoria">{{ checklist.categoria }}</td>
                                                <td class="text-right">
                                                    <a href="#" class="btn btn-danger btn-sm"
                                                        @click.prevent="eliminarFormatCheckListCategoria(index + 1)"
                                                        data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                    Cancelar
                                </button>
                            </div>
                            <div class="col-6 text-right">
                                <button type="submit" class="btn btn-success" v-on:click="crearCheckList">
                                    Siguiente <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-show="crearIntervencionCheckList">
                        <h4>Crear Intervenciones</h4>
                        <hr>
                        <div v-for="(categoria, index) in checklists" :key="index" class="card mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-7 align-self-center">
                                        <h5 class="card-title mb-0">{{ categoria.categoria }}</h5>
                                    </div>
                                    <div class="col-5 text-right">
                                        <a href="#" class="btn btn-success"
                                            @click.prevent="modalIntervencion({ id: categoria.categoria })">
                                            Intervenciones
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-6">
                                <a href="#" class="btn btn-danger" @click.prevent="modalCerrarIntervencionCheckList">
                                    <i class="fas fa-arrow-left"></i> Atras
                                </a>
                            </div>
                            <div class="col-6 text-right">
                                <button type="button" class="btn btn-info" v-on:click="finalizarFormatoCheckList">
                                    Finalizar Check List
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-show="intervencionCheckList">
                        <h4>Agregar Intervenciones</h4>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <form action="POST" v-on:submit.prevent="agregarIntervencion">
                                    <div class="row align-items-end">
                                        <div class="col-9">

                                            <label for="intervencion">Intervención</label>
                                            <input required type="text" name="intervencion" class="form-control"
                                                v-model="intervencionForm.intervencion">

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
                                                <th>Intervencion</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <template v-for="checklist in checklists">
                                                <template v-if="checklist.categoria === intervencionForm.id_categoria">
                                                    <tr v-for="(intervencion, index) in checklist.intervenciones"
                                                        :key="index">
                                                        <td>{{ index + 1 }}</td>
                                                        <td>{{ intervencion.intervencion }}</td>
                                                        <td class="text-right">
                                                            <a href="#" class="btn btn-danger btn-sm"
                                                                @click.prevent="eliminarIntervencionFormatCheckList(index + 1)"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title="Eliminar">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <a href="#" class="btn btn-danger" @click.prevent="modalCerrarIntervencion">
                                    <i class="fas fa-arrow-left"></i> Atras
                                </a>
                            </div>
                        </div>
                    </div>
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
        ...mapState(['checklists', 'checkListForm', 'crearFormatoCheckList', 'crearIntervencionCheckList', 'intervencionCheckList', 'intervencionForm', 'errorsLaravel']),
        ...mapGetters([''])
    },
    methods: {
        ...mapActions(['agregarCategoria', 'crearCheckList', 'eliminarFormatCheckListCategoria', 'modalIntervencion', 'finalizarFormatoCheckList', 'agregarIntervencion', 'eliminarIntervencionFormatCheckList', 'modalCerrarIntervencion', 'modalCerrarIntervencionCheckList'])
    },
}
</script>
