<template>

    <form action="POST" v-on:submit.prevent="updateDetailimport({ id: fillDetailimport.id })">
        <div id="editDetailImport" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Producto de la Importación</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <label for="producto">Producto</label>
                        <input required
                                type="text"
                                name="producto"
                                class="form-control" v-model="fillDetailimport.product">
                        

                        <label for="codigo">Código</label>
                        <input required
                                type="text"
                                name="codigo"
                                class="form-control" v-model="fillDetailimport.detail">
                        
                        <label for="valor">Valor (clp)</label>
                        <input required
                                type="number"
                                step="0.01"
                                name="valor"
                                class="form-control" v-model="fillDetailimport.price">
                        

                        <label for="cantidad">Cantidad</label>
                        <input required
                                type="number"
                                name="cantidad"
                                class="form-control" v-model="fillDetailimport.quantity">
                        
                        <label for="adicional">Adicional (clp)</label>
                        <input 
                                type="number"
                                step="0.01"
                                name="adicional"
                                class="form-control" v-model="fillDetailimport.aditional">
                        


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
    computed:{
        ...mapState(['fillDetailimport', 'errorsLaravel']),
        ...mapGetters([])
    },
    methods:{
        ...mapActions(['updateDetailimport'])
    },
}
</script>
