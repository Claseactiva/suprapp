<template>

    <div id="historial_motor" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Historial del Motor</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-new table-dark table-sm">
                            <thead>
                                <tr>
                                    <th>Vehículo</th>
                                    <th>Desde</th>
                                    <th>Hasta</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="row in motorHistory" :key="row.id">
                                    <td>{{ row.vehicle ? row.vehicle.patent + ' - ' + row.vehicle.brand + ' ' + row.vehicle.model : 'N/D' }}</td>
                                    <td>{{ row.fecha_inicio }}</td>
                                    <td>{{ row.fecha_fin || 'Vigente' }}</td>
                                </tr>
                                <tr v-if="!motorHistory.length">
                                    <td colspan="3" class="text-center text-muted">Sin historial</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import { mapState } from 'vuex'
export default {
    computed: {
        ...mapState(['motorHistory'])
    }
}
</script>
