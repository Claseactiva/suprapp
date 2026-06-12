<template>
    <form action="POST" v-on:submit.prevent="updateProduct({ id: fillProduct.id })">
        <div id="edit_product" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Código</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="producto">Producto</label>
                            <input required type="text" name="producto" class="form-control" v-model="fillProduct.name">
                        </div>
                        <div class="form-group">
                            <label for="codigo">Código</label>
                            <input required type="text" name="codigo" class="form-control" v-model="fillProduct.codebar">
                        </div>
                        <div class="form-group">
                            <label for="detalle">Detalle</label>
                            <input required type="text" name="detalle" class="form-control" v-model="fillProduct.detail">
                        </div>
                        <div class="form-group">
                            <label for="atributo">Atributo</label>
                            <input required type="number" name="atributo" class="form-control"
                                v-model="fillProduct.atributo">
                        </div>
                        <div class="form-group">
                            <label for="utilidad">%Utilidad</label>
                            <input required type="number" name="utilidad" class="form-control"
                                v-model="fillProduct.utilidad">
                        </div>
                        <div class="form-group">
                            <label for="flete">Flete</label>
                            <input required type="number" name="flete" class="form-control" v-model="fillProduct.flete">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'
import SelectTiposPagos from '../Utilidad/SelectTiposPagos'

export default {
    components: { SelectTiposPagos },
    computed: {
        ...mapState(['fillProduct', 'errorsLaravel'])
    },
    methods: {
        ...mapActions(['updateProduct'])
    },
}
</script>
