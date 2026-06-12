<template>
    <div>
        <div class="mb-3">

            <p class="font-weight-bold">Observación</p>
            <p class="text-justify">{{ observaciones.observacion }}</p>
            <hr>
            <p class="font-weight-bold">Foto</p>
            <template v-for="imagen in observaciones.imagenes">
                <img :src="formatImage(imagen.imagen)" class="img-thumbnail" :alt="imagen.imagen">
            </template>
        </div>


        <button type="button" class="btn btn-secondary" @click.prevent="cerrarMostrarObservacion">
            <i class="fas fa-arrow-left"></i> Volver
        </button>

    </div>
</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex';
import { formatImage } from '../../utils/imageUtils';
export default {
    components: {},
    computed: {
        ...mapState(['observaciones']),
        ...mapGetters([]),
    },
    methods: {
        ...mapActions(['cerrarMostrarObservacion']),
        formatImage
    },
}
</script>
