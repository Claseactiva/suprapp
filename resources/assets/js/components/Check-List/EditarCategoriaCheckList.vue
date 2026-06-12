<template>
    <form action="POST"
        v-on:submit.prevent="editarCategoriaCheckList({ id_categoria: editarCategoriaForm.id_categoria })">
        <div id="EditarCategoriaCheckList" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Categoria</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">

                            <label for="categoria">Categoria</label>
                            <input required type="text" name="categoria" class="form-control"
                                v-model="editarCategoriaForm.categoria">
                        </div>


                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger" v-on:click="cerrarCategoria">
                            Cancelar
                        </button>

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
    components: {},
    computed: {
        ...mapState(['editarCategoriaForm', 'errorsLaravel']),
        ...mapGetters(['']),
    },
    methods: {
        ...mapActions(['editarCategoriaCheckList' , 'cerrarCategoria'])
    },
}
</script>
