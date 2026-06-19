<template>
    <div class="pl-0">
        <v-select
            required
            name="cliente"
            placeholder="Seleccionar Cliente"
            @input="setClient"
            :options="optionsClient"
            :value="selectedClient"></v-select>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed:{
        ...mapState(['optionsClient', 'selectedClient', 'errorsLaravel']),
        ...mapGetters(['getClient'])
    },
    methods:{
        ...mapActions(['setClient'])
    },
    created(){
        this.$store.dispatch('allClients', { type: 'Proveedor' })
    }
}
</script>

<style>

</style>
