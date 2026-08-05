<template>
    <div class="pl-0">
        <v-select
            name="actividad_reparacion"
            placeholder="Seleccionar de la biblioteca..."
            @input="setRepairActivity"
            :options="repairActivities"
            :value="selectedRepairActivity"
        />
    </div>
</template>

<script>

import { mapState, mapActions } from 'vuex'

export default {
    computed:{
        ...mapState(['repairActivities', 'selectedRepairActivity'])
    },
    methods:{
        ...mapActions(['setRepairActivity'])
    },
    created(){
        this.$store.dispatch('getRepairActivities')
    }
}
</script>
