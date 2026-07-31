<template>

    <div class="col-lg-12 orden-trabajo-admin">

        <h5 class="text-white">
            Ordenes de trabajo
        </h5>
        <div class="orden-trabajo-table-shell mt-3">
            <table class="table table-responsive-new table-dark table-sm orden-trabajo-table mb-0">
                <thead>
                    <tr>
                        <th class="orden-col-id">ID</th>
                        <th class="orden-col-patente">Patente</th>
                        <th class="orden-col-km">Kilometraje</th>
                        <th class="orden-col-fecha">Fecha</th>
                        <th class="orden-col-actions">Trabajos</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="ordentrabajoLocal in ordenestrabajos">
                        <tr data-toggle="collapse" :data-target="'#orden_trabajo_' + ordentrabajoLocal.id"
                            class="accordion-toggle">
                            <td data-table-label="ID" class="orden-col-id">{{ ordentrabajoLocal.id }}</td>
                            <td data-table-label="Patente" class="orden-col-patente orden-cell-strong" :title="ordentrabajoLocal.vehicle.patent">{{ ordentrabajoLocal.vehicle.patent }}</td>
                            <td data-table-label="Kilometraje" class="orden-col-km orden-cell-meta">{{ ordentrabajoLocal.km }}</td>
                            <td data-table-label="Fecha" class="orden-col-fecha orden-cell-meta">{{ ordentrabajoLocal.updated_at | moment('DD/MM/YYYY') }}</td>

                            <td class="orden-col-actions orden-action-cell">
                                <a class="btn btn-info btn-sm" title="Editar">
                                    <i class="fas fa-info"></i>
                                </a>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="12" class="p-0">
                                <div class="accordian-body collapse" :id="'orden_trabajo_' + ordentrabajoLocal.id">
                                    <div class="orden-trabajo-subtable-shell">
                                    <table class="table table-responsive-new orden-trabajo-subtable m-0">
                                        <thead>
                                            <tr>
                                                <th class="sub-col-id">ID</th>
                                                <th class="sub-col-desc">Descripcion Del Trabajo</th>
                                                <th class="sub-col-km">Kilometraje</th>
                                                <th class="sub-col-fecha">Fecha</th>
                                                <th class="sub-col-realizado">Realizado</th>
                                                <th class="sub-col-actions"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="trabajo in ordentrabajoLocal.trabajo" :key="trabajo.id">
                                                <td data-table-label="ID" class="sub-col-id">{{ trabajo.id }}</td>
                                                <td data-table-label="Descripcion Del Trabajo" class="sub-col-desc orden-cell-wrap" :title="trabajo.descripcion">{{ trabajo.descripcion }}</td>
                                                <td data-table-label="Kilometraje" class="sub-col-km orden-cell-meta">{{ trabajo.km }}</td>

                                                <td data-table-label="Fecha" class="sub-col-fecha orden-cell-meta">{{ trabajo.created_at | moment('DD/MM/YYYY') }}
                                                </td>
                                                <td data-table-label="Realizado" class="sub-col-realizado orden-cell-meta" v-if="trabajo.realizado > 0">
                                                    <button class="btn btn-success btn-sm"><i
                                                            class="fas fa-check"></i></button>
                                                </td>
                                                <td data-table-label="Realizado" class="sub-col-realizado orden-cell-meta" v-else>
                                                    <input type="checkbox" :id="trabajo.id" :value="trabajo.id"
                                                        v-model="checkRealizado">
                                                    <label :for="trabajo.id"></label>
                                                </td>
                                                <td class="sub-col-actions orden-action-cell">
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

<style>
.orden-trabajo-table-shell,
.orden-trabajo-subtable-shell {
    overflow-x: visible;
}

.orden-cell-strong,
.orden-cell-wrap {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.orden-cell-strong {
    font-weight: 600;
}

.orden-cell-meta {
    font-size: 0.78rem;
    text-align: center;
}

.orden-action-cell {
    display: flex;
    flex-wrap: nowrap;
    justify-content: center;
    align-items: center;
    gap: 0.25rem;
    white-space: nowrap;
}

.orden-action-cell .btn {
    margin: 0;
    width: 28px;
    height: 28px;
    min-width: 28px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
}

@media (min-width: 769px) {
    .orden-trabajo-admin .orden-trabajo-table,
    .orden-trabajo-admin .orden-trabajo-subtable {
        table-layout: fixed;
        width: 100%;
    }

    .orden-trabajo-admin .orden-trabajo-table th,
    .orden-trabajo-admin .orden-trabajo-table td,
    .orden-trabajo-admin .orden-trabajo-subtable th,
    .orden-trabajo-admin .orden-trabajo-subtable td {
        padding: 0.28rem 0.32rem !important;
        font-size: 0.74rem;
        white-space: nowrap !important;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .orden-trabajo-admin .orden-col-id,
    .orden-trabajo-admin .sub-col-id {
        width: 3rem;
    }

    .orden-trabajo-admin .orden-col-patente {
        width: 25%;
    }

    .orden-trabajo-admin .orden-col-km,
    .orden-trabajo-admin .sub-col-km {
        width: 6rem;
    }

    .orden-trabajo-admin .orden-col-fecha,
    .orden-trabajo-admin .sub-col-fecha {
        width: 6.5rem;
    }

    .orden-trabajo-admin .orden-col-actions {
        width: 5rem;
        overflow: visible !important;
        text-overflow: clip !important;
    }

    .orden-trabajo-admin .sub-col-desc {
        width: 40%;
    }

    .orden-trabajo-admin .sub-col-realizado {
        width: 5.5rem;
    }

    .orden-trabajo-admin .sub-col-actions {
        width: 6rem;
        overflow: visible !important;
        text-overflow: clip !important;
    }
}
</style>
