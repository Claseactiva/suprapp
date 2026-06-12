<template>
    <div>
        <form action="POST" v-on:submit.prevent="updateUtilidadDefect">
            <div class="form-row">
                <div class="form-group col-lg-6">
                    <label for="utilidad">Utilidad</label>
                    <input required type="number" name="utilidad" class="form-control" v-model="newUtilidad.utilidad">
                </div>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-plus-square"></i> Guardar
            </button>
        </form>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['newUtilidad'])
    },
    methods: {
        ...mapActions(['updateUtilidadDefect'])
    },
    created() {
        this.$store.dispatch('getUtilities')
    }
}
</script>
