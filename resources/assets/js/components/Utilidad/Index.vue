<template>

    <div class="col-lg-12">

        <button type="submit" data-toggle="modal" data-target="#createTiposPagos" class="btn btn-success"><i
                class="fas fa-plus-circle"></i> Forma de Pagos</button>
        <button type="submit" data-toggle="modal" data-target="#createDescuento" class="btn btn-success"><i
                class="fas fa-plus-circle"></i> Descuento</button>

        <table class="table table-responsive-new table-dark table-sm mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo de Pago</th>
                    <th>% Utilidad</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="tipospagosLocal in tipospagos" :key="tipospagosLocal.id">
                    <td data-table-label="ID">{{ tipospagosLocal.id }}</td>
                    <td data-table-label="Tipo de Pago">{{ tipospagosLocal.pago }}</td>
                    <td data-table-label="% Utilidad">{{ tipospagosLocal.utilidad }}%</td>
                    <td class="text-right">
                        <button class="btn btn-warning" @click.prevent="editTiposPagos({ tipospagosLocal })"
                            data-toggle="tooltip" data-placement="top" title="Editar Tipo de pago">
                            <i class="far fa-edit"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <AgregarTiposPagos></AgregarTiposPagos>
        <EditTiposPagos></EditTiposPagos>
        <AgregarDescuento></AgregarDescuento>

    </div>
</template>
<script>

import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapActions, mapGetters } from 'vuex'
import AgregarTiposPagos from './AgregarTiposPagos'
import EditTiposPagos from './EditTiposPagos'
import AgregarDescuento from '../Utilidad/Descuentos'

export default {
    components: { AgregarTiposPagos, EditTiposPagos, AgregarDescuento },
    computed: {
        ...mapState(['tipospagos']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions(['getTiposPagos', 'createTipoPago', 'editTiposPagos'])
    },
    created() {
        loadProgressBar();
        this.$store.dispatch('getTiposPagos')
    }
}
</script>
