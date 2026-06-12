<template>

    <form action="POST" v-on:submit.prevent="createVehicleModel">
        <div id="createModel" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Nuevo Modelo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        
                        <SelectBrand></SelectBrand>
                        <label for="modelo">Modelo</label>
                        <input v-validate="'required|min:2|max:190'"
                                :class="{'input': true, 'is-invalid': errors.has('modelo') }"
                                type="text"
                                name="modelo"
                                class="form-control" v-model="newVehicleBrand.model">
                        <p v-show="errors.has('modelo')" class="text-danger">{{ errors.first('model') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.model }}</p>
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

import { mapState, mapGetters, mapActions } from 'vuex';

import SelectBrand from '../VehicleBrand/SelectBrand'

export default {
    components: { SelectBrand },
    computed:{
        ...mapState(['newVehicleBrand', 'errorsLaravel'])
    },
    methods:{
        ...mapActions(['createVehicleModel','editVehicleModel'])
    },
}
</script>


