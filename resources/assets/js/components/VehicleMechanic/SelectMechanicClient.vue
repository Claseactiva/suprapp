<template>
    <div class="pl-0">
        <v-select
            :class="{'input': true, 'is-invalid': errors.has('usuario') }"
            name="usuario"
            placeholder="Seleccionar Usuario"
            @input="setMechanicClient"
            :options="optionsMechanicClient"
            :value="selectedMechanicClient">
        </v-select>
        <p v-show="errors.has('usuario')" class="text-danger">{{ errors.first('usuario') }}</p>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed:{
        ...mapState(['optionsMechanicClient', 'selectedMechanicClient']),
    },
    methods:{
        ...mapActions(['setMechanicClient'])
    },
    created(){
        this.$store.dispatch('getMechanicClients')
    }
}
</script>

<style>

</style>
