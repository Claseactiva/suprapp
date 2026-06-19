<template>
    <div id="delete_product" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>¿Eliminar este producto?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Esto eliminará producto permanentemente
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancelar</button>
                    <button type="submit" class="btn btn-danger" @click.prevent="deleteProduct({ id: deleteProductId })">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex';

export default {
    computed: {
        ...mapState(['deleteProductId']),
    },
    methods: {
        ...mapActions(['deleteProduct'])
    },
}
</script>