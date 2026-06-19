<template>

    <form action="POST" v-on:submit.prevent="updateVehicle({ id: fillVehicle.id })">
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

                        <label for="usuario">Usuario</label>
                        <SelectUsuario></SelectUsuario>


                        <label for="patente">Patente</label>
                        <input v-validate="'required|min:4|max:190'"
                            :class="{ 'input': true, 'is-invalid': errors.has('patente') }" type="text" name="patente"
                            class="form-control" v-model="fillVehicle.patent">
                        <p v-show="errors.has('patente')" class="text-danger">{{ errors.first('patente') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.patent }}</p>
                        </div>

                        <label for="chasis">Chasis</label>
                        <input v-validate="'required|min:4|max:190'"
                            :class="{ 'input': true, 'is-invalid': errors.has('chasis') }" type="text" name="chasis"
                            class="form-control" v-model="fillVehicle.chasis">
                        <p v-show="errors.has('chasis')" class="text-danger">{{ errors.first('chasis') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.chasis }}</p>
                        </div>

                        <label for="brand">Marca</label>
                        <BrandSelector />

                        <label for="model">Modelo</label>
                        <ModelSelector />

                        <label for="anio">Año</label>
                        <YearSelector />

                        <label for="engine">Motor</label>
                        <EngineSelector />


                        <label for="color">Color</label>
                        <input v-validate="'required|min:2|max:190'"
                            :class="{ 'input': true, 'is-invalid': errors.has('color') }" type="text" name="color"
                            class="form-control" v-model="fillVehicle.color">
                        <p v-show="errors.has('color')" class="text-danger">{{ errors.first('color') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.color }}</p>
                        </div>

                        <label for="km">Kilometraje</label>
                        <input v-validate="'required|min:4|max:190'"
                            :class="{ 'input': true, 'is-invalid': errors.has('km') }" type="number" name="km"
                            class="form-control" v-model="fillVehicle.km">
                        <p v-show="errors.has('km')" class="text-danger">{{ errors.first('km') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.km }}</p>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning" :disabled="!completeVehicleEdit">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'
import SelectUsuario from './SelectMechanicClient'
import BrandSelector from '../Quotationuser/BrandSelector'
import ModelSelector from '../Quotationuser/ModelSelector'
import YearSelector from '../Quotationuser/YearSelector'
import EngineSelector from '../Quotationuser/EngineSelector'

export default {
    components: { SelectUsuario, BrandSelector, ModelSelector, YearSelector, EngineSelector },
    computed: {
        ...mapState(['fillVehicle', 'errorsLaravel']),
        ...mapGetters(['completeVehicleEdit'])
    },
    methods: {
        ...mapActions(['updateVehicle'])
    },
}
</script>
