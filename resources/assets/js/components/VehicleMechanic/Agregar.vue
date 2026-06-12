<template>

    <form action="POST" v-on:submit.prevent="createVehicleMechanicClient">
        <div id="createVehicleMechanic" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Registrar Vehículo (Mecánico)</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <label for="usuario">Usuario</label>
                        <SelectMechanicClient/>

                        <label for="patente">Patente</label>
                        <input v-validate="'required|min:2|max:190'"
                                :class="{'input': true, 'is-invalid': errors.has('patente') }"
                                type="text"
                                name="patente"
                                class="form-control" v-model="newVehicle.patent">
                        <p v-show="errors.has('patente')" class="text-danger">{{ errors.first('patente') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.patent }}</p>
                        </div>

                        <label for="patente">Chasis</label>
                        <input v-validate="'required|min:2|max:190'"
                                :class="{'input': true, 'is-invalid': errors.has('chasis') }"
                                type="text"
                                name="chasis"
                                class="form-control" v-model="newVehicle.chasis">
                        <p v-show="errors.has('chasis')" class="text-danger">{{ errors.first('chasis') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.chasis }}</p>
                        </div>

                        <label for="patente">Marca</label>
                        <BrandSelector/>


                        <label for="modelo">Modelo</label>
                        <ModelSelector/>


                        <label for="anio">Año</label>
                        <YearSelector/>

                        <label for="engine">Motor</label>
                        <EngineSelector/>

                        <label for="color">Color</label>
                        <input v-validate="'required|min:2|max:190'"
                                :class="{'input': true, 'is-invalid': errors.has('color') }"
                                type="text"
                                name="color"
                                class="form-control" v-model="newVehicle.color">
                        <p v-show="errors.has('color')" class="text-danger">{{ errors.first('color') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.color }}</p>
                        </div>

                        <label for="km">Kilometraje</label>
                        <input v-validate="'required|min:1|max:190'"
                                :class="{'input': true, 'is-invalid': errors.has('km') }"
                                type="number"
                                name="km"
                                class="form-control" v-model="newVehicle.km">
                        <p v-show="errors.has('km')" class="text-danger">{{ errors.first('km') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.km }}</p>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus-square"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'
import SelectMechanicClient from './SelectMechanicClient'
import BrandSelector from '../Quotationuser/BrandSelector'
import ModelSelector from '../Quotationuser/ModelSelector'
import YearSelector from '../Quotationuser/YearSelector'
import EngineSelector from '../Quotationuser/EngineSelector'

export default {
    components: { SelectMechanicClient, BrandSelector, ModelSelector, YearSelector, EngineSelector },
    computed:{
        ...mapState(['newVehicle', 'errorsLaravel', 'selectedVBrand', 'selectedVModel', 'selectedVYear', 'selectedVEngine']),
        ...mapGetters(['completeVehicleCreate'])
    },
    methods:{
        ...mapActions(['createVehicleMechanicClient'])
    },
}
</script>

