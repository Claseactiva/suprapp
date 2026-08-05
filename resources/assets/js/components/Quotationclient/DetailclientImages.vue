<template>
    <div id="detailclientImagesModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h4>Fotos de: {{ activeDetailclientImages.product }}</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row mb-3">
                        <div class="col-6 col-md-4 mb-3" v-for="image in activeDetailclientImages.images" :key="image.id">
                            <div class="card">
                                <img :src="formatImage(image.imagen)" class="card-img-top" style="height: 100px; object-fit: cover;" alt="...">
                                <div class="card-body p-2 text-center">
                                    <a href="#" class="btn btn-danger btn-icon-sm"
                                        @click.prevent="deleteDetailclientImage(image.id)" title="Eliminar">
                                        <i class="far fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12" v-if="activeDetailclientImages.images.length === 0">
                            <p class="text-muted mb-0">Aún no hay fotos para este producto.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="detailclientImagesInput">Agregar fotos</label>
                        <input id="detailclientImagesInput" type="file" class="form-control" multiple
                            @change="setDetailclientImagesFiles($event)" accept=".png, .jpeg, .jpg">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" @click="uploadDetailclientImages">
                        <i class="fas fa-upload"></i> Subir
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { mapState, mapActions } from 'vuex'
import { formatImage } from '../../utils/imageUtils'

export default {
    computed: {
        ...mapState(['activeDetailclientImages'])
    },
    methods: {
        ...mapActions(['setDetailclientImagesFiles', 'uploadDetailclientImages', 'deleteDetailclientImage']),
        formatImage
    },
    mounted() {
        // Este modal se abre encima de #modalQuotationclient (modal anidado).
        // Bootstrap 4 borra 'modal-open'/backdrop al cerrar el hijo aunque el padre
        // siga abierto, dejando un backdrop invisible que bloquea el scroll/clicks.
        $('#detailclientImagesModal').on('hidden.bs.modal', function () {
            if ($('.modal.show').length > 0) {
                $('body').addClass('modal-open')
            }
            if ($('.modal-backdrop').length > 1) {
                $('.modal-backdrop').not(':last').remove()
            }
        })
    }
}
</script>
