<template>
    <form action="POST" v-on:submit.prevent="guardarCheckList">
        <div id="checkListVehicle" class="modal fade" style="overflow-y: scroll;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="kilometraje">Kilometraje</label>
                            <input required class="form-control" type="number" name="kilometraje"
                                v-model="kilometraje" />
                        </div>

                        <div class="col">
                            <div class="row justify-content-between border d-none d-lg-flex text-center">
                                <div class="col border-right py-3 px-0">
                                    <strong>Categoria</strong>
                                </div>
                                <div class="col-2 border-right py-3 px-0">
                                    <strong>Existe</strong>
                                    <div class="row m-lg-0 mt-md-2 text-center">
                                        <div class="col p-0 border-right">
                                            <strong>Si</strong>
                                        </div>
                                        <div class="col p-0">
                                            <strong>No</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col border-right py-3 px-0">
                                    <strong>Estado</strong>
                                    <div class="row m-lg-0 mt-md-2 text-center">
                                        <div class="col p-0 border-right">
                                            <i class="fas fa-smile checklist-icon text-success"></i>
                                        </div>
                                        <div class="col p-0 border-right">
                                            <i class="fas fa-meh checklist-icon text-warning"></i>
                                        </div>
                                        <div class="col p-0">
                                            <i class="fas fa-frown checklist-icon text-danger"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col py-3 px-0">
                                    <strong>Observación</strong>
                                </div>
                            </div>


                            <template v-for="(formatchecklist, index) in formatchecklists">
                                <div
                                    class="row justify-content-between align-items-center border border-lg-top-0 border-md-top">
                                    <div class="col-12 bg-secondary border-lg-right border-md-right-0 p-3">
                                        <p class="d-md-block d-lg-none"><strong>CATEGORIA</strong></p>
                                        <strong><em>{{ index + 1 }}.- {{ formatchecklist.categoria }}</em></strong>
                                    </div>
                                </div>
                                <template v-for="intervencion in formatchecklist.intervenciones">
                                    <div class="row justify-content-between align-items-center border border-lg-top-0 border-md-top text-center">
                                        <div class="col-lg col-md-12 border-right py-3 px-0">
                                            <p class="d-md-block d-lg-none"><strong>INTERVENCION</strong></p>
                                            <p class="m-0">{{ intervencion.intervencion }}</p>
                                        </div>
                                        <div class="col-lg-2 col-md-12 border-right py-3 px-0">
                                            <strong class="d-md-block d-lg-none">EXISTE</strong>
                                            <div class="row m-lg-0 mt-md-2 text-center">
                                                <div class="col p-0 border-right">
                                                    <p class="d-md-block d-lg-none"><strong>SI</strong></p>
                                                    <input type="radio" required :name="'existe' + intervencion.id"
                                                        :value="intervencion.id" v-model="checkExisteSi">
                                                    <label></label>
                                                </div>
                                                <div class="col p-0">
                                                    <p class="d-md-block d-lg-none"><strong>NO</strong></p>
                                                    <input type="radio" required :name="'existe' + intervencion.id"
                                                        :value="intervencion.id" v-model="checkExisteNo">
                                                    <label></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg col-md-12 border-right py-3 px-0">
                                            <strong class="d-md-block d-lg-none">ESTADO</strong>
                                            <div class="row m-lg-0 mt-md-2 text-center">
                                                <div class="col p-0 border-right">
                                                    <p class="d-md-block d-lg-none"><i class="fas fa-smile checklist-icon text-success"></i></p>
                                                    <input type="radio" :name="'estado' + intervencion.id"
                                                        :value="intervencion.id" v-model="checkEstadoBueno">
                                                    <label></label>
                                                </div>
                                                <div class="col p-0 border-right">
                                                    <p class="d-md-block d-lg-none"><i class="fas fa-meh checklist-icon text-warning"></i></p>
                                                    <input type="radio" :name="'estado' + intervencion.id"
                                                        :value="intervencion.id" v-model="checkEstadoRegular">
                                                    <label></label>
                                                </div>
                                                <div class="col p-0">
                                                    <p class="d-md-block d-lg-none"><i class="fas fa-meh checklist-icon text-danger"></i></p>
                                                    <input type="radio" :name="'estado' + intervencion.id"
                                                        :value="intervencion.id" v-model="checkEstadoMalo">
                                                    <label></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col py-3 px-0">
                                            <p class="d-md-block d-lg-none"><strong>OBSERVACION</strong></p>
                                            <a class="btn btn-success text-white"
                                                @click.prevent="modalObservacionVehicleCheckList({ id_intervencion: intervencion.id, id_vehicle: formatchecklist.check_list_vehicle_id, intervenciones: formatchecklist.intervenciones })">
                                                <i class="fas fa-plus"></i> Observación
                                            </a>
                                        </div>
                                    </div>
                                </template>
                            </template>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
                            Cerrar
                        </button>

                        <button type="submit" class="btn btn-success">
                            Guardar
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
    components: {},
    computed: {
        ...mapState(['formatchecklists', 'formcheckList', 'km_old', 'id_vehicle', 'columnaObservacion', 'columnaExiste', 'columnaEstado']),
        ...mapGetters([]),


        checkExisteSi: {
            get() {
                return this.$store.state.checkExisteSi
            },
            set(value) {
                this.$store.commit('setCheckExisteSi', value)
            }
        },
        checkExisteNo: {
            get() {
                return this.$store.state.checkExisteNo
            },
            set(value) {
                this.$store.commit('setcheckExisteNo', value)
            }
        },
        checkEstadoBueno: {
            get() {
                return this.$store.state.checkEstadoBueno
            },
            set(value) {
                this.$store.commit('setCheckEstadoBueno', value)
            }
        },
        checkEstadoRegular: {
            get() {
                return this.$store.state.checkEstadoRegular
            },
            set(value) {
                this.$store.commit('setCheckEstadoRegular', value)
            }
        },
        checkEstadoMalo: {
            get() {
                return this.$store.state.checkEstadoMalo
            },
            set(value) {
                this.$store.commit('setCheckEstadoMalo', value)
            }
        },
        kilometraje: {
            get() {
                return this.$store.state.kilometraje
            },
            set(value) {
                this.$store.commit('setKilometraje', value)
            }
        }

    },
    methods: {
        ...mapActions(['guardarCheckList', 'modalObservacionVehicleCheckList'])
    },
}
</script>