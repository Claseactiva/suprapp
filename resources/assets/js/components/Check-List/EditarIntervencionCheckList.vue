<template>
    <form action="POST"
        v-on:submit.prevent="editarIntervencionCheckList({ id_intervencion: editarIntervencionForm.id_intervencion })">
        <div id="EditarIntervencionCheckList" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Intervencion</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">

                            <label for="intervencion">Intervencion</label>
                            <input required type="text" name="intervencion" class="form-control"
                                v-model="editarIntervencionForm.intervencion">
                        </div>


                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger" v-on:click="cerrarIntervencion">
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
        ...mapState(['editarIntervencionForm', 'errorsLaravel']),
        ...mapGetters(['']),
    },
    methods: {
        ...mapActions(['editarIntervencionCheckList', 'cerrarIntervencion'])
    },
}
</script>
