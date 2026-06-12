<template>

    <form action="POST" v-on:submit.prevent="updateVehiculoTipo({ id: fillVehiculoTipo.id })">
        <div id="edit_tipo" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Tipo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <label for="tipo_vehiculo">Nombre</label>
                        <input v-validate="'required|min:2|max:190'"
                                :class="{'input': true, 'is-invalid': errors.has('tipo_vehiculo') }"
                                type="text"
                                name="tipo_vehiculo"
                                class="form-control" v-model="fillVehiculoTipo.tipo_vehiculo">
                        <p v-show="errors.has('tipo_vehiculo')" class="text-danger">{{ errors.first('tipo_vehiculo') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.tipo_vehiculo }}</p>
                        </div>

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
        ...mapState(['fillVehiculoTipo', 'errorsLaravel'])
    },
    methods:{
        ...mapActions(['updateVehiculoTipo'])
    },
}
</script>
