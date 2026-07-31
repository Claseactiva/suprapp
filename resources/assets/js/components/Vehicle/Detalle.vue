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
                    <div class="vehicle-detalle-table-shell">
                        <table class="table table-responsive-new vehicle-detalle-table table-dark table-sm mt-3">
                            <thead>
                                <tr>
                                    <th class="vd-col-id">ID</th>
                                    <th class="vd-col-km">Kilometraje</th>
                                    <th class="vd-col-nota">Nota</th>
                                    <th class="vd-col-fecha">Fecha</th>
                                    <th class="vd-col-imagen">Imagen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="detailLocal in details">
                                    <tr>
                                        <td data-table-label="id" class="vd-col-id">{{ detailLocal.id }}</td>
                                        <td data-table-label="kilometraje" class="vd-col-km vd-cell-meta">{{ detailLocal.km }}</td>
                                        <td data-table-label="nota" class="vd-col-nota vd-cell-wrap" :title="detailLocal.note">{{ detailLocal.note }}</td>
                                        <td data-table-label="fecha" class="vd-col-fecha vd-cell-meta">{{ detailLocal.created_at | moment('DD/MM/YYYY H:mm') }}
                                        </td>
                                        <td data-table-label="imagen" class="vd-col-imagen">
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
                    <div class="vehicle-detalle-table-shell">
                        <table class="table table-responsive-new vehicle-detalle-table table-dark table-sm mt-3">
                            <thead>
                                <tr>
                                    <th class="vd-col-id">ID</th>
                                    <th class="vd-col-desc">Descripcion Del Trabajo</th>
                                    <th class="vd-col-km">Kilometraje</th>
                                    <th class="vd-col-fecha">Fecha</th>
                                    <th class="vd-col-imagen">Imagenes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="trabajo in trabajos">
                                    <tr>
                                        <td data-table-label="id" class="vd-col-id">{{ trabajo.id }}</td>
                                        <td data-table-label="descripcion del trabajo" class="vd-col-desc vd-cell-wrap" :title="trabajo.descripcion">{{ trabajo.descripcion }}</td>
                                        <td data-table-label="kilometraje" class="vd-col-km vd-cell-meta">{{ trabajo.km }}</td>
                                        <td data-table-label="fecha" class="vd-col-fecha vd-cell-meta">{{ trabajo.created_at | moment('DD/MM/YYYY H:mm') }}</td>
                                        <td data-table-label="imagen" class="vd-col-imagen">
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
                    <div class="vehicle-detalle-table-shell">
                        <table class="table table-responsive-new vehicle-detalle-table table-dark table-sm mt-3">
                            <thead>
                                <tr>
                                    <th class="vd-col-id">ID</th>
                                    <th class="vd-col-obs">Observacion</th>
                                    <th class="vd-col-imagen">Imagenes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="trabajo in trabajos">
                                    <template v-for="observacion in trabajo.observaciones">
                                        <tr>
                                            <td data-table-label="id" class="vd-col-id">{{ observacion.id }}</td>
                                            <td data-table-label="observacion" class="vd-col-obs vd-cell-wrap" :title="observacion.observacion">{{ observacion.observacion }}</td>
                                            <td data-table-label="imagen" class="vd-col-imagen">
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

<style>
.vehicle-detalle-table-shell {
    overflow-x: visible;
}

.vd-cell-wrap {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.vd-cell-meta {
    font-size: 0.78rem;
    text-align: center;
}

@media (min-width: 769px) {
    .vehicle-detalle-table {
        table-layout: fixed;
        width: 100%;
    }

    .vehicle-detalle-table th,
    .vehicle-detalle-table td {
        padding: 0.28rem 0.32rem !important;
        font-size: 0.74rem;
        white-space: nowrap !important;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .vehicle-detalle-table .vd-col-id {
        width: 3rem;
    }

    .vehicle-detalle-table .vd-col-km {
        width: 6rem;
    }

    .vehicle-detalle-table .vd-col-nota,
    .vehicle-detalle-table .vd-col-desc,
    .vehicle-detalle-table .vd-col-obs {
        width: 50%;
    }

    .vehicle-detalle-table .vd-col-fecha {
        width: 8rem;
    }

    .vehicle-detalle-table .vd-col-imagen {
        width: 5rem;
        text-align: center;
    }
}
</style>
