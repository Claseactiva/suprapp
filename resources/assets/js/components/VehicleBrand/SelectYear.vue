<template>
    <div class="pl-0">
        <v-select
            :class="{'input': true, 'is-invalid': errors.has('select_year') }"
            name="select_year"
            placeholder="Seleccionar Año"
            @input="setVehicleYear"
            :options="optionsYear"
            :value="selectedVehicleYear"></v-select>
        <p v-show="errors.has('select_year')" class="text-danger">{{ errors.first('select_year') }}</p>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed:{
        ...mapState(['optionsYear', 'selectedVehicleYear']),
        //...mapGetters(['getVehicleMotor'])
    },
    methods:{
        ...mapActions(['setVehicleYear'])
    },
    created(){
        this.$store.dispatch('allVehicleYears')
    }
}
</script>

<style>

</style>
