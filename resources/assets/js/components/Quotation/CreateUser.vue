<template>
    <form action="POST" v-on:submit.prevent="createUser">
        <div id="modalCreateUser" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Crear Usuario</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="name">Nombre</label>
                        <input v-validate="'required|min:4'"
                                :class="{'input': true, 'is-invalid': errors.has('name') }"
                                type="text"
                                name="name"
                                class="form-control" v-model="newUser.name">
                        <p v-show="errors.has('name')" class="text-danger">{{ errors.first('name') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.name }}</p>
                        </div>

                        <label for="email">Correo</label>
                        <input v-validate="'required|min:6'"
                                :class="{'input': true, 'is-invalid': errors.has('email') }"
                                type="email"
                                name="email"
                                class="form-control" v-model="newUser.email">
                        <p v-show="errors.has('email')"
                            class="text-danger">{{ errors.first('email') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.email }}</p>
                        </div>

                        <label for="password">Contraseña</label>
                        <input v-validate="'required'"
                                :class="{'input': true, 'is-invalid': errors.has('password') }"
                                type="password"
                                name="password"
                                class="form-control" v-model="newUser.password">
                        <p v-show="errors.has('password')"
                            class="text-danger">{{ errors.first('password') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.password }}</p>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal" aria-label="close">
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Crear
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    computed: {
        ...mapState(['newUser', 'errorsLaravel'])
    },
    methods: {
        ...mapActions(['createUser'])
    }
    
}
</script>