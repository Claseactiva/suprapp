<template>

    <form>
        <div id="detail" class="modal fade">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Detalle</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped mt-3 table-sm text-white bg-dark">
                            <tbody>

                                <tr>
                                    <td>Cliente</td>
                                    <td>{{ client.name }}</td>
                                </tr>
                                <tr>
                                    <td>Rut</td>
                                    <td>{{ client.rut }}</td>
                                </tr>
                                <tr>
                                    <td>Razón Social</td>
                                    <td>{{ client.razonSocial }}</td>
                                </tr>
                                <tr>
                                    <td>Giro</td>
                                    <td>{{ client.giro }}</td>
                                </tr>
                                <tr>
                                    <td>Comuna</td>
                                    <td>{{ client.comuna }}</td>
                                </tr>
                                <tr>
                                    <td>Dirección</td>
                                    <td>{{ client.address }}</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td>{{ client.email }}</td>
                                </tr>
                                <tr>
                                    <td>Teléfono</td>
                                    <td>{{ client.phone }}</td>
                                </tr>

                            </tbody>
                        </table>

                        <table class="table table-bordered table-striped mt-3 table-sm text-white bg-dark">
                            <thead>
                                <th>Actividades</th>
                            </thead>
                            <tbody>
                                <tr  v-for="actividad in client.activities" :key="actividad.id">
                                    <td>{{ actividad.name }}</td>
                                </tr>
                            </tbody>
                        </table>

                        </div>

                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>
    </form>

</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex';

export default {
    computed:{
        ...mapState(['client']),
        ...mapGetters([])
    },
    methods:{
        ...mapActions([])
    },
}
</script>
