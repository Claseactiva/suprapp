<template>
    <form action="POST" v-on:submit.prevent="agregarFormaPago">
        <div id="formaPago" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Seleccione la forma de Pago</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">

                        </div>

                        <div class="form-group">
                            <label for="utilidad">Forma de Pago</label>
                            <SelectTiposPagos></SelectTiposPagos>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus-square"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex';
import axios from 'axios'
import toastr from 'toastr'
import SelectTiposPagos from '../Utilidad/SelectTiposPagos'


export default {
    components: { SelectTiposPagos },
    computed: {
        ...mapState(['selectedPago', 'errorsLaravel']),
    },

    methods: {
        ...mapActions(['agregarFormaPago']),
    },
}
</script>