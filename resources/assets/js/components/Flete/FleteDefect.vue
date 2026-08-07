<template>
    <div>
        <form action="POST" v-on:submit.prevent="updateFleteDefect">
            <div class="form-row align-items-end">
                <div class="form-group col-lg-2">
                    <label for="flete">Flete</label>
                    <input required type="number" name="flete" class="form-control" v-model="newFlete.flete">
                </div>
                <div class="form-group col-lg-auto">
                    <small class="text-muted d-block mb-2">Valor actual: <strong>{{ currentValueLabel }}</strong></small>
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
        ...mapState(['newFlete', 'currentFlete']),
        currentValueLabel() {
            return this.currentFlete !== null ? this.currentFlete : 'sin definir'
        }
    },
    methods: {
        ...mapActions(['updateFleteDefect'])
    },
    created(){
        this.$store.dispatch('getFletes')
    }
}
</script>