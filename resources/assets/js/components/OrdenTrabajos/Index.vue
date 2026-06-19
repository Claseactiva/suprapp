<template>

    <div class="col-lg-12">

        <h5 class="text-white">
            Ordenes de trabajo
        </h5>
        <div class="table-responsive">
            <table class="table table-responsive-new table-dark table-sm mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patente</th>
                        <th>Kilometraje</th>
                        <th>Fecha</th>
                        <th>Trabajos</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="ordentrabajoLocal in ordenestrabajos">
                        <tr data-toggle="collapse" :data-target="'#orden_trabajo_' + ordentrabajoLocal.id"
                            class="accordion-toggle">
                            <td data-table-label="ID">{{ ordentrabajoLocal.id }}</td>
                            <td data-table-label="Patente">{{ ordentrabajoLocal.vehicle.patent }}</td>
                            <td data-table-label="Kilometraje">{{ ordentrabajoLocal.km }}</td>
                            <td data-table-label="Fecha">{{ ordentrabajoLocal.updated_at | moment('DD/MM/YYYY') }}</td>

                            <td>
                                <a class="btn btn-info btn-sm" title="Editar">
                                    <i class="fas fa-info"></i>
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="12" class="p-0">
                                <div class="accordian-body collapse" :id="'orden_trabajo_' + ordentrabajoLocal.id">
                                    <table class="table m-0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Descripcion Del Trabajo</th>
                                                <th>Kilometraje</th>
                                                <th>Fecha</th>
                                                <th>Realizado</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="trabajo in ordentrabajoLocal.trabajo" :key="trabajo.id">
                                                <td data-table-label="ID">{{ trabajo.id }}</td>
                                                <td data-table-label="Descripcion Del Trabajo">{{ trabajo.descripcion }}</td>
                                                <td data-table-label="Kilometraje">{{ trabajo.km }}</td>

                                                <td data-table-label="Fecha">{{ trabajo.created_at | moment('DD/MM/YYYY') }}
                                                </td>
                                                <td data-table-label="Realizado" v-if="trabajo.realizado > 0">
                                                    <button class="btn btn-success btn-sm"><i
                                                            class="fas fa-check"></i></button>
                                                </td>
                                                <td data-table-label="Realizado" v-else>
                                                    <input type="checkbox" :id="trabajo.id" :value="trabajo.id"
                                                        v-model="checkRealizado">
                                                    <label :for="trabajo.id"></label>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-success btn-sm"
                                                        v-if="trabajo.realizado === 0"
                                                        @click.prevent="modalFotosOrdenTrabajo({ id: trabajo.id })"
                                                        title="Fotos">
                                                        <i class="fas fa-camera-retro"></i>
                                                    </a>

                                                    <a href="#" class="btn btn-warning btn-sm"
                                                        v-if="trabajo.realizado === 0"
                                                        @click.prevent="modalObservacion({ id: trabajo.id })"
                                                        title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <AgregarFotosOrdenTrabajo></AgregarFotosOrdenTrabajo>
        <AgregarObservacion></AgregarObservacion>
        <AlertaInformacion></AlertaInformacion>
    </div>

</template>


<script>

import { loadProgressBar } from 'axios-progress-bar'
import AgregarFotosOrdenTrabajo from './AgregarFotosOrdenTrabajo'
import AgregarObservacion from './AgregarObservacion'
import { mapState, mapActions, mapGetters } from 'vuex'
import AlertaInformacion from './AlertaInformacion.vue'

export default {
    components: { AgregarFotosOrdenTrabajo, AgregarObservacion, AlertaInformacion },
    computed: {
        ...mapState(['ordenestrabajos', 'trabajos', 'checkRealizado', 'cerrarObservacion']),
        ...mapGetters(['isActived', 'pagesNumber']),
        checkRealizado: {
            get() {
                return this.$store.state.checkRealizado
            },
            set(value) {
                this.$store.commit('setcheckRealizado', value)
            }
        }
    },
    methods: {
        ...mapActions(['getOrdenesTrabajos', 'modalFotosOrdenTrabajo', 'modalObservacion', 'removeTrabajo'])
    },
    created() {
        loadProgressBar();
        this.$store.dispatch('getOrdenesTrabajos')
    }
}

</script>

<style></style>
