<template>
    <div class="pl-0">
        <v-select :class="{ 'input': true, 'is-invalid': errors.has('usuario') }" name="usuario"
            placeholder="Seleccionar Usuario" @input="setUser" :options="optionsUser" :value="selectedUser"></v-select>
        <p v-show="errors.has('usuario')" class="text-danger">{{ errors.first('usuario') }}</p>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['optionsUser', 'selectedUser']),
        ...mapGetters(['getUser'])
    },
    methods: {
        ...mapActions(['setUser'])
    },
    created() {
        this.$store.dispatch('allUsers')
    }
}
</script>

<style></style>
