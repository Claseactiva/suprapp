<template>

    <form action="POST" v-on:submit.prevent="updateRole(fillRole)">
        <div id="edit" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Rol</h4>
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
                                class="form-control" v-model="fillRole.name">
                        <p v-show="errors.has('nombre')" class="text-danger">{{ errors.first('nombre') }}</p>

                        <label for="nombre">Descripción</label>
                        <input  type="text"
                                class="form-control" v-model="fillRole.description">


                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.name }}</p>
                            <p>{{ error.date }}</p>
                        </div>

                        <h6 class="mt-3">Lista de Permisos</h6>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="special" value="all-access"
                                    v-model="checkedSpecialRole">
                            <label class="form-check-label" for="inlineRadio1"> Acceso Total</label>
                        </div>
                        <div class="form-check form-check-inline mb-3">
                            <input class="form-check-input" type="radio" name="special" value="no-access"
                                    v-model="checkedSpecialRole">
                            <label class="form-check-label" for="inlineRadio1"> Ningún Acceso</label>
                        </div>

                        <div class="form-check form-check-inline mb-3">
                            <input class="form-check-input" type="radio" name="special" value=""
                                    v-model="checkedSpecialRole">
                            <label class="form-check-label" for="inlineRadio1"> Acceso Personalizado</label>
                        </div>

                        <div v-show="checkedSpecialRole !== 'all-access' && checkedSpecialRole !== 'no-access'" v-for="permissionLocal in permissions" :key="permissionLocal.id">
                            <input name="permission" type="checkbox" v-bind:id="permissionLocal.id" v-bind:value="permissionLocal.id"
                                    v-model="checkedPermissions">
                            <label v-bind:for="permissionLocal.id">{{ permissionLocal.name }} </label>
                        </div>
                        

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning" :disabled="!completeRoleEdit">
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
        ...mapState(['fillRole', 'permissions', 'checkedSpecialRole', 'errorsLaravel']),
        ...mapGetters(['completeRoleEdit']),
        checkedSpecialRole: {
            get () {
                return this.$store.state.checkedSpecialRole
            },
            set (value) {
                this.$store.commit('setSpecialRole', value)
            }
        },
        checkedPermissions: {
            get () {
                return this.$store.state.checkedPermissions
            },
            set (arr) {
                this.$store.commit('setPermissions', arr)
            }
        },
    },
    methods:{
        ...mapActions(['updateRole', 'getAllPermissions'])
    },
}
</script>
