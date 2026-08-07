<template>
    <div class="pl-0">
        <v-select
            name="proveedor"
            placeholder="Seleccionar Proveedor"
            @input="setSupplier"
            :options="optionsSupplier"
            :value="selectedSupplier"></v-select>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['selectedSupplier']),
        ...mapGetters(['optionsSupplier'])
    },
    methods: {
        ...mapActions(['setSupplier', 'getSuppliers'])
    },
    created() {
        this.getSuppliers()
    }
}
</script>
