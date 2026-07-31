<template>

    <form action="POST" v-on:submit.prevent="createDetailVehicle(newDetailVehicle.rol)">
        <div id="createDetail" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Agregar Detalle Vehículo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-5">
                                <label for="kilometraje">Kilometraje Actual</label>
                            </div>
                            <div class="col-7 text-right">
                                <label class="font-weight-bold">Ultimo Kilometraje: {{ kilometrajeActual
                                    }}</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <input v-validate="'required|min:2|max:190'"
                                :class="{ 'input': true, 'is-invalid': errors.has('kilometraje') }" type="number"
                                name="kilometraje" class="form-control" v-model="newDetailVehicle.km">
                            <p v-show="errors.has('kilometraje')" class="text-danger">{{ errors.first('kilometraje') }}
                            </p>
                        </div>

                        <div class="form-group">
                            <label for="nota">Nota</label>
                            <textarea v-validate="'required|min:4'"
                                :class="{ 'input': true, 'is-invalid': errors.has('nota') }" name="nota" rows="12"
                                class="form-control" v-model="newDetailVehicle.note"></textarea>
                            <p v-show="errors.has('nota')" class="text-danger">{{ errors.first('nota') }}</p>
                        </div>

                        <div class="form-group">
                            <label>Subir Imagen</label>
                            <input id="files" type="file" multiple accept="image/*" class="form-control"
                                @change="fileChange({ evt: $event })">
                        </div>

                        <div class="row" v-if="previews.length">
                            <div class="col-4 col-md-3 mb-3" v-for="(preview, index) in previews" :key="index">
                                <div class="detalle-preview">
                                    <img :src="preview.src" :alt="preview.name">
                                </div>
                            </div>
                        </div>

                        <!--<button class="btn btn-info">Subir Imágenes</button>-->

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" :disabled="!completeDetailVehicleCreate">
                            <i class="fas fa-plus-square"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';

export default {
    data() {
        return {
            attachment: [],
            form: new FormData,
            previews: []
        }
    },
    computed: {
        ...mapState(['newDetailVehicle', 'kilometrajeActual', 'images', 'errorsLaravel']),
        ...mapState({ selectedFiles: 'attachment' }),
        ...mapGetters(['completeDetailVehicleCreate'])
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
        ...mapActions(['createDetailVehicle', 'fileChange']),
    },
}
</script>

<style>
.detalle-preview {
    position: relative;
    padding-top: 100%;
    border-radius: 4px;
    overflow: hidden;
    background: #e9ecef;
}

.detalle-preview img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
</style>
