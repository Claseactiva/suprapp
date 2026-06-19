<template>

    <form action="POST" v-on:submit.prevent="updateCantCliVehi({ id: fillCantCliVehi.id })">
        <div id="editCantCliVehi" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Vehiculos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="cant_vehicle">Vehiculos</label>
                            <select class="form-control" name="cant_vehicle" v-model="fillCantCliVehi.cant_vehicle">
                                <option disabled value="0">Seleccione Cantidad</option>
                                <option value="1" selected>1 - 1</option>
                                <option value="5">1 - 5</option>
                                <option value="10">1 - 10</option>
                                <option value="30">1 - 30</option>
                                <option value="50">1 - 50</option>
                                <option value="100">1 - 100</option>
                                <option value="500">1 - 500</option>
                                <option value="1000">1 - 1000</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer d-block">
                        <div class="row align-items-center">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</template>

<script>
import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapGetters, mapActions } from 'vuex';

export default {
    computed: {
        ...mapState(['totalvehiadmin', 'fillCantCliVehi', 'cantCliVehiAdmin', 'errorsLaravel']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions(['getTotalVehiAdmin', 'updateCantCliVehi'])
    },


}
</script>
