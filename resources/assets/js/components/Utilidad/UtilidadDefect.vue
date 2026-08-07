<template>
    <div>
        <form action="POST" v-on:submit.prevent="updateUtilidadDefect">
            <div class="form-row align-items-end">
                <div class="form-group col-lg-2">
                    <label for="utilidad">Utilidad</label>
                    <input required type="number" name="utilidad" class="form-control" v-model="newUtilidad.utilidad">
                </div>
                <div class="form-group col-lg-auto">
                    <small class="text-muted d-block mb-2">Valor actual: <strong>{{ currentValueLabel }}</strong></small>
                </div>
            </div>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-plus-square"></i> Guardar
            </button>
        </form>

        <div v-if="!newUtilidad.utilidad" class="mt-2">
            <small class="text-muted d-block mb-1">Sugerencias:</small>
            <button v-for="suggestion in suggestions" :key="suggestion" type="button"
                class="btn btn-outline-secondary btn-sm mr-2 mb-2" @click.prevent="useSuggestion(suggestion)">
                {{ suggestion }}%
            </button>
        </div>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    data() {
        return {
            suggestions: [20, 30, 40]
        }
    },
    computed: {
        ...mapState(['newUtilidad', 'currentUtilidad']),
        currentValueLabel() {
            return this.currentUtilidad !== null ? this.currentUtilidad + '%' : 'sin definir'
        }
    },
    methods: {
        ...mapActions(['updateUtilidadDefect']),
        useSuggestion(value) {
            this.newUtilidad.utilidad = value
            this.updateUtilidadDefect()
        }
    },
    created() {
        this.$store.dispatch('getUtilities')
    }
}
</script>
