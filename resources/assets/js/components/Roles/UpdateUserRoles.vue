<template>

    <form action="POST" v-on:submit.prevent="updateUserRoles({ id: fillUserRoles.id })">
        <div id="editRoles" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Asignar Roles al Usuario - {{ fillUserRoles.name }}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div v-for="rolesLocal in roles" :key="rolesLocal.id">
                            <input type="checkbox" v-bind:id="rolesLocal.id" v-bind:value="rolesLocal.id"
                                v-model="checkedRoles">
                            <label v-bind:for="rolesLocal.id">{{ rolesLocal.description }}</label>
                        </div>
                        <!--<span>Roles Seleccionados: {{ checkedRoles }}</span>-->

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-user-plus"></i> Asignar Rol(es)
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
        ...mapState(['roles', 'fillUserRoles',]),
        //...mapGetters(['getArray']),
        checkedRoles: {
            get() {
                return this.$store.state.checkedRoles
            },
            set(arr) {
                this.$store.commit('setRoles', arr)
            }
        },
    },
    methods: {
        ...mapActions(['updateUserRoles', 'setArray'])
    },
    created() {
        this.$store.dispatch('getAllRoles')
    }
}
</script>
