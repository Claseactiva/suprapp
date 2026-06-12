<template>
    <v-select
        name="modelo"
        placeholder="Seleccionar Modelo..."
        @input="setVMR"
        :disabled="selectedVBR.label==''"
        :options="optionsVMR"
        :value="selectedVMR">
    </v-select>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['errorsLaravel', 'optionsVMR', 'selectedVMR', 'selectedVBR']),
    },
    methods: {
        ...mapActions(['setVMR'])
    }
}
</script>