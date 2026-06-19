<template>
    <div id="modalAlertaInformacion" class="modal fade" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">¿Quieres cerrar la Orden de trabajo?</h5>
                </div>
                <div class="modal-body">
                    <h6 class="mb-3" v-if="observaciones.length == 0 || trabajos.length == 0">Falta informacion:</h6>
                    <h6 class="mb-3" v-else>Tiene la informacion necesaria para cerrar la OT</h6>
                    <div class="alert alert-success" role="alert" v-if="observaciones.length > 0">
                        <div class="row">
                            <div class="col-1">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <div class="col-6">
                                <p class="font-weight-bold mb-0">Observacion e imagenes</p>
                            </div>
                        </div>
                    </div>
                    <div v-else class="alert alert-danger" role="alert">
                        <div class="row">
                            <div class="col-1">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="col-6">
                                <p class="font-weight-bold mb-0">Observacion e imagenes</p>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-success" role="alert" v-if="trabajos.length > 0">
                        <div class="row">
                            <div class="col-1">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <div class="col-6">
                                <p class="font-weight-bold mb-0">Fotos Trabajos</p>
                            </div>
                        </div>
                    </div>
                    <div v-else class="alert alert-danger" role="alert">
                        <div class="row">
                            <div class="col-1">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="col-6">
                                <p class="font-weight-bold mb-0">Fotos Trabajos</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" @click.prevent="cerrarRealizado({ id: id_trabajo })">Si</button>
                    <button class="btn btn-secondary" @click.prevent="deleteRealizado({ id: id_trabajo })" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapGetters, mapActions } from 'vuex';
export default {
    computed: {
        ...mapState(['observaciones', 'trabajos', 'id_trabajo']),
    },

    methods: {
        ...mapActions(['cerrarRealizado', 'deleteRealizado']),
    }
}
</script>
