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
                        <RegistroLinkBox></RegistroLinkBox>

                        <label for="name">Nombre</label>
                        <input required type="text" name="name" class="form-control" v-model="newUser.name">

                        <label for="email">Correo</label>
                        <input required type="email" name="email" class="form-control" v-model="newUser.email">

                        <p class="text-muted mt-2 mb-0">
                            <small>Se enviara un correo al usuario para que configure su propia contrasena.</small>
                        </p>

                        <ActivationLink></ActivationLink>

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
import ActivationLink from '../User/ActivationLink'
import RegistroLinkBox from '../User/RegistroLinkBox'

export default {
    components: { ActivationLink, RegistroLinkBox },
    computed: {
        ...mapState(['newUser', 'errorsLaravel'])
    },
    methods: {
        ...mapActions(['createMechanicClient'])
    }

}
</script>