<template>

    <form action="POST" v-on:submit.prevent="updateUtilidad({ id: fillTipoPago.id })">
        <div id="editUtilidad" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Forma de Pago</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <SelectTiposPagos></SelectTiposPagos>
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
import SelectTiposPagos from './SelectTiposPagos'

export default {
    components: { SelectTiposPagos },
    computed: {
        ...mapState(['fillTipoPago', 'errorsLaravel']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions(['updateUtilidad'])
    },
}
</script>
