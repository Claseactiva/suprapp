<template>

    <form action="POST" v-on:submit.prevent="">
        <div id="modalQuotation" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header  bg-dark text-white">
                        <h4>Administrar Cotización</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-lg-12 table-responsive">

                                <table class="table table-bordered table-striped mt-3 table-sm">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Producto</th>
                                            <th>Valor Neto</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td></td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm"
                                                        v-model="newDetail.product">
                                            </td>
                                            <td>
                                                <!--<input type="text" class="form-control form-control-sm"
                                                        v-model="searchCity.name" @keyup="getCities">-->
                                                <input type="number" class="form-control form-control-sm"
                                                        v-model="newDetail.price">
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-success btn-sm"
                                                    @click.prevent="createDetail"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Crear">
                                                    <i class="fas fa-plus-square"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <tr v-for="(detailLocal, index) in details" :key="detailLocal.id">
                                            <td width="10px">{{ index + 1 }}</td>
                                            <td>{{ detailLocal.product }}</td>
                                            <td>{{ detailLocal.price }}</td>
                                            <td>

                                                <a href="#" class="btn btn-danger btn-sm"
                                                    @click.prevent="deleteDetail( { id: detailLocal.id } )"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Eliminar">
                                                    <i class="fas fa-ban"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>Total: {{ totalQuotation }}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td>Total + IVA: {{ totalQuotationIVA }}</td>
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-danger"
                            @click.prevent="pdfQuotation">
                            <i class="far fa-file-pdf"></i> PDF
                        </a>
                        <!--<a class="btn btn-danger"
                            href="http://localhost:8000/pdf-quotation/5">
                            <i class="far fa-file-pdf"></i> PDF
                        </a>-->

                        <!--<a class="btn btn-info">
                            <i class="far fa-envelope"></i> Enviar Cotización
                        </a>-->

                        <!--button type="submit" class="btn btn-warning">
                            <i class="fas fa-pencil-alt"></i> Editar
                        </button>-->

                    </div>
                </div>
            </div>
        </div>
    </form>

</template>

<script>

import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapGetters, mapActions } from 'vuex';

export default {
    components: {  },
    computed:{
        ...mapState(['details', 'totalQuotation', 'totalQuotationIVA', 'newDetail', 'errorsLaravel']),
        ...mapGetters([])
    },
    methods:{
        ...mapActions(['createDetail', 'deleteDetail', 'pdfQuotation'])
    },
}
</script>

