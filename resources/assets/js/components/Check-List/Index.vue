<template>

    <div class="col-lg-12">

        <h5 class="text-white">
            Check List

            <a v-if="roleschecklists == 'admin'" href="#" class="btn btn-success pull-right" data-toggle="modal"
                data-target="#CrearFormatoCheckList">
                <i class="fas fa-plus-circle"></i>
            </a>

            <a href="#" class="btn btn-success pull-right" @click.prevent="modalMostrarFormatoCheckList">
                <i class="fas fa-clipboard-check"></i> Formato Check List
            </a>
        </h5>
        <div class="table-responsive">
            <table class="table table-responsive-new table-dark table-sm mt-3">
                <thead>
                    <tr>
                        <th>Patente</th>
                        <th>Kilometraje</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="checklistvehicle in checklistvehicles">
                        <tr class="accordion-toggle" data-toggle="collapse"
                            :data-target="'#checklist_' + checklistvehicle.id">
                            <td data-table-label="Patente">{{ checklistvehicle.patent }}</td>
                            <td colspan="4" data-table-label="Kilometraje">{{ checklistvehicle.km }}</td>
                        </tr>

                        <tr v-for="(checklist, index) in checklistvehicle.checklist" :key="index"
                            :id="'checklist_' + checklistvehicle.id"
                            class="accordian-body collapse bg-secondary">
                            <td data-table-label="ID">{{ index + 1 }}</td>
                            <td data-table-label="Check List">Check List {{ index + 1 }} </td>
                            <td data-table-label="Estado" v-if="checklist.realizado > 0">
                                <a class="btn btn-success btn-sm"><i class="fas fa-check"></i></a>
                            </td>
                            <td data-table-label="Estado" v-else></td>
                            <td data-table-label="Fecha">{{ checklist.created_at | moment('DD/MM/YYYY') }}</td>
                            <td>
                                <a class="btn btn-block btn-info btn-sm" title="Ver"
                                    @click.prevent="modalMostrarCheckListVehicle({ id: checklist.id })">
                                    <i class="fas fa-clipboard-check"></i> Ver
                                </a>
                            </td>
                        </tr>

                    </template>
                </tbody>
            </table>
        </div>
        <CrearFormatoCheckList></CrearFormatoCheckList>
        <MostrarFormatoCheckList></MostrarFormatoCheckList>
        <MostrarCheckListVehicle></MostrarCheckListVehicle>
    </div>

</template>


<script>

import { loadProgressBar } from 'axios-progress-bar'
import CrearFormatoCheckList from './CrearFormatoCheckList'
import MostrarFormatoCheckList from './MostrarFormatoCheckList'
import MostrarCheckListVehicle from './MostrarCheckListVehicle'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: { CrearFormatoCheckList, MostrarFormatoCheckList, MostrarCheckListVehicle },
    computed: {
        ...mapState(['ordenestrabajos', 'checklists', 'trabajos', 'checklistvehicles', 'roleschecklists']),
    },
    methods: {

        ...mapActions(['modalCrearFormatoCheckList', 'modalCrearIntervencionCheckList', 'modalMostrarFormatoCheckList', 'modalMostrarCheckListVehicle', 'getCheckListVehicles', 'getCheckListRoles'])
    },
    created() {
        loadProgressBar();

        this.$store.dispatch('getCheckListVehicles')
        this.$store.dispatch('getCheckListRoles')
    }
}

</script>
