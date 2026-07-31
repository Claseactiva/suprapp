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
                            <input id="filesObservacion" type="file" class="form-control" multiple accept="image/*"
                                @change="subirFotosObservacion({ evt: $event })">
                        </div>

                        <div class="row" v-if="previews.length">
                            <div class="col-4 col-md-3 mb-3" v-for="(preview, index) in previews" :key="index">
                                <div class="observacion-preview">
                                    <img :src="preview.src" :alt="preview.name">
                                    <button type="button" class="btn btn-danger btn-sm preview-remove"
                                        title="Quitar" @click.prevent="removePreview(index)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
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
                                        <h5>
                                            Observación
                                            <a href="#" class="btn btn-warning btn-sm" title="Editar"
                                                v-if="editingId !== observacion.id"
                                                @click.prevent="startEdit(observacion)">
                                                <i class="far fa-edit"></i>
                                            </a>
                                        </h5>

                                        <div v-if="editingId === observacion.id">
                                            <textarea class="form-control" rows="4" v-model="editText"></textarea>
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-success btn-sm"
                                                    :disabled="editText.trim().length < 4"
                                                    @click.prevent="saveEdit(observacion)">
                                                    <i class="fas fa-check"></i> Guardar
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    @click.prevent="cancelEdit">
                                                    Cancelar
                                                </button>
                                            </div>
                                        </div>
                                        <p v-else>{{ observacion.observacion }}</p>
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
            form: new FormData,
            previews: [],
            editingId: null,
            editText: ''
        }
    },
    computed: {
        ...mapState(['observaciones', 'newOrdenTrabajo', 'errorsLaravel']),
        ...mapState({ selectedFiles: 'attachment' }),
        ...mapGetters(['completeObservacion'])
    },
    watch: {
        selectedFiles(files) {
            this.previews.forEach(preview => URL.revokeObjectURL(preview.src))
            this.previews = (files || []).map(file => ({
                src: URL.createObjectURL(file),
                name: file.name
            }))
        }
    },
    beforeDestroy() {
        this.previews.forEach(preview => URL.revokeObjectURL(preview.src))
    },

    methods: {
        ...mapActions(['getObservaciones', 'borrarImagenObservacion', 'AgregarObservacion', 'subirFotosObservacion', 'actualizarObservacion']),
        formatImage,
        removePreview(index) {
            this.$store.commit('removeAttachment', index)
        },
        startEdit(observacion) {
            this.editingId = observacion.id
            this.editText = observacion.observacion
        },
        cancelEdit() {
            this.editingId = null
            this.editText = ''
        },
        saveEdit(observacion) {
            this.actualizarObservacion({ id: observacion.id, observacion: this.editText })
            this.editingId = null
            this.editText = ''
        }
    },


}
</script>
<style>
.modal-observacion {
    max-width: 1000px;
}

.observacion-preview {
    position: relative;
    padding-top: 100%;
    border-radius: 4px;
    overflow: hidden;
    background: #e9ecef;
}

.observacion-preview img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-remove {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 20px;
    height: 20px;
    padding: 0;
    line-height: 1;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    border: 2px solid #fff;
    z-index: 2;
}
</style>