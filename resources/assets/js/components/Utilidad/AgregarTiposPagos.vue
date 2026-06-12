<template>

    <form action="POST" v-on:submit.prevent="createTipoPago">
        <div id="createTiposPagos" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Crear Formas de Pagos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="pago">Tipo de Pago</label>
                            <input required type="text" name="pago" class="form-control" v-model="newTipoPago.pago">

                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-plus-square"></i> Guardar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</template>

<script>

import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: {},
    computed: {
        ...mapState(['newTipoPago']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions(['createTipoPago'])
    }
}

</script>
