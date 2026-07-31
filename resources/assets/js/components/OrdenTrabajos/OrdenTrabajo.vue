<template>
    <div>

        <div id="createOrdenTrabajo" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Agregar Orden de Trabajo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-5">
                                            <label for="kilometraje">Kilometraje Actual</label>
                                        </div>
                                        <div class="col-7 text-right">
                                            <label class="font-weight-bold">Ultimo Kilometraje: {{ kilometrajeActual
                                                }}</label>
                                        </div>
                                    </div>

                                    <input v-validate="'required'"
                                        :class="{ 'input': true, 'is-invalid': errors.has('kilometraje') }"
                                        type="number" name="kilometraje" class="form-control"
                                        v-model="newOrdenTrabajo.km">
                                    <p v-show="errors.has('kilometraje')" class="text-danger">{{
                                        errors.first('kilometraje') }}</p>
                                </div>

                                <div class="form-group">
                                    <label for="descripcion">Descripción de trabajo</label>
                                    <textarea v-validate="'min:4'"
                                        :class="{ 'input': true, 'is-invalid': errors.has('descripcion') }"
                                        name="descripcion" rows="12" class="form-control"
                                        v-model="newOrdenTrabajo.descripcion"></textarea>
                                    <p v-show="errors.has('descripcion')" class="text-danger">{{
                                        errors.first('descripcion') }}</p>
                                </div>

                                <button type="button" class="btn btn-secondary" v-show="verBotonActualizar"
                                    @click.prevent="volverAgregar()">
                                    <i class="fas fa-arrow-left"></i> Volver
                                </button>

                                <button type="button" class="btn btn-success" :disabled="!completeGuardarOrdenTrabajo"
                                    v-show="verBotonActualizar === false"
                                    @click.prevent="guardarOrdenTrabajo({ id: newOrdenTrabajo.vehicle_id, km_old: newOrdenTrabajo.km_old })">
                                    <i class="fas fa-plus-square"></i> Agregar
                                </button>

                                <button type="button" class="btn btn-warning"
                                    :disabled="!completeActualizarOrdenTrabajo" v-show="verBotonActualizar"
                                    @click.prevent="actualizarOrdenTrabajo({ id: newOrdenTrabajo.vehicle_id, km_old: newOrdenTrabajo.km_old })">
                                    <i class="fas fa-edit"></i> Editar
                                </button>

                                <div class="alert alert-warning mt-3" role="alert" v-show="alert">
                                    <i class="fas fa-exclamation-triangle"></i> {{ alertkm }}
                                </div>

                            </div>
                            <div class="col-12">
                                <div class="orden-modal-table-shell mt-3">
                                    <table class="table table-responsive-new table-dark table-sm orden-modal-table mb-0">
                                        <thead>
                                            <tr>
                                                <th class="modal-col-id">#</th>
                                                <th class="modal-col-trabajo">Trabajo</th>
                                                <th class="modal-col-km">Kilometraje</th>
                                                <th class="modal-col-fecha">Fecha</th>
                                                <th class="modal-col-actions"></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr v-for="trabajo in trabajos" :key="trabajo.id">
                                                <td data-table-label="id" class="modal-col-id">{{ trabajo.id }}</td>
                                                <td data-table-label="trabajo" class="modal-col-trabajo orden-cell-wrap" :title="trabajo.descripcion">{{ trabajo.descripcion }}</td>
                                                <td data-table-label="kilometraje" class="modal-col-km orden-cell-meta">{{ trabajo.km }}</td>
                                                <td data-table-label="fecha" class="modal-col-fecha orden-cell-meta">{{ trabajo.created_at | moment('DD/MM/YYYY h:mm a') }}</td>
                                                <td class="modal-col-actions orden-action-cell">
                                                    <a href="#" class="btn btn-warning btn-sm"
                                                        @click.prevent="editarTrabajo(trabajo)" data-toggle="tooltip"
                                                        data-placement="top" title="Eliminar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <a href="#" class="btn btn-danger btn-sm"
                                                        @click.prevent="removeTrabajo({ id: trabajo.id, vehicle_id: newOrdenTrabajo.vehicle_id })"
                                                        data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-danger">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapGetters, mapActions } from 'vuex';

export default {
    data() {
        return {
            alert: false,
            alertkm: '',
        }
    },
    watch: {
        'newOrdenTrabajo.km': function () {
            this.alert = false

            if (this.newOrdenTrabajo.km > 0 && this.newOrdenTrabajo.km < this.$store.state.kilometrajeActual) {
                this.alert = true
                this.alertkm = 'El kilometraje ingresado es menor que el último registrado, deseas agregar?'
            }

            if (this.newOrdenTrabajo.km > 0 && this.newOrdenTrabajo.km > this.$store.state.newOrdenTrabajo.tendenciaKm) {
                this.alert = true
                this.alertkm = 'Estas seguro del kilometraje ingresado?'
            }

        }
    },
    computed: {
        ...mapState(['trabajos', 'newOrdenTrabajo', 'errorsLaravel', 'kilometrajeActual', 'verBotonActualizar']),
        ...mapGetters(['completeGuardarOrdenTrabajo', 'completeActualizarOrdenTrabajo'])
    },

    methods: {
        ...mapActions(['getTrabajos', 'removeTrabajo', 'guardarOrdenTrabajo', 'editarTrabajo', 'actualizarOrdenTrabajo', 'volverAgregar']),
    },
}
</script>

<style>
.orden-modal-table-shell {
    overflow-x: visible;
}

@media (min-width: 769px) {
    .orden-modal-table {
        table-layout: fixed;
        width: 100%;
    }

    .orden-modal-table th,
    .orden-modal-table td {
        padding: 0.28rem 0.32rem !important;
        font-size: 0.74rem;
        white-space: nowrap !important;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .orden-modal-table .modal-col-id {
        width: 3rem;
    }

    .orden-modal-table .modal-col-trabajo {
        width: 45%;
    }

    .orden-modal-table .modal-col-km {
        width: 6rem;
    }

    .orden-modal-table .modal-col-fecha {
        width: 8rem;
    }

    .orden-modal-table .modal-col-actions {
        width: 6rem;
        overflow: visible !important;
        text-overflow: clip !important;
    }
}
</style>
