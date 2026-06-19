<template>
    <v-select
        name="modelo"
        placeholder="Seleccionar Modelo..."
        @input="setMM"
        :options="optionsMM"
        :value="selectedMM">
    </v-select>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['errorsLaravel', 'optionsMM', 'selectedMM']),
    },
    methods: {
        ...mapActions(['setMM'])
    },
    created(){
        this.$store.dispatch('allMM')
    }
}
</script>