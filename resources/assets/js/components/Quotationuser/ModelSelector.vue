<template>
    <v-select
        name="modelo"
        placeholder="Seleccionar Modelo..."
        @input="setVModel"
        :disabled="selectedVBrand.label==''"
        :options="optionsVModel"
        :value="selectedVModel">
    </v-select>
    
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['errorsLaravel', 'optionsVModel', 'selectedVModel', 'selectedVBrand']),
    },
    methods: {
        ...mapActions(['setVModel'])
    }
}
</script>