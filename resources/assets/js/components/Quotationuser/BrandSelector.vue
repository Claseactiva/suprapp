<template>
    <v-select
        name="marca"   
        placeholder="Seleccionar Marca..."
        @input="setVBrand"
        :options="optionsVBrand"
        :value="selectedVBrand">
    </v-select>      
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['errorsLaravel', 'formCotizacion', 'optionsVBrand', 'selectedVBrand']),
    },
    methods: {
        ...mapActions(['setVBrand'])
    },
    created() {
        this.$store.dispatch('allVBrands')
    }
}
</script>