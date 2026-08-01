<template>
    <div class="col-lg-12">

        <h5 class="text-white">
            Opciones de Cantidad
        </h5>

        <p class="text-white-50">
            Estos son los numeros disponibles para asignar "cantidad de vehiculos" a un usuario o como
            default de un rol. Agregalos o quitalos aqui.
        </p>

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap" style="gap: 0.5rem;">
                    <span class="badge badge-secondary p-2" v-for="opt in cantidadVehiculoOptions" :key="opt.id">
                        {{ opt.value }}
                        <a href="#" class="text-danger ml-1" title="Eliminar"
                            @click.prevent="deleteCantidadVehiculoOption(opt.id)">
                            <i class="fas fa-times"></i>
                        </a>
                    </span>
                    <span v-if="cantidadVehiculoOptions.length === 0" class="text-muted">
                        No hay opciones registradas.
                    </span>
                </div>

                <div class="input-group mt-3" style="max-width: 320px;">
                    <input type="number" min="1" class="form-control" placeholder="Nueva cantidad"
                        v-model="newCantidadVehiculoOption">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-success" @click.prevent="createCantidadVehiculoOption">
                            <i class="fas fa-plus"></i> Agregar
                        </button>
                    </div>
                </div>
                <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                    <p>{{ error.value }}</p>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['cantidadVehiculoOptions', 'errorsLaravel']),
        newCantidadVehiculoOption: {
            get() {
                return this.$store.state.newCantidadVehiculoOption
            },
            set(value) {
                this.$store.state.newCantidadVehiculoOption = value
            }
        }
    },
    methods: {
        ...mapActions(['getCantidadVehiculoOptions', 'createCantidadVehiculoOption', 'deleteCantidadVehiculoOption'])
    },
    created() {
        loadProgressBar()
        this.getCantidadVehiculoOptions()
    }
}
</script>
