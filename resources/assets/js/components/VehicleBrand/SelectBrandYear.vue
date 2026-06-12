<template>
    <v-select
        name="marca"
        placeholder="Seleccionar Marca"
        @input="setVBR"
        :options="optionsVBR"
        :value="selectedVBR">
    </v-select>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed:{
        ...mapState(['errorsLaravel','optionsVBR', 'selectedVBR']),
    },
    methods:{
        ...mapActions(['setVBR'])
    },
    created(){
        this.$store.dispatch('allVBR')
    }
}
</script>
