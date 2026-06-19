<template>
    <div id="detail" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Detalle del Vehículo</h5>
                    <div class="table-responsive">
                        <table class="table table-responsive-new table-dark table-sm mt-3">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Kilometraje</th>
                                    <th>Nota</th>
                                    <th>Fecha</th>
                                    <th>Imagen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="detailLocal in details">
                                    <tr>
                                        <td data-table-label="id">{{ detailLocal.id }}</td>
                                        <td data-table-label="kilometraje">{{ detailLocal.km }}</td>
                                        <td data-table-label="nota">{{ detailLocal.note }}</td>
                                        <td data-table-label="fecha">{{ detailLocal.created_at | moment('DD/MM/YYYY H:mm') }}
                                        </td>
                                        <td data-table-label="imagen">
                                            <button class="btn btn-primary btn-sm" data-toggle="collapse"
                                                :data-target="'#detail_' + detailLocal.id">Ver</button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="12" class="p-0">
                                            <div class="accordian-body collapse" :id="'detail_' + detailLocal.id">
                                                <div class="d-flex flex-row">
                                                    <div v-for="imagen in detailLocal.images" :key="imagen.id"
                                                        class="p-2">
                                                        <img class="img-fluid" :src="formatImage(imagen.url)">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <h5>Orden de Trabajo</h5>
                    <div class="table-responsive">
                        <table class="table table-responsive-new table-dark table-sm mt-3">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Descripcion Del Trabajo</th>
                                    <th>Kilometraje</th>
                                    <th>Fecha</th>
                                    <th>Imagenes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="trabajo in trabajos">
                                    <tr>
                                        <td data-table-label="id">{{ trabajo.id }}</td>
                                        <td data-table-label="descripcion del trabajo">{{ trabajo.descripcion }}</td>
                                        <td data-table-label="kilometraje">{{ trabajo.km }}</td>
                                        <td data-table-label="fecha">{{ trabajo.created_at | moment('DD/MM/YYYY H:mm') }}</td>
                                        <td data-table-label="imagen">
                                            <button class="btn btn-primary btn-sm" data-toggle="collapse"
                                                :data-target="'#trabajo_' + trabajo.id">Ver</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="12" class="p-0">
                                            <div class="accordian-body collapse" :id="'trabajo_' + trabajo.id">
                                                <div class="d-flex flex-row">
                                                    <div v-for="imagen in trabajo.imagenes" :key="imagen.id"
                                                        class="p-2">
                                                        <img class="img-fluid" :src="formatImage(imagen.url)">
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>


                    <h5>Observaciones</h5>
                    <div class="table-responsive">
                        <table class="table table-responsive-new table-dark table-sm mt-3">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Observacion</th>
                                    <th>Imagenes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="trabajo in trabajos">
                                    <template v-for="observacion in trabajo.observaciones">
                                        <tr>
                                            <td data-table-label="id">{{ observacion.id }}</td>
                                            <td data-table-label="observacion">{{ observacion.observacion }}</td>
                                            <td data-table-label="imagen">
                                                <button class="btn btn-primary btn-sm" data-toggle="collapse"
                                                    :data-target="'#observacion_' + observacion.id">Ver</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="p-0">
                                                <div class="accordian-body collapse"
                                                    :id="'observacion_' + observacion.id">
                                                    <div class="d-flex flex-row">
                                                        <div v-for="imagen in observacion.imagenes" :key="imagen.id"
                                                            class="p-2">
                                                            <img class="img-fluid" :src="formatImage(imagen.imagen)">
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapActions, mapGetters } from 'vuex'
import { formatImage } from '../../utils/imageUtils';

export default {
    components: {},
    computed: {
        ...mapState(['details', 'trabajos']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions([]),
        formatImage
    },
    created() {
        loadProgressBar();
    }
}

</script>

<style></style>
