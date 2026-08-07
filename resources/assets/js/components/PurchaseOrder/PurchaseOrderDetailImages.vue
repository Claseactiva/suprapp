<template>
    <div id="purchaseOrderDetailImagesModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h4>Fotos de: {{ activePurchaseOrderDetailImages.product }}</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row mb-3">
                        <div class="col-6 col-md-4 mb-3" v-for="image in activePurchaseOrderDetailImages.images" :key="image.id">
                            <div class="card">
                                <img :src="formatImage(image.imagen)" class="card-img-top" style="height: 100px; object-fit: cover;" alt="...">
                                <div class="card-body p-2 text-center">
                                    <a href="#" class="btn btn-danger btn-icon-sm"
                                        @click.prevent="deletePurchaseOrderDetailImage(image.id)" title="Eliminar">
                                        <i class="far fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12" v-if="activePurchaseOrderDetailImages.images.length === 0">
                            <p class="text-muted mb-0">Aún no hay fotos para este producto.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="purchaseOrderDetailImagesInput">Agregar fotos</label>
                        <input id="purchaseOrderDetailImagesInput" type="file" class="form-control" multiple
                            @change="setPurchaseOrderDetailImagesFiles($event)" accept=".png, .jpeg, .jpg">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" @click="uploadPurchaseOrderDetailImages">
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
        ...mapState(['activePurchaseOrderDetailImages'])
    },
    methods: {
        ...mapActions(['setPurchaseOrderDetailImagesFiles', 'uploadPurchaseOrderDetailImages', 'deletePurchaseOrderDetailImage']),
        formatImage
    },
    mounted() {
        // Este modal se abre encima de #modalPurchaseOrder (modal anidado), mismo cuidado
        // que en DetailclientImages.vue para no dejar un backdrop invisible al cerrar.
        $('#purchaseOrderDetailImagesModal').on('hidden.bs.modal', function () {
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
