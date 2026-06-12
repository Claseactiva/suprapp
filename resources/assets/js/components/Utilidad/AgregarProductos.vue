<template>
    <form action="POST" v-on:submit.prevent="createProductsPagos">
        <div id="createProducts" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Ingresar Productos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <!-- <input type="file" class="form-control"  id="input-file-import" name="file_import" ref="import_file" accept=".xlsx, .xls" @change="onFileChange({ evt: $event})"> -->
                            <input required id="files" type="file" name="import_file" class="form-control"
                                accept=".xlsx, .xls" @change="onFileChange({ evt: $event })">
                        </div>
                        <div class="form-group">
                            <label for="empresa">Empresa</label>
                            <SelectProvider></SelectProvider>
                        </div>
                        <div class="form-group">
                            <label for="utilidad">Forma de Pago</label>
                            <SelectTiposPagos></SelectTiposPagos>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-plus-square"></i> Subir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex';
import SelectProvider from '../Provider/Select'
import SelectTiposPagos from './SelectTiposPagos'


export default {
    components: { SelectProvider, SelectTiposPagos },
    data() {
        return {
            attachment: [],
            form: new FormData
        }
    },
    computed: {
        ...mapState(['selectedPago', 'selectedClient', 'errorsLaravel']),
    },

    methods: {
        ...mapActions(['createProductsPagos', 'onFileChange']),
    },
}
</script>