<template>

    <form action="POST" v-on:submit.prevent="updateQuotationShipping({ id: fillQuotationShipping.id })">
        <div id="modalQuotationShipping" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Envio a Domicilio</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <label for="direccion">Dirección</label>
                        <input required class="form-control" type="text" name="direccion"
                            v-model="fillQuotationShipping.direccion" />
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            Guardar
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
        ...mapState(['fillQuotationShipping', 'errorsLaravel']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions(['updateQuotationShipping'])
    },
}
</script>
