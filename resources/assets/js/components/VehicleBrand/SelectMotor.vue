<template>
    <div class="pl-0">
        <v-select
            :class="{'input': true, 'is-invalid': errors.has('motor') }"
            name="motor"
            placeholder="Seleccionar Motor"
            @input="setVehicleMotor"
            :options="optionsMotores"
            :value="selectedVehicleMotor"></v-select>
        <p v-show="errors.has('motor')" class="text-danger">{{ errors.first('motor') }}</p>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed:{
        ...mapState(['optionsMotores', 'selectedVehicleMotor']),
        //...mapGetters(['getVehicleMotor'])
    },
    methods:{
        ...mapActions(['setVehicleMotor'])
    },
    created(){
        this.$store.dispatch('allVehicleMotors')
    }
}
</script>

<style>

</style>
