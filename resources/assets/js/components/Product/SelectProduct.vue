<template>
    <div class="pl-0">
        <v-select
            :class="{'input': true, 'is-invalid': errors.has('producto') }"
            name="producto"
            placeholder="Seleccionar Producto"
            @input="setProduct"
            :options="optionsProduct"
            :value="selectedProduct"
        />
        <p v-show="errors.has('producto')" class="text-danger">{{ errors.first('producto') }}</p>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed:{
        ...mapState(['optionsProduct', 'selectedProduct']),
        ...mapGetters(['getProduct'])
    },
    methods:{
        ...mapActions(['setProduct'])
    },
    created(){
        this.$store.dispatch('allProducts')
    }
}
</script>
