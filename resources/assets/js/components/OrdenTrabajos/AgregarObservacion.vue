<template>
    <div id="modalObservacion" class="modal fade">
        <div class="modal-dialog modal-observacion">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Observaciones</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <form action="POST" v-on:submit.prevent="AgregarObservacion">
                        <div class="form-group">
                            <label for="observacion">Observacion y/o comentario</label>
                            <textarea v-validate="'min:4'"
                                :class="{ 'input': true, 'is-invalid': errors.has('observacion') }" name="observacion"
                                rows="5" class="form-control" v-model="newOrdenTrabajo.observacion"></textarea>
                            <p v-show="errors.has('observacion')" class="text-danger">{{
                                errors.first('observacion') }}</p>
                        </div>

                        <div class="form-group">
                            <input id="filesObservacion" type="file" class="form-control" multiple
                                @change="subirFotosObservacion({ evt: $event })">
                        </div>

                        <button type="submit" class="btn btn-success" :disabled="!completeObservacion">
                            <i class="fas fa-plus-square"></i> Agregar
                        </button>
                    </form>

                    <div class="my-3">
                        <div class="card mb-3" v-for="observacion in observaciones" :key="observacion.id">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 mt-3" v-for="imagen in observacion.imagenes"
                                        :key="imagen.id">
                                        <div class="imagen">
                                            <div class="image-overlay"></div>
                                            <img class="img-fluid" :src="formatImage(imagen.imagen)">
                                            <button type="button" class="btn btn-danger delete-button"
                                                @click.prevent="borrarImagenObservacion(imagen)">
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
                                            :data="{ id: observacion.id, componente: 'borrarOservacion' }">
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
import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapGetters, mapActions } from 'vuex';
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
        ...mapState(['observaciones', 'newOrdenTrabajo', 'errorsLaravel']),
        ...mapGetters(['completeObservacion'])
    },

    methods: {
        ...mapActions(['getObservaciones', 'borrarImagenObservacion', 'AgregarObservacion', 'subirFotosObservacion']),
        formatImage
    },


}
</script>
<style>
.modal-observacion {
    max-width: 1000px;
}
</style>