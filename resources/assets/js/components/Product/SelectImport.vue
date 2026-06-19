<template>
    <div class="pl-0">
        <v-select
            :class="{'input': true, 'is-invalid': errors.has('producto') }"
            name="producto"
            placeholder="Seleccionar Producto"
            @input="setProductimport"
            :options="optionsProductimport"
            :value="selectedProductimport"></v-select>
        <p v-show="errors.has('producto')" class="text-danger">{{ errors.first('producto') }}</p>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed:{
        ...mapState(['optionsProductimport', 'selectedProductimport']),
        ...mapGetters([])
    },
    methods:{
        ...mapActions(['setProductimport'])
    },
    created(){
        this.$store.dispatch('allProductimports')
    }
}
</script>

<style>

</style>
