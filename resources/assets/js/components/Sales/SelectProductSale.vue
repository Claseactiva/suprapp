<template>
    <div class="pl-0">
        <v-select name="productSale" placeholder="Seleccionar Producto" @input="setProductSale"
            :options="optionsProductSale" :value="selectedProductSale" />
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['optionsProductSale', 'selectedProductSale']),
        ...mapGetters(['getProductSale'])
    },
    methods: {
        ...mapActions(['setProductSale'])
    },
    created() {
        this.$store.dispatch('allProductsSale')
    }
}
</script>
