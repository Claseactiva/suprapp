<template>

    <form action="POST" v-on:submit.prevent="updateVehicleMotor({ id: fillVehicleMotor.id })">
        <div id="edit_motor" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Motor</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="col-0">
                            <label for="motor_spec_id">Motor</label>
                            <v-select
                                name="motor_spec_id"
                                placeholder="Seleccionar Motor..."
                                @input="setFillMotorSpec"
                                :options="optionsMotorSpec"
                                :value="selectedFillMotorSpec">
                            </v-select>

                            <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                <p>{{ error.motor_spec_id }}</p>
                            </div>
                        </div>

                        <div class="col-0 mt-2">
                            <label for="year_from">Año desde</label>
                            <input v-validate="'required|numeric|min_value:1900|max_value:2100'"
                                    :class="{'input': true, 'is-invalid': errors.has('year_from') }"
                                    type="number"
                                    name="year_from"
                                    class="form-control" v-model="fillVehicleMotor.year_from">
                            <p v-show="errors.has('year_from')" class="text-danger">{{ errors.first('year_from') }}</p>

                            <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                <p>{{ error.year_from }}</p>
                            </div>
                        </div>

                        <div class="col-0 mt-2">
                            <label for="year_to">Año hasta</label>
                            <input v-validate="'required|numeric|min_value:1900|max_value:2100'"
                                    :class="{'input': true, 'is-invalid': errors.has('year_to') }"
                                    type="number"
                                    name="year_to"
                                    class="form-control" v-model="fillVehicleMotor.year_to">
                            <p v-show="errors.has('year_to')" class="text-danger">{{ errors.first('year_to') }}</p>

                            <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                <p>{{ error.year_to }}</p>
                            </div>
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
export default {
    computed:{
        ...mapState(['fillVehicleMotor', 'errorsLaravel', 'optionsMotorSpec']),
        selectedFillMotorSpec() {
            return this.optionsMotorSpec.find(option => option.value === this.fillVehicleMotor.motor_spec_id) || { label: '', value: '' }
        }
    },
    methods:{
        ...mapActions(['updateVehicleMotor']),
        setFillMotorSpec(option) {
            this.fillVehicleMotor.motor_spec_id = option ? option.value : ''
        }
    },
}
</script>
