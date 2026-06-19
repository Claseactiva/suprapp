<template>

    <div id="MostrarCheckListVehicle" class="modal fade" style="overflow-y: scroll;" data-backdrop="static"
        data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">

                    <div id="accordion">
                        <div v-for="(mostrarchecklistvehicle, index) in mostrarchecklistvehicles[0]" :key="index"
                            class="card">
                            <div class="card-header" id="headingOne">
                                <h5 class="mb-0">
                                    <button class="btn btn-link"
                                        @click.prevent="mostrarCondiciones({ id_categoria: mostrarchecklistvehicle.id })"
                                        data-toggle="collapse"
                                        :data-target="'#collapseOne_' + mostrarchecklistvehicle.id" aria-expanded="true"
                                        :aria-controls="'collapseOne_' + mostrarchecklistvehicle.id">
                                        <b><em>{{ index + 1 }}.- {{ mostrarchecklistvehicle.categoria }}</em></b>
                                    </button>
                                </h5>
                            </div>

                            <div :id="'collapseOne_' + mostrarchecklistvehicle.id" class="collapse false"
                                aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <div class="col">
                                        <div v-show="mostrarCheckListVehicle">
                                            <div class="row justify-content-between border d-none d-lg-flex">
                                                <div class="col border-right p-2">
                                                    <strong>Intervenciones</strong>
                                                </div>
                                                <div class="col-2 border-right p-2">
                                                    <strong>Existe</strong>
                                                    <div class="row mt-2">
                                                        <div class="col border-right">
                                                            <strong>Si</strong>
                                                        </div>
                                                        <div class="col">
                                                            <strong>No</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col border-right p-2">
                                                    <strong>Estado</strong>
                                                    <div class="row mt-2">
                                                        <div class="col border-right">
                                                            <i class="fas fa-smile checklist-icon text-success"></i>
                                                        </div>
                                                        <div class="col border-right">
                                                            <i class="fas fa-meh checklist-icon text-warning"></i>
                                                        </div>
                                                        <div class="col">
                                                            <i class="fas fa-frown checklist-icon text-danger"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col p-2">
                                                    <strong>Observación</strong>
                                                </div>
                                            </div>

                                            <div
                                                class="row justify-content-between align-items-center border border-lg-top-0 border-md-top">
                                                <template
                                                    v-for="intervencion in mostrarchecklistvehicle.intervenciones">
                                                    <div class="col-lg col-md-12 border-lg-right border-md-right-0 p-3">
                                                        <strong class="d-md-block d-lg-none">INTERVENCION</strong>
                                                        <p class="m-0">{{ intervencion.intervencion }}</p>
                                                    </div>
                                                    <template v-for="condicion in intervencion.condiciones">
                                                        <div class="col-lg-2 col-md-12 border-right p-3">
                                                            <strong class="d-md-block d-lg-none">EXISTE</strong>
                                                            <div class="row mt-2 text-center">
                                                                <div class="col border-right"
                                                                    v-if="condicion.existe == 'si'">
                                                                    <p class="d-md-block d-lg-none font-weight-bold">SI
                                                                    </p>
                                                                    <i class="fas fa-check"></i>
                                                                </div>
                                                                <div class="col border-right" v-else>
                                                                    <p class="d-md-block d-lg-none font-weight-bold">SI
                                                                    </p>
                                                                </div>
                                                                <div class="col" v-if="condicion.existe == 'no'">
                                                                    <p class="d-md-block d-lg-none font-weight-bold">NO
                                                                    </p>
                                                                    <i class="fas fa-check"></i>
                                                                </div>
                                                                <div class="col" v-else>
                                                                    <p class="d-md-block d-lg-none font-weight-bold">NO
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg col-md-12 border-right p-3">
                                                            <strong class="d-md-block d-lg-none">ESTADO</strong>
                                                            <div class="row mt-2 text-center">
                                                                <div class="col border-right"
                                                                    v-if="condicion.estado == 'bueno'">
                                                                    <p class="d-md-block d-lg-none">
                                                                        <i
                                                                            class="fas fa-smile checklist-icon text-success"></i>
                                                                    </p>
                                                                    <i class="fas fa-check"></i>
                                                                </div>
                                                                <div class="col border-right" v-else>
                                                                    <p class="d-md-block d-lg-none">
                                                                        <i
                                                                            class="fas fa-smile checklist-icon text-success"></i>
                                                                    </p>
                                                                </div>
                                                                <div class="col border-right"
                                                                    v-if="condicion.estado == 'regular'">
                                                                    <p class="d-md-block d-lg-none">
                                                                        <i
                                                                            class="fas fa-smile checklist-icon text-warning"></i>
                                                                    </p>
                                                                    <i class="fas fa-check"></i>
                                                                </div>
                                                                <div class="col border-right" v-else>
                                                                    <p class="d-md-block d-lg-none">
                                                                        <i
                                                                            class="fas fa-smile checklist-icon text-warning"></i>
                                                                    </p>
                                                                </div>
                                                                <div class="col" v-if="condicion.estado == 'malo'">
                                                                    <p class="d-md-block d-lg-none">
                                                                        <i
                                                                            class="fas fa-smile checklist-icon text-danger"></i>
                                                                    </p>
                                                                    <i class="fas fa-check"></i>
                                                                </div>
                                                                <div class="col" v-else>
                                                                    <p class="d-md-block d-lg-none"><i
                                                                            class="fas fa-smile checklist-icon text-danger"></i>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col p-3">
                                                            <strong class="d-md-block d-lg-none">OBSERVACION</strong>
                                                            <template v-for="observacion in intervencion.observaciones">
                                                                <a v-if="observacion.id > 0"
                                                                    class="btn btn-block btn-warning"
                                                                    @click.prevent="modalMostrarObservacion({ observacion: observacion.observacion, imagenes: observacion.imagenes })">
                                                                    <i class="far fa-eye"></i>
                                                                </a>
                                                            </template>
                                                        </div>
                                                    </template>
                                                </template>
                                            </div>
                                        </div>
                                        <MostrarObservacion v-show="mostrarObservacion"></MostrarObservacion>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="button" @click.prevent="cerrarMostrarCheckListVehicle" class="btn btn-danger"
                        data-dismiss="modal" aria-label="Close">
                        Cerrar
                    </button>

                </div>
            </div>
        </div>
    </div>

</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex';
import { formatImage } from '../../utils/imageUtils';
import MostrarObservacion from './MostrarObservacion.vue';

export default {
    components: { MostrarObservacion },
    computed: {
        ...mapState(['mostrarchecklistvehicles', 'intervenciones', 'mostrarCheckListVehicle', 'mostrarObservacion']),
        ...mapGetters([]),
    },
    methods: {
        ...mapActions(['getMostrarCheckListVehicles', 'mostrarCondiciones', 'modalMostrarObservacion', 'cerrarMostrarCheckListVehicle']),
        formatImage
    }
}
</script>
