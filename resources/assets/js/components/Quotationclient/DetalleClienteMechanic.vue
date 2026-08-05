<template>

    <form>
        <div id="detalleClienteMechanic" class="modal fade">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Solicitud de Repuestos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive" v-for="quotationLocalForm in quotationforms"
                            :key="quotationLocalForm.id">
                            <table class="table table-bordered table-striped mt-3 text-white bg-dark">
                                <tbody>

                                    <tr>
                                        <th>Cliente</th>
                                        <td>{{ quotationLocalForm.name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Telefono</th>
                                        <td>{{ quotationLocalForm.phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pago</th>
                                        <td>{{ quotationLocalForm.payment }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered table-striped mt-3 text-white bg-dark">
                                <tbody>

                                    <tr>
                                        <th>Patente o Chasis</th>
                                        <td>{{ quotationLocalForm.patentchasis }}</td>
                                    </tr>
                                    <tr>
                                        <th>Marca</th>
                                        <td>{{ quotationLocalForm.brand }}</td>
                                    </tr>
                                    <tr>
                                        <th>Modelo</th>
                                        <td>{{ quotationLocalForm.model }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ano</th>
                                        <td>{{ quotationLocalForm.year }}</td>
                                    </tr>
                                    <tr>
                                        <th>Descripcion</th>
                                        <td>{{ quotationLocalForm.description }}</td>
                                    </tr>
                                    <tr v-if="quotationLocalForm.images && quotationLocalForm.images.length">
                                        <th>Fotos</th>
                                        <td>
                                            <div class="d-flex flex-wrap">
                                                <a v-for="image in quotationLocalForm.images" :key="image.id"
                                                    :href="formatImage(image.imagen)" target="_blank" class="mr-2 mb-2">
                                                    <img :src="formatImage(image.imagen)" style="height: 90px; object-fit: cover;" class="rounded">
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'
import { formatImage } from '../../utils/imageUtils'

export default {
    computed: {
        ...mapState(['quotationforms']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions([]),
        formatImage
    },
}
</script>