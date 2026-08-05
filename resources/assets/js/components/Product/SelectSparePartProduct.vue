<template>
    <div class="pl-0">
        <v-select
            name="producto_repuesto"
            placeholder="Seleccionar Producto"
            @input="setSparePartProduct"
            :options="optionsProduct"
            :value="selectedSparePartProduct"
        />
    </div>
</template>

<script>

import { mapState, mapActions } from 'vuex'

export default {
    computed:{
        ...mapState(['optionsProduct', 'selectedSparePartProduct'])
    },
    methods:{
        ...mapActions(['setSparePartProduct'])
    },
    created(){
        this.$store.dispatch('allProducts')
    }
}
</script>
