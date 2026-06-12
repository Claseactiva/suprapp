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
                            <label for="v_engine">Motor</label>
                            <input v-validate="'required|min:2|max:190'"
                                    :class="{'input': true, 'is-invalid': errors.has('v_engine') }"
                                    type="text"
                                    name="v_engine"
                                    class="form-control" v-model="fillVehicleMotor.v_engine">
                            <p v-show="errors.has('v_engine')" class="text-danger">{{ errors.first('v_engine') }}</p>

                            <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                <p>{{ error.v_engine }}</p>
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
        ...mapState(['fillVehicleMotor', 'errorsLaravel'])
    },
    methods:{
        ...mapActions(['updateVehicleMotor'])
    },
}
</script>
