<template>

    <form action="POST" v-on:submit.prevent="updateVehicleYear({ id: fillVehicleYear.id })">
        <div id="edit_year" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Año</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-0">
                            <label for="year">Año</label>
                            <input v-validate="'required|numeric|max:9999'"
                                    :class="{'input': true, 'is-invalid': errors.has('year') }"
                                    type="number"
                                    name="year"
                                    class="form-control" v-model="fillVehicleYear.v_year">
                            <p v-show="errors.has('year')" class="text-danger">{{ errors.first('v_year') }}</p>

                            <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                <p>{{ error.v_year }}</p>
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
        ...mapState(['fillVehicleYear', 'errorsLaravel'])
    },
    methods:{
        ...mapActions(['updateVehicleYear'])
    },
}
</script>
