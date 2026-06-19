<template>

    <form action="POST" v-on:submit.prevent="">
        <div id="modalImport" class="modal fade modal-fullscreen" style="overflow-y: auto;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header  bg-dark text-white">
                        <h4>Administrar Importación</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-lg-12">
                                <div id="accordion">
                                    <div class="card">

                                        <div class="card-header p-0" id="headingTwo">
                                            <h5 class="mb-0">
                                                <button id="btn-iternacional-card" class="btn btn-block text-left p-3"
                                                    data-toggle="collapse" data-target="#collapseTwo"
                                                    aria-expanded="true" aria-controls="collapseTwo">
                                                    Costo Internacional (USD) - (dolar : {{ detailImport.dolar }})
                                                    <span class="text-right"><i class="fas fa-arrows-alt-v"></i></span>
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                            data-parent="#accordion">
                                            <div class="card-body">

                                                <div class="row">

                                                    <div class="col-lg-1">
                                                        <label for="product">Dolar</label>
                                                        <input type="number" step="0.01" name="product"
                                                            class="form-control" v-model="detailImport.dolar">
                                                    </div>

                                                    <div class="col-lg-1">
                                                        <label for="transporte">Embarque</label>
                                                        <input type="number" step="0.01" name="transporte"
                                                            class="form-control" v-model="detailImport.embarque">
                                                    </div>

                                                    <div class="col-lg-1">
                                                        <label for="importacion">FEE</label>
                                                        <input type="number" step="0.01" name="importacion"
                                                            class="form-control" v-model="detailImport.fee">
                                                    </div>

                                                    <div class="col-lg-1">
                                                        <label for="importacion">Flete</label>
                                                        <input type="number" step="0.01" name="importacion"
                                                            class="form-control" v-model="detailImport.fleteUsa">
                                                    </div>

                                                    <div class="col-lg-1">
                                                        <label for="bankusa">BankUsa</label>
                                                        <input type="number" step="0.01" name="bankusa"
                                                            class="form-control" v-model="detailImport.bankusa">
                                                    </div>

                                                    <div class="col-lg-1">
                                                        <label for="bankchile">BankChile</label>
                                                        <input type="number" step="0.01" name="bankchile"
                                                            class="form-control" v-model="detailImport.bankchile">
                                                    </div>

                                                    <div class="col-lg-1">
                                                        <label for="transferencia">Transferencia</label>
                                                        <input type="number" step="0.01" name="transferencia"
                                                            class="form-control" v-model="detailImport.transferencia">
                                                    </div>

                                                    <div class="col-lg-1">
                                                        <label for="otro">Otro</label>
                                                        <input type="number" step="0.01" name="otro"
                                                            class="form-control" v-model="detailImport.otro">
                                                    </div>

                                                    <div class="col-lg-3 mt-2">
                                                        <label></label>
                                                        <a href="#" class="btn btn-info form-control"
                                                            @click.prevent="distributionImport" data-toggle="tooltip"
                                                            data-placement="top" title="Distribución">

                                                            Distribuir
                                                        </a>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 mt-3">
                                <div id="accordion">
                                    <div class="card">

                                        <div class="card-header p-0" id="headingThree">
                                            <h5 class="mb-0">
                                                <button id="btn-iternacional-card" class="btn btn-block text-left p-3"
                                                    data-toggle="collapse" data-target="#collapseThree"
                                                    aria-expanded="true" aria-controls="collapseThree">
                                                    Costo Nacional (CLP)
                                                    <span class="text-right"><i class="fas fa-arrows-alt-v"></i></span>
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                            data-parent="#accordion">
                                            <div class="card-body">

                                                <div class="row">

                                                    <div class="col-lg-1">
                                                        <label for="product">Aduana 1</label>
                                                        <input type="number" step="0.01" name="product"
                                                            class="form-control" v-model="detailImportNacional.aduana1">
                                                    </div>

                                                    <div class="col-lg-1">
                                                        <label for="seguro">Aduana 2</label>
                                                        <input type="number" step="0.01" name="seguro"
                                                            class="form-control" v-model="detailImportNacional.aduana2">
                                                    </div>


                                                    <div class="col-lg-1">
                                                        <label for="transporte">Manipuelo</label>
                                                        <input type="number" step="0.01" name="transporte"
                                                            class="form-control"
                                                            v-model="detailImportNacional.manipuleo">
                                                    </div>

                                                    <div class="col-lg-1">
                                                        <label for="importacion">Bodega</label>
                                                        <input type="number" step="0.01" name="importacion"
                                                            class="form-control" v-model="detailImportNacional.bodega">
                                                    </div>

                                                    <div class="col-lg-1">
                                                        <label for="importacion">Guía</label>
                                                        <input type="number" step="0.01" name="importacion"
                                                            class="form-control" v-model="detailImportNacional.guia">
                                                    </div>


                                                    <div class="col-lg-1">
                                                        <label for="importacion">Retiro</label>
                                                        <input type="number" step="0.01" name="importacion"
                                                            class="form-control" v-model="detailImportNacional.retiro">
                                                    </div>

                                                    <div class="col-lg-1">
                                                        <label for="importacion">Flete</label>
                                                        <input type="number" step="0.01" name="importacion"
                                                            class="form-control"
                                                            v-model="detailImportNacional.fleteChile">
                                                    </div>

                                                    <div class="col-lg-2 mt-2">
                                                        <label></label>
                                                        <a href="#" class="btn btn-info form-control"
                                                            @click.prevent="distributionImport" data-toggle="tooltip"
                                                            data-placement="top" title="Distribución">

                                                            Distribuir
                                                        </a>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12 mt-3">
                                <div id="accordion">
                                    <div class="card">

                                        <div class="card-header p-0" id="headingOne">
                                            <h5 class="mb-0">
                                                <button id="btn-detailclient-card-import"
                                                    class="btn btn-block text-left p-3" data-toggle="collapse"
                                                    data-target="#collapseOne" aria-expanded="true"
                                                    aria-controls="collapseOne">
                                                    Nuevo Producto
                                                    <span class="text-right"><i class="fas fa-arrows-alt-v"></i></span>
                                                </button>
                                            </h5>
                                        </div>

                                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                            data-parent="#accordion">
                                            <div class="card-body">

                                                <form action="POST" v-on:submit.prevent="createDetailimport">

                                                    <div class="row">

                                                        <div class="col-lg-3">
                                                            <label for="producto">Producto</label>
                                                            <SelectProductImport></SelectProductImport>
                                                        </div>

                                                        <div class="col-lg-3">
                                                            <label for="producto">Nombre Producto</label>
                                                            <input type="text" name="producto" class="form-control"
                                                                v-model="newDetailimport.product">
                                                        </div>

                                                        <div class="col-lg-2">
                                                            <label for="detalle">Código</label>
                                                            <input type="text" name="detalle" class="form-control"
                                                                v-model="newDetailimport.detail">
                                                        </div>

                                                        <div class="col-lg-1">
                                                            <label for="precio">Valor</label>
                                                            <input type="number" step="0.01" name="precio"
                                                                class="form-control" v-model="newDetailimport.price">
                                                        </div>

                                                        <div class="col-lg-1">
                                                            <label for="cantidad">Cantidad</label>
                                                            <input type="number" name="quantity" class="form-control"
                                                                v-model="newDetailimport.quantity">
                                                        </div>

                                                        <div class="col-lg-1">
                                                            <label for="aditional">% Imp. USA</label>
                                                            <input type="number" step="0.01" name="aditional"
                                                                class="form-control" v-model="newDetailimport.usa">
                                                        </div>

                                                        <div class="col-lg-1">
                                                            <label for="seguro">% Seguro</label>
                                                            <input type="number" step="0.01" name="seguro"
                                                                class="form-control" v-model="newDetailimport.seguro">
                                                        </div>

                                                        <div class="col-lg-1">
                                                            <label for="valorem">Ad Valorem</label>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" value="1"
                                                                    v-model="newDetailimport.valorem">
                                                                <label class="form-check-label"
                                                                    for="inlineRadio1">Si</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" value="0"
                                                                    v-model="newDetailimport.valorem">
                                                                <label class="form-check-label"
                                                                    for="inlineRadio2">No</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">

                                                        <div class="col-lg-1 mt-2">

                                                            <button type="submit" class="btn btn-success form-control">
                                                                Guardar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">

                                <table class="table table-responsive-new table-dark table-sm mt-3">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Producto</th>
                                            <th>Código</th>
                                            <th>Valor Neto (clp)</th>
                                            <th>Cantidad</th>
                                            <th>Usa</th>
                                            <th>Seguro</th>
                                            <th>Adicional (clp)</th>
                                            <th>% (aprox.)</th>
                                            <th>Costo Nac</th>
                                            <th>Costo Inter</th>
                                            <th>Valor Total (clp)</th>
                                            <th>Valor Unitario (clp)</th>
                                            <th>Precio Venta Chile (clp)</th>
                                            <th>Utilidad (clp)</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr v-for="(detailLocal, index) in detailimports" :key="detailLocal.id">
                                            <td data-table-label="ID">{{ index + 1 }}</td>
                                            <td data-table-label="Producto">{{ detailLocal.product }}</td>
                                            <td data-table-label="Código">{{ detailLocal.detail }}</td>
                                            <td data-table-label="Valor Neto (clp)">{{ detailLocal.price_dolar | currency('$', 2, { thousandsSeparator: ',' })}}</td>
                                            <td data-table-label="Cantidad">{{ detailLocal.quantity }}</td>
                                            <td data-table-label="Usa">{{ detailLocal.usa }}</td>
                                            <td data-table-label="Seguro">{{ detailLocal.seguro }}</td>
                                            <td data-table-label="Adicional (clp)">{{ detailLocal.aditional | currency('$', 2, { thousandsSeparator: ',' })}}</td>
                                            <td data-table-label="% (aprox.)">{{ detailLocal.percentage }}</td>
                                            <td data-table-label="Costo Nac">{{ detailLocal.nacional }}</td>
                                            <td data-table-label="Costo Inter">{{ detailLocal.internacional }}</td>
                                            <td data-table-label="Valor Total (clp)">{{ detailLocal.total | currency('$', 2, { thousandsSeparator: ',' }) }}</td>
                                            <td data-table-label="Valor Unitario (clp)">{{ detailLocal.unitario | currency('$', 2, { thousandsSeparator: ',' }) }}</td>
                                            <td data-table-label="Precio Venta Chile (clp)">
                                                <input type="number" step="0.01" class="form-control form-control-sm"
                                                    v-model="detailLocal.valueChile" @keyup="distributionImport">
                                            </td>
                                            <td data-table-label="Utilidad (clp)">
                                                <input type="number" step="0.01" class="form-control form-control-sm"
                                                    v-model="detailLocal.utility" @keyup="getTotalImport">
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-warning btn-sm"
                                                    @click.prevent="editDetailimport({ detailLocal })"
                                                    data-toggle="tooltip" data-placement="top" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <a href="#" class="btn btn-danger btn-sm"
                                                    @click.prevent="deleteDetailimport({ id: detailLocal.id })"
                                                    data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                    <i class="fas fa-ban"></i>
                                                </a>

                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="9"></td>
                                            <td data-table-label="Total nacional" v-if="totalNacional > 0">{{ totalNacional | currency('$', 2, {
                                                thousandsSeparator: ','
                                            }) }}</td>
                                            <td data-table-label="Total internacional" v-if="totalInternacional > 0">{{ totalInternacional | currency('$', 2, {
                                                thousandsSeparator: ','
                                            }) }}</td>
                                            <td data-table-label="total CLP" v-if="totalValue > 0">{{ totalValue | currency('$', 2, {
                                                thousandsSeparator: ','
                                            }) }}</td>
                                            <td></td>
                                            <td data-table-label="total importacion" v-if="totalImport > 0">{{ totalImport | currency('$', 2, {
                                                thousandsSeparator: ','
                                            }) }}</td>
                                            <td data-table-label="total CLP + total importacion" v-if="totalImport > 0">{{ totalImport + totalValue | currency('$', 2, {
                                                thousandsSeparator: ','
                                            }) }}</td>
                                             <td colspan="3"></td>
                                        </tr>

                                        <tr>
                                            <td colspan="11"></td>
                                            <td data-table-label="total importacion IVA" v-if="totalImportIVA > 0">Total + IVA: {{ totalImportIVA | currency('$',
                                                2, { thousandsSeparator: ',' }) }}</td>
                                            <td colspan="5"></td>
                                        </tr>

                                    </tbody>
                                </table>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">


                        <button type="button" class="btn btn-success"
                            @click.prevent="finishDetailimport">Finalizar</button>

                    </div>
                </div>
            </div>
        </div>
    </form>

</template>

<script>

import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapGetters, mapActions } from 'vuex'
import SelectProductImport from '../Product/SelectImport'

export default {
    components: { SelectProductImport },
    computed: {
        ...mapState(['detailimports', 'detailImport', 'detailImportNacional', 'totalNeto',
            'totalNacional', 'totalInternacional', 'totalCosto',
            'totalValue', 'totalImport',
            'totalImportIVA', 'newDetailimport', /*'totalDetailimport',*/ 'errorsLaravel']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions(['createDetailimport', 'editDetailimport', 'deleteDetailimport', 'utilityImport',
            'pdfImport', 'distributionImport', 'sumUtility', 'finishDetailimport', 'getTotalImport'
                    /*, 'sumTotalImport'*/])
    },
}
</script>
