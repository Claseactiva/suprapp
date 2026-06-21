<template>

    <form>
        <div id="detalleCliente" class="modal fade">
            <div class="modal-dialog modal-fullscreen">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Formulario de la cotizacion</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive" v-for="quotationLocalForm in quotationforms"
                            :key="quotationLocalForm.id">
                            <table class="table table-bordered table-striped mt-3 text-white bg-dark">
                                <tbody>

                                    <tr>
                                        <th>Cliente</th>
                                        <td>{{ quotationLocalForm.name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Telefono</th>
                                        <td>{{ quotationLocalForm.phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>Pago</th>
                                        <td>{{ quotationLocalForm.payment }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered table-striped mt-3 text-white bg-dark">
                                <tbody>

                                    <tr>
                                        <th>Patente o Chasis</th>
                                        <td>{{ quotationLocalForm.patentchasis }}</td>
                                    </tr>
                                    <tr>
                                        <th>Marca</th>
                                        <td>{{ quotationLocalForm.brand }}</td>
                                    </tr>
                                    <tr>
                                        <th>Modelo</th>
                                        <td>{{ quotationLocalForm.model }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ano</th>
                                        <td>{{ quotationLocalForm.year }}</td>
                                    </tr>
                                    <tr>
                                        <th>Descripcion</th>
                                        <td>{{ quotationLocalForm.description }}</td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['quotationforms']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions([])
    },
}
</script>