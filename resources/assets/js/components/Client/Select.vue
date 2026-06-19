<template>
    <div class="pl-0">
        <v-select
            :class="{'input': true, 'is-invalid': errors.has('cliente') }"
            name="cliente"
            v-bind:disabled="newQuotationclient.cliente_part === true ? true : false"
            placeholder="Seleccionar Cliente"
            @input="setClient"
            :options="optionsClient"
            :value="selectedClient"></v-select>
        <p v-show="errors.has('cliente')" class="text-danger">{{ errors.first('cliente') }}</p>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed:{
        ...mapState(['optionsClient', 'newQuotationclient','selectedClient']),
        ...mapGetters(['getClient'])
    },
    methods:{
        ...mapActions(['setClient'])
    },
    created(){
        this.$store.dispatch('allClients', { type: 'Cliente' })
    }
}
</script>

<style>

</style>
