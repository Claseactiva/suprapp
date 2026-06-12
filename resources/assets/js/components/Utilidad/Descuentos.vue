<template>
    <form action="POST" v-on:submit.prevent="createDescuento">
        <div id="createDescuento" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Descuento</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="pago">Descuento</label>
                            <input required type="number" name="descuento" class="form-control"
                                v-model="newDescuento.descuento">

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

import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapActions, mapGetters } from 'vuex'


export default {
    components: {},
    computed: {
        ...mapState(['newDescuento']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions(['createDescuento'])
    },
    created() {
        this.$store.dispatch('descuentoDefect')
    }
}

</script>
