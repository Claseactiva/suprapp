<template>
    <div id="upload_invoice" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Cargar Factura</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="invoice">Cargar factura</label>
                        <input v-validate="'required|min:4|max:190'"
                            :class="{ 'input': true, 'is-invalid': errors.has('invoice') || errorsLaravel.errors?.invoice }"
                            type="file" ref="invoice" class="form-control" @change="fileInvoice($event)" accept="application/xml">
                        <p v-show="errors.has('invoice')" class="text-danger">{{ errors.first('invoice') }}</p>
                        <div v-for="(errorss, index) in errorsLaravel" class="text-danger" :key="index">
                            <p v-for="error in errorss.invoice" :key="error">{{ error }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"
                        @click.prevent="uploadInvoice()">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex';

export default {
    computed: {
        ...mapState(['errorsLaravel']),
    },
    methods: {
        ...mapActions(['uploadInvoice']),
        fileInvoice(event) {
            const file = event.target.files[0];
            this.$store.state.fileInvoice = file;
        }
    }
}
</script>