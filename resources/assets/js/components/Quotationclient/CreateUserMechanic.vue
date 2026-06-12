<template>
    <form action="POST" v-on:submit.prevent="createMechanicClient">
        <div id="modalCreateUserMechanic" class="modal fade">
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
                        <input required type="text" name="name" class="form-control" v-model="newUser.name">

                        <label for="email">Correo</label>
                        <input required type="email" name="email" class="form-control" v-model="newUser.email">


                        <label for="password">Contraseña</label>
                        <input required type="password" name="password" class="form-control" v-model="newUser.password">


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal" aria-label="close">
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
        ...mapActions(['createMechanicClient'])
    }

}
</script>