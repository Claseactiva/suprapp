<template>
    <div id="agregarObservacionCheckList" class="modal fade" style="overflow-y: scroll">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Agregar Observación</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        @click.prevent="modalCerrarObservacionVehicleCheckList()">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="POST" v-on:submit.prevent="agregarObservacionCheckList">
                        <div class="form-group">
                            <label for="observacion">Observación</label>
                            <textarea v-validate="'min:4'"
                                :class="{ 'input': true, 'is-invalid': errors.has('observacion') }" name="observacion"
                                rows="5" class="form-control" v-model="columnaObservacion.observacion"></textarea>
                            <p v-show="errors.has('observacion')" class="text-danger">{{ errors.first('observacion')
                                }}
                            </p>
                        </div>

                        <div class="form-group">
                            <div class="input-group">

                                <input id="filesObservacion" type="file" class="form-control" multiple
                                    @change="subirFotosObservacionCheckList({ evt: $event })">
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-block btn-success"
                                :disabled="!completeObservacionCheckList">
                                <i class="fas fa-plus-circle"></i> Agregar
                            </button>
                        </div>
                    </form>

                    <div v-for="intervencion in intervenciones" :key="intervencion.id">
                        <div class="card mb-3" v-for="observacion in intervencion.observaciones" :key="observacion.id">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 mt-3" v-for="imagen in observacion.imagenes" :key="imagen.id">
                                        <div class="imagen">
                                            <div class="image-overlay"></div>
                                            <img class="img-fluid" :src="formatImage(imagen.imagen)">
                                            <button type="button" class="btn btn-danger delete-button"
                                                @click.prevent="borrarImagenChecklist(imagen)">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-3" v-if="observacion.observacion">
                                        <h5>Observación</h5>
                                        <p>{{ observacion.observacion }}</p>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <Eliminar
                                            :data="{ id: observacion.id, componente: 'borrarOservacionChecklist' }">
                                        </Eliminar>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

</template>

<script>

import { mapState, mapGetters, mapActions, mapMutations } from 'vuex';
import { formatImage } from '../../utils/imageUtils';
import Eliminar from '../Eliminar/Eliminar';
export default {
    components: { Eliminar },
    data() {
        return {
            attachment: [],
            form: new FormData
        }
    },
    computed: {
        ...mapState(['columnaObservacion', 'errorsLaravel', 'intervenciones']),
        ...mapGetters(['completeObservacionCheckList']),
    },
    methods: {
        ...mapActions(['agregarObservacionCheckList', 'subirFotosObservacionCheckList', 'modalCerrarObservacionVehicleCheckList', 'borrarImagenChecklist']),
        formatImage
    },
}
</script>