<template>

    <form action="POST" v-on:submit.prevent="AgregarFotosOrdenTrabajo">
        <div id="modalFotosOrdenTrabajo" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Agregar Fotos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-12">

                                <div class="row">
                                    <div class="col-10">
                                        <div class="form-group">
                                            <input id="files" type="file" multiple class="form-control"
                                                @change="subirFotosOrdenTrabajo({ evt: $event })">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-plus-square"></i> Guardar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">


                                <div class="row row-cols-1 row-cols-md-2 g-4">
                                    <div class="col-6 pb-3" v-for="trabajo in trabajos" :key="trabajo.id">
                                        <div class="card">
                                            <div class="imagen">
                                                <div class="image-overlay"></div>
                                                <img :src="formatImage(trabajo.url)" class="card-img-top" alt="...">
                                                <button type="button" class="btn btn-danger delete-button"
                                                    @click.prevent="borrarImagenOrdenTrabajo({id : trabajo.id})">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>

</template>

<script>
import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapGetters, mapActions } from 'vuex';
import { formatImage } from '../../utils/imageUtils';
export default {
    data() {
        return {
            attachment: [],
            form: new FormData
        }
    },
    computed: {
        ...mapState(['trabajos', 'newOrdenTrabajo', 'images', 'errorsLaravel']),
        // ...mapGetters(['completeDetailVehicleCreate'])
    },

    methods: {
        ...mapActions(['AgregarFotosOrdenTrabajo', 'subirFotosOrdenTrabajo', 'borrarImagenOrdenTrabajo']),
        formatImage
    }
}
</script>
