<template>
    <div>
        <v-select
            :class="{'input': true, 'is-invalid': errors.has('select_tipo')}"
            name="select_tipo"
            placeholder="Seleccione el Tipo de Vehiculo"
            @input="setVehiculoTipo"
            :options="optionsTiposVehiculo"
            :value="selectedVehiculoTipo"></v-select>
        <p v-show="errors.has('select_tipo')" class="text-danger">{{ errors.first('select_tipo') }}</p>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed:{
        ...mapState(['optionsTiposVehiculo', 'selectedVehiculoTipo']),
        //...mapGetters(['getVehiculoTipos'])
    },
    methods:{
        ...mapActions(['setVehiculoTipo'])
    },
    created(){
        this.$store.dispatch('allTiposVehiculos')
    }
}
</script>

<style>

</style>
