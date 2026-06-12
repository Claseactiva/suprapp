<template>
    <v-select
        name="año"
        placeholder="Seleccionar año..."
        @input="setYM"
        :disabled="selectedMM.label==''"
        :options="optionsYM"
        :value="selectedYM">
    </v-select>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: { 
        ...mapState(['errorsLaravel', 'optionsYM', 'selectedYM', 'selectedMM']),
    },
    methods: { 
        ...mapActions(['setYM'])
    }
}
</script>