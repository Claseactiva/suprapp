<template>
    <v-select
        name="motor_spec"
        placeholder="Seleccionar Motor..."
        @input="setMotorSpec"
        :options="optionsMotorSpec"
        :value="selectedMotorSpec">
    </v-select>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['errorsLaravel', 'optionsMotorSpec', 'selectedMotorSpec']),
    },
    methods: {
        ...mapActions(['setMotorSpec'])
    },
    created(){
        this.$store.dispatch('allMotorSpecs')
    }
}
</script>
