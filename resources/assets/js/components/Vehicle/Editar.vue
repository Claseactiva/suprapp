<template>

    <form action="POST" v-on:submit.prevent="updateVehicleUser({ id: fillVehicle.id })">
        <div id="edit" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Vehículo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <label for="patente">Patente</label>
                        <input v-validate="'required|min:4|max:190'"
                            :class="{ 'input': true, 'is-invalid': errors.has('patente') }" type="text" name="patente"
                            class="form-control" v-model="fillVehicle.patent">
                        <p v-show="errors.has('patente')" class="text-danger">{{ errors.first('patente') }}</p>


                        <label for="chasis">Chasis</label>
                        <input v-validate="'required|min:4|max:190'"
                            :class="{ 'input': true, 'is-invalid': errors.has('chasis') }" type="text" name="chasis"
                            class="form-control" v-model="fillVehicle.chasis">
                        <p v-show="errors.has('chasis')" class="text-danger">{{ errors.first('chasis') }}</p>

                        <label for="numero_interno">N° Interno</label>
                        <input type="text" name="numero_interno" class="form-control" v-model="fillVehicle.numero_interno">

                        <label for="tipo">Tipo</label>
                        <v-select :options="optionsTiposVehiculoLabels" v-model="fillVehicle.tipo"
                            :taggable="true" :push-tags="true" placeholder="Camión, Generador, Maquinaria, etc.">
                        </v-select>

                        <label for="marca">Marca</label>
                        <BrandSelector />
                        <!-- <SelectBrand></SelectBrand> -->

                        <label for="modelo">Modelo</label>
                        <ModelSelector />
                        <!-- <SelectModel></SelectModel> -->


                        <label for="anio">Año</label>
                        <YearSelector />

                        <label for="engine">Motor</label>
                        <EngineSelector />

                        <label for="color">Color</label>
                        <input v-validate="'required|min:4|max:190'"
                            :class="{ 'input': true, 'is-invalid': errors.has('color') }" type="text" name="color"
                            class="form-control" v-model="fillVehicle.color">
                        <p v-show="errors.has('color')" class="text-danger">{{ errors.first('color') }}</p>


                        <div class="form-check form-check-inline mt-2">
                            <input type="checkbox" class="form-check-input" id="editTrackKm" v-model="fillVehicle.trackKm">
                            <label class="form-check-label" for="editTrackKm">Kilometraje</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="editTrackHorometro" v-model="fillVehicle.trackHorometro">
                            <label class="form-check-label" for="editTrackHorometro">Horómetro</label>
                        </div>

                        <div class="row">
                            <div class="form-group" :class="fillVehicle.trackKm && fillVehicle.trackHorometro ? 'col-6' : 'col-12'"
                                v-if="fillVehicle.trackKm">
                                <label for="km">Kilometraje</label>
                                <input type="number" name="km" class="form-control" v-model="fillVehicle.km">
                            </div>
                            <div class="form-group" :class="fillVehicle.trackKm && fillVehicle.trackHorometro ? 'col-6' : 'col-12'"
                                v-if="fillVehicle.trackHorometro">
                                <label for="horometro">Horómetro</label>
                                <input type="number" name="horometro" class="form-control" v-model="fillVehicle.horometro">
                            </div>
                        </div>

                        <label for="motor_number">N° de Motor</label>
                        <input type="text" name="motor_number" class="form-control" v-model="fillVehicle.motor_number">

                        <label for="motor_model">Modelo de Motor</label>
                        <input type="text" name="motor_model" class="form-control" v-model="fillVehicle.motor_model">

                        <label for="arreglo_cpl">Arreglo / CPL</label>
                        <input type="text" name="arreglo_cpl" class="form-control" v-model="fillVehicle.arreglo_cpl">

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
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
        ...mapState(['fillVehicle', 'errorsLaravel', 'optionsTiposVehiculo']),
        optionsTiposVehiculoLabels() {
            return this.optionsTiposVehiculo.map(tipo => tipo.label)
        }
    },
    methods: {
        ...mapActions(['updateVehicleUser', 'allTiposVehiculos'])
    },
    created() {
        this.allTiposVehiculos()
    }
}
</script>
