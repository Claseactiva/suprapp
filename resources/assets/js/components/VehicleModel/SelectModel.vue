<template>
    <div class="pl-0">
        <v-select
            :class="{'input': true, 'is-invalid': errors.has('Modelo') }"
            name="modelo"
            placeholder="Seleccionar Modelo"
            @input="setVehicleModel"
            :options="optionsVehicleModel"
            :value="selectedVehicleModel">
        </v-select>
        <p v-show="errors.has('Modelo')" class="text-danger">{{ errors.first('Modelo') }}</p>
    </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['optionsVehicleModel', 'selectedVehicleModel','selectedVehicleBrand']),
        ...mapGetters(['getVehicleModel'])
    },
    methods: {
        ...mapActions(['setVehicleModel'])
    },
    created() {
        this.$store.dispatch('allVehicleModels')
    }
}
</script>

<style>

</style>
