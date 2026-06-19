<template>
    <div>
        <p class="font-weight-bold" v-show="eliminar">Seguro de eliminar la observacion?</p>
        <button type="button" class="btn btn-secondary" v-show="eliminar" @click.prevent="botonNo()">No</button>
        <button type="button" class="btn btn-danger" v-show="eliminar" @click.prevent="botonSi(data)">Si</button>
        <button type="button" class="btn btn-danger" v-show="esconderBotonEliminar"
            @click.prevent="botonEliminar()">Eliminar</button>
    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex';
export default {
    props: ['data'],
    data() {
        return {
            eliminar: false,
            esconderBotonEliminar: true
        }
    },
    computed: {
        ...mapState([]),
        ...mapGetters([]),
    },
    methods: {
        ...mapActions(['botonSi']),
        botonEliminar() {
            this.eliminar = true
            this.esconderBotonEliminar = false
        },
        botonNo() {
            this.eliminar = false
            this.esconderBotonEliminar = true
        },
    },
}
</script>