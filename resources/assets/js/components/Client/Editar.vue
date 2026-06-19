<template>

    <form action="POST" v-on:submit.prevent="updateClient({ id: fillClient.id })">
        <div id="edit" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Cliente o Proveedor</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <label for="nombre">Cliente</label>
                        <input v-validate="'required|min:1'"
                                :class="{'input': true, 'is-invalid': errors.has('nombre') }"
                                type="text"
                                name="nombre"
                                class="form-control" v-model="fillClient.name">
                        <p v-show="errors.has('nombre')" class="text-danger">{{ errors.first('nombre') }}</p>

                        <label for="rut">Rut</label>
                        <input v-validate="'required|min:1'"
                                :class="{'input': true, 'is-invalid': errors.has('rut') }"
                                type="text"
                                name="rut"
                                class="form-control" v-model="fillClient.rut">
                        <p v-show="errors.has('rut')" class="text-danger">{{ errors.first('rut') }}</p>

                        <label for="razon_social">Razón Social</label>
                        <input v-validate="'required|min:1'"
                                :class="{'input': true, 'is-invalid': errors.has('razon_social') }"
                                type="text"
                                name="razon_social"
                                class="form-control" v-model="fillClient.razonSocial">
                        <p v-show="errors.has('razon_social')" class="text-danger">{{ errors.first('razon_social') }}</p>

                        <label for="correo">Email</label>
                        <input v-validate="'required|min:1'"
                                :class="{'input': true, 'is-invalid': errors.has('correo') }"
                                type="email"
                                name="correo"
                                class="form-control" v-model="fillClient.email">
                        <p v-show="errors.has('correo')" class="text-danger">{{ errors.first('correo') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.email }}</p>
                        </div>

                        <label for="telefono">Teléfono</label>
                        <input v-validate="'required|min:1'"
                                :class="{'input': true, 'is-invalid': errors.has('telefono') }"
                                type="text"
                                name="telefono"
                                class="form-control" v-model="fillClient.phone">
                        <p v-show="errors.has('telefono')" class="text-danger">{{ errors.first('telefono') }}</p>

                        <label for="direccion">Dirección</label>
                        <input v-validate="'required|min:1'"
                                :class="{'input': true, 'is-invalid': errors.has('direccion') }"
                                type="text"
                                name="direccion"
                                class="form-control" v-model="fillClient.address">
                        <p v-show="errors.has('direccion')" class="text-danger">{{ errors.first('direccion') }}</p>

                        <label for="comuna">Comuna</label>
                        <input v-validate="'required|min:1'"
                                :class="{'input': true, 'is-invalid': errors.has('comuna') }"
                                type="text"
                                name="comuna"
                                class="form-control" v-model="fillClient.comuna">
                        <p v-show="errors.has('comuna')" class="text-danger">{{ errors.first('comuna') }}</p>

                        <label for="tipo">Tipo</label>
                        <select class="form-control" v-model="fillClient.type">
                            <option>Cliente</option>
                            <option>Proveedor</option>
                            <option>Empresa</option>
                        </select>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.phone }}</p>
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
        ...mapState(['fillClient', 'errorsLaravel']),
        ...mapGetters([])
    },
    methods:{
        ...mapActions(['updateClient'])
    },
}
</script>
