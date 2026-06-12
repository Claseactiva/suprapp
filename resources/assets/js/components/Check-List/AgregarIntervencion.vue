<template>

    <div id="AgregarIntervencion" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Agregar Intervenciones</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-12">
                            <form action="POST" v-on:submit.prevent="agregarIntervencionEditar">
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

                                        <tr v-for="(intervencion, index) in intervenciones" :key="index">
                                            <td data-table-label="#">{{ index + 1 }}</td>
                                            <td data-table-label="Intervencion">{{ intervencion.intervencion }}</td>
                                            <td class="text-right">
                                                <a href="#" class="btn btn-danger btn-sm"
                                                    @click.prevent="eliminarIntervencion" data-toggle="tooltip"
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

                    <button type="button" class="btn btn-danger" v-on:click="cerrarIntervencion">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-success"
                        v-on:click="crearIntervencion({ id: intervencionForm.id_categoria })">
                        Guardar
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
        ...mapState(['intervenciones', 'intervencionForm']),
        ...mapGetters([''])
    },
    methods: {
        ...mapActions(['agregarIntervencionEditar', 'cerrarIntervencion', 'crearIntervencion', 'eliminarIntervencion'])
    },
}
</script>
