<template>
    <v-select name="ciudad" placeholder="Seleccionar Ciudad..." @input="setCiudad" :options="optionsCiudad"
        :value="selectedCiudad">
    </v-select>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['errorsLaravel', 'formQuotationShipping', 'optionsCiudad', 'selectedCiudad']),
    },
    methods: {
        ...mapActions(['setCiudad'])
    },
    created() {
        this.$store.dispatch('allCiudad')
    }
}
</script>
