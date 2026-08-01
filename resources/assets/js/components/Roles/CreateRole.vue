<template>

    <form action="POST" v-on:submit.prevent="createRole">
        <div id="create" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Nuevo Rol</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <label for="nombre">Nombre</label>
                        <input v-validate="'required|min:4|max:190'"
                                :class="{'input': true, 'is-invalid': errors.has('nombre') }"
                                type="text"
                                name="nombre"
                                class="form-control" v-model="newRole.name">
                        <p v-show="errors.has('nombre')" class="text-danger">{{ errors.first('nombre') }}</p>

                        <label for="nombre">Descripción</label>
                        <input  type="text"
                                class="form-control" v-model="newRole.description">

                        <label for="default_cant_vehicle" class="mt-2">Cantidad de Vehiculos por defecto</label>
                        <select class="form-control" v-model="newRole.default_cant_vehicle">
                            <option value="">Sin definir</option>
                            <option v-for="opt in cantidadVehiculoOptions" :key="opt.id" :value="opt.value">
                                1 - {{ opt.value }}
                            </option>
                        </select>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.name }}</p>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" :disabled="!completeRoleCreate">
                            <i class="fas fa-plus-square"></i> Guardar
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
        ...mapState(['newRole', 'errorsLaravel', 'cantidadVehiculoOptions']),
        ...mapGetters(['completeRoleCreate'])
    },
    methods:{
        ...mapActions(['createRole', 'getCantidadVehiculoOptions'])
    },
    created() {
        this.getCantidadVehiculoOptions()
    }
}
</script>

