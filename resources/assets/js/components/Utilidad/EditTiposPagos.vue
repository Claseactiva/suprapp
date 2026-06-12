<template>

    <form action="POST" v-on:submit.prevent="updateTiposPagos({ id: fillTipoPago.id })">
        <div id="edit" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Tipo de Pago</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="pago">Tipo de Pago</label>
                            <input required type="text" name="pago" class="form-control" v-model="fillTipoPago.pago">

                        </div>

                        <div class="form-group">
                            <label for="utilidad">% Utilidad</label>
                            <input required type="number" name="utilidad" class="form-control"
                                v-model="fillTipoPago.utilidad">
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
    computed: {
        ...mapState(['fillTipoPago', 'errorsLaravel']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions(['updateTiposPagos'])
    },
}
</script>
