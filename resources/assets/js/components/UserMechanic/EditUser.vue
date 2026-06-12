<template>

    <form action="POST" v-on:submit.prevent="updateMechanicClient({ id: fillUser.id })">
        <div id="edit" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Usuario</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input v-validate="'required|min:4|max:190'"
                                :class="{ 'input': true, 'is-invalid': errors.has('nombre') }" type="text" name="nombre"
                                class="form-control" v-model="fillUser.name">
                            <p v-show="errors.has('nombre')" class="text-danger">{{ errors.first('nombre') }}</p>

                            <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                <p>{{ error.name }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="correo">Email</label>
                            <input v-validate="'required|min:6|max:190'"
                                :class="{ 'input': true, 'is-invalid': errors.has('correo') }" type="email" name="correo"
                                class="form-control" v-model="fillUser.email">
                            <p v-show="errors.has('nombre')" class="text-danger">{{ errors.first('correo') }}</p>

                            <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                <p>{{ error.correo }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input v-validate="'required'"
                                :class="{ 'input': true, 'is-invalid': errors.has('password') }" type="password"
                                name="password" class="form-control" v-model="fillUser.password">
                            <p v-show="errors.has('password')" class="text-danger">{{ errors.first('password') }}</p>

                            <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                <p>{{ error.password }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="url">URL</label>
                            <input type="text" name="url" class="form-control" v-model="fillUser.url" readonly=readonly>
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
    computed: {
        ...mapState(['fillUser', 'errorsLaravel']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions(['updateMechanicClient'])
    },
}
</script>
