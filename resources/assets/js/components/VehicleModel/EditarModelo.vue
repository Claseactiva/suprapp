<template>

    <form action="POST" v-on:submit.prevent="updateVehicleModel({ id: fillVehicleModel.id })">
        <div id="edit_modelo" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Modelo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-0">
                            <label for="modelo">Modelo</label>
                            <input v-validate="'required|min:2|max:190'"
                                :class="{ 'input': true, 'is-invalid': errors.has('modelo') }" type="text" name="modelo"
                                class="form-control" v-model="fillVehicleModel.model">
                            <p v-show="errors.has('modelo')" class="text-danger">{{ errors.first('model') }}</p>

                            <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                <p>{{ error.model }}</p>
                            </div>
                        </div>
                        <div class="col-0">
                            <label for="model">Marca</label>
                            <SelectBrand></SelectBrand>
                        </div>

                        <div class="col-0">
                            <label for="model">Tipo de vehiculo</label>
                            <TiposSelector></TiposSelector>
                        </div>


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

import SelectBrand from '../VehicleBrand/SelectBrand'
import TiposSelector from '../VehicleBrand/TiposSelector'

export default {
    components: { SelectBrand, TiposSelector },
    computed: {
        ...mapState(['fillVehicleModel', 'errorsLaravel'])
    },
    methods: {
        ...mapActions(['updateVehicleModel'])
    },
}
</script>
