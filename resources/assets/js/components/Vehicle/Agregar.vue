<template>

    <form action="POST" v-on:submit.prevent="createVehicleUser">
        <div id="create" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Registrar Vehículo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">

                            <label for="patente">Patente</label>
                            <input required type="text" name="patente" class="form-control" v-model="newVehicle.patent">
                        </div>

                        <div class="form-group">
                            <label for="patente">Chasis</label>
                            <input required type="text" name="chasis" class="form-control" v-model="newVehicle.chasis">
                        </div>

                        <div class="form-group">
                            <label for="numero_interno">N° Interno</label>
                            <input type="text" name="numero_interno" class="form-control" v-model="newVehicle.numero_interno">
                        </div>

                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <v-select :options="optionsTiposVehiculoLabels" v-model="newVehicle.tipo"
                                :taggable="true" :push-tags="true" placeholder="Camión, Generador, Maquinaria, etc.">
                            </v-select>
                        </div>

                        <div class="form-group">
                            <label for="marca">Marca</label>
                            <BrandSelector />
                        </div>

                        <div class="form-group">
                            <label for="modelo">Modelo</label>
                            <ModelSelector />
                        </div>

                        <div class="form-group">
                            <label for="anio">Año</label>
                            <YearSelector />
                        </div>

                        <div class="form-group">
                            <label for="engine">Motor</label>
                            <EngineSelector />
                        </div>

                        <div class="form-group" v-if="rol !== 'client'">
                            <label for="motor_number">N° de Motor</label>
                            <input type="text" name="motor_number" class="form-control" v-model="newVehicle.motor_number">
                        </div>

                        <div class="form-group" v-if="rol !== 'client'">
                            <label for="motor_model">Modelo de Motor</label>
                            <input type="text" name="motor_model" class="form-control" v-model="newVehicle.motor_model">
                        </div>

                        <div class="form-group" v-if="rol !== 'client'">
                            <label for="arreglo_cpl">Arreglo / CPL</label>
                            <input type="text" name="arreglo_cpl" class="form-control" v-model="newVehicle.arreglo_cpl">
                        </div>

                        <div class="form-group">
                            <label for="color">Color</label>
                            <input required type="text" name="color" class="form-control" v-model="newVehicle.color">
                        </div>

                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="trackKm" v-model="newVehicle.trackKm">
                                <label class="form-check-label" for="trackKm">Kilometraje</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="trackHorometro" v-model="newVehicle.trackHorometro">
                                <label class="form-check-label" for="trackHorometro">Horómetro</label>
                            </div>

                            <div class="row">
                                <div class="form-group" :class="newVehicle.trackKm && newVehicle.trackHorometro ? 'col-6' : 'col-12'"
                                    v-if="newVehicle.trackKm">
                                    <label for="km">Kilometraje</label>
                                    <input type="number" name="km" class="form-control" v-model="newVehicle.km">
                                </div>
                                <div class="form-group" :class="newVehicle.trackKm && newVehicle.trackHorometro ? 'col-6' : 'col-12'"
                                    v-if="newVehicle.trackHorometro">
                                    <label for="horometro">Horómetro</label>
                                    <input type="number" name="horometro" class="form-control" v-model="newVehicle.horometro">
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" :disabled="!completeVehicleCreate">
                            <i class="fas fa-plus-square"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex';

import BrandSelector from '../Quotationuser/BrandSelector'
import ModelSelector from '../Quotationuser/ModelSelector'
import YearSelector from '../Quotationuser/YearSelector'
import EngineSelector from '../Quotationuser/EngineSelector'

export default {
    components: { BrandSelector, ModelSelector, YearSelector, EngineSelector },
    computed: {
        ...mapState(['newVehicle', 'errorsLaravel', 'rol', 'optionsTiposVehiculo']),
        ...mapGetters(['completeVehicleCreate']),
        optionsTiposVehiculoLabels() {
            return this.optionsTiposVehiculo.map(tipo => tipo.label)
        }
    },
    methods: {
        ...mapActions(['createVehicleUser', 'allTiposVehiculos'])
    },
    created() {
        this.allTiposVehiculos()
    }
}
</script>
