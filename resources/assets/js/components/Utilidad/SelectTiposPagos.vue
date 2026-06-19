<template>
    <div class="pl-0">
        <v-select name="pagos" placeholder="Seleccionar Forma de Pago" @input="setPagos" :options="optionsPago"
            :value="selectedPago"></v-select>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['optionsPago', 'selectedPago']),
        ...mapGetters(['getPago'])
    },
    methods: {
        ...mapActions(['setPagos'])
    },
    created() {
        this.$store.dispatch('allPagos')
    }
}
</script>

<style>
.v-select .dropdown-toggle .clear {
    display: none;
}
</style>
