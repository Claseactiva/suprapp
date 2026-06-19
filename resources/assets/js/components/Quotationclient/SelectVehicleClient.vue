<template>
    <div class="pl-0">
        <v-select :class="{ 'input': true, 'is-invalid': errors.has('Modelo') }" name="modelo"
            placeholder="Seleccionar Modelo" @input="setVehicleClient" :options="optionsVehicleClient"
            :value="selectedVehicleClient">
        </v-select>
        <p v-show="errors.has('Modelo')" class="text-danger">{{ errors.first('Modelo') }}</p>
    </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['optionsVehicleClient', 'selectedVehicleClient']),
        //...mapGetters(['getVehicleModel'])
    },
    methods: {
        ...mapActions(['setVehicleClient'])
    },
    created() {
        this.$store.dispatch('allVehicleClients')
    }
}
</script>

<style></style>
