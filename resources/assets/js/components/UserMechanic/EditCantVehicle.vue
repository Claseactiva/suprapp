<template>
    <div id="editCantVehicle" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Detalle</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-responsive-new table-dark table-sm mt-3">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text-center">Puede crear</th>
                                        <th class="text-center">Creados</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Vehiculos</th>
                                        <td data-table-label="Puede crear">1 - {{ fillCantVehicle.cant_vehicle }}</td>
                                        <td data-table-label="Creados">{{ totalvehi }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';

export default {
    computed: {
        ...mapState(['totalvehi', 'fillCantVehicle', 'errorsLaravel']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions(['getTotalVehi'])
    }

}
</script>
