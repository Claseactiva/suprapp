<template>
    <v-select
        name="año"
        placeholder="Seleccionar año..."
        @input="setVYear"
        :disabled="selectedVModel.label==''"
        :options="optionsVYear"
        :value="selectedVYear">
    </v-select>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: { 
        ...mapState(['errorsLaravel', 'optionsVYear', 'selectedVYear', 'selectedVModel']),
    },
    methods: { 
        ...mapActions(['setVYear'])
    }
}
</script>