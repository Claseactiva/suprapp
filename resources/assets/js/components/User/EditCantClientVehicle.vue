<template>

    <form action="POST" v-on:submit.prevent="updateCantCliVehi({ id: fillCantCliVehi.id })">
        <div id="editCantCliVehi" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Vehiculos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="cant_vehicle">Vehiculos</label>
                            <select class="form-control" name="cant_vehicle" v-model="fillCantCliVehi.cant_vehicle">
                                <option disabled value="0">Seleccione Cantidad</option>
                                <option v-for="opt in cantidadVehiculoOptions" :key="opt.id" :value="opt.value">
                                    1 - {{ opt.value }}
                                </option>
                            </select>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label>Administrar opciones</label>
                            <div class="d-flex flex-wrap" style="gap: 0.4rem;">
                                <span class="badge badge-secondary p-2" v-for="opt in cantidadVehiculoOptions" :key="'chip-' + opt.id">
                                    {{ opt.value }}
                                    <a href="#" class="text-danger ml-1" title="Eliminar"
                                        @click.prevent="deleteCantidadVehiculoOption(opt.id)">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            </div>

                            <div class="input-group mt-2">
                                <input type="number" min="1" class="form-control" placeholder="Nueva cantidad"
                                    v-model="newCantidadVehiculoOption">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-secondary" @click.prevent="createCantidadVehiculoOption">
                                        <i class="fas fa-plus"></i> Agregar
                                    </button>
                                </div>
                            </div>
                            <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                <p>{{ error.value }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-block">
                        <div class="row align-items-center">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus"></i>
                                </button>
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

export default {
    computed: {
        ...mapState(['totalvehiadmin', 'fillCantCliVehi', 'cantCliVehiAdmin', 'errorsLaravel', 'cantidadVehiculoOptions']),
        ...mapGetters([]),
        newCantidadVehiculoOption: {
            get() {
                return this.$store.state.newCantidadVehiculoOption
            },
            set(value) {
                this.$store.state.newCantidadVehiculoOption = value
            }
        }
    },
    methods: {
        ...mapActions(['getTotalVehiAdmin', 'updateCantCliVehi', 'createCantidadVehiculoOption', 'deleteCantidadVehiculoOption'])
    },


}
</script>
