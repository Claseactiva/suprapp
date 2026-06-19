<template>
    <div class="pl-0">
        <v-select
            :class="{'input': true, 'is-invalid': errors.has('marca') }"
            name="marca"
            placeholder="Seleccionar Marca"
            @input="setVehicleBrand"
            :options="optionsVehicleBrand"
            :value="selectedVehicleBrand"></v-select>
        <p v-show="errors.has('marca')" class="text-danger">{{ errors.first('marca') }}</p>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed:{
        ...mapState(['optionsVehicleBrand', 'selectedVehicleBrand']),
        ...mapGetters(['getVehicleBrand'])
    },
    methods:{
        ...mapActions(['setVehicleBrand'])
    },
    created(){
        this.$store.dispatch('allVehicleBrands')
    }
}
</script>

<style>

</style>
