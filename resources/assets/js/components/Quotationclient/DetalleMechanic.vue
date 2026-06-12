<template>

    <form action="POST" v-on:submit.prevent="">
        <div id="modalQuotationclientMechanic" class="modal fade modal-fullscreen">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header  bg-dark text-white">
                        <h4>Administrar Cotización Formal</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-lg-12">
                                <!-- <div id="accordion"> -->
                                    <div class="card">

                                        <div class="card-header">
                                            <h5 class="mb-0">Nuevo Producto</h5>
                                        </div>

                                        <!-- <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion"> -->
                                            <div class="card-body">
                                                <form action="POST" v-on:submit.prevent="createDetailclient">

                                                    <div class="row">

                                                        <!-- <div class="col-lg-3">
                                                            <label for="cliente">Producto</label>
                                                            <SelectProduct></SelectProduct>
                                                        </div> -->

                                                        <div class="col-lg-3">
                                                            <label for="product">Nombre Producto</label>
                                                            <input 
                                                                type="text"
                                                                name="product"
                                                                class="form-control"
                                                                list="quotation-product-suggestions-mechanic"
                                                                autocomplete="off"
                                                                v-model="newDetailclient.product"
                                                            >
                                                            <datalist id="quotation-product-suggestions-mechanic">
                                                                <option
                                                                    v-for="suggestion in filteredModelProductSuggestions"
                                                                    :key="suggestion.product_key"
                                                                    :value="suggestion.product_name"
                                                                    :label="suggestion.display_label">
                                                                </option>
                                                            </datalist>
                                                        </div>

                                                         <!-- <div class="col-lg-3">
                                                            <label for="product">Código</label>
                                                            <input type="text" name="product" class="form-control"
                                                            v-model="newDetailclient.detail">
                                                        </div> -->

                                                        <div class="col-lg-1">
                                                            <label for="precio">Precio</label>
                                                            <input type="number" name="precio" class="form-control"
                                                                v-model="newDetailclient.price"
                                                                @keyup="sumTotalProductMechanic">
                                                        </div>

                                                        <!-- <div class="col-lg-1">
                                                            <label for="cantidad">Cantidad</label>
                                                            <input type="number" name="quantity" class="form-control"
                                                                v-model="newDetailclient.quantity"
                                                                @keyup="sumTotalProduct">
                                                        </div> -->

                                                        <!-- <div class="col-lg-1">
                                                            <label for="percentage">%</label>
                                                            <input type="number" name="percentage" class="form-control"
                                                                v-model="newDetailclient.percentage"
                                                                @keyup="sumTotalProduct">
                                                        </div> -->

                                                        <!-- <div class="col-lg-1">
                                                            <label for="aditional">Adicional</label>
                                                            <input type="number" name="aditional" class="form-control"
                                                                v-model="newDetailclient.aditional"
                                                                @keyup="sumTotalProduct">
                                                        </div>

                                                        <div class="col-lg-1">
                                                            <label for="transport">Flete</label>
                                                            <input type="number" name="transport" class="form-control"
                                                                v-model="newDetailclient.transport"
                                                                @keyup="sumTotalProduct">
                                                        </div>

                                                        <div class="col-lg-1">
                                                            <label for="utilidad">Utilidad</label>
                                                            <input type="number" name="utilidad" class="form-control"
                                                                v-model="newDetailclient.utility" disabled>
                                                        </div> -->

                                                        <div class="col-lg-2">
                                                            <label for="plazo">Días de Plazo</label>
                                                            <select name="plazo" class="form-control" v-model="newDetailclient.days">
                                                                <option v-for="deliveryTime in availableDeliveryTimes(newDetailclient.days)"
                                                                    :key="deliveryTime.id || deliveryTime.label" :value="deliveryTime.label">
                                                                    {{ deliveryTime.label }}
                                                                </option>
                                                            </select>
                                                        </div>

                                                        <div class="col-lg-2">
                                                            <label for="total">Total</label>
                                                            <input type="number" name="total" class="form-control"
                                                                v-model="newDetailclient.total" disabled>
                                                        </div>

                                                        <div class="col-lg-2 mt-2">
                                                            <label></label>
                                                            <button type="submit" class="btn btn-success form-control">
                                                                <i class="fas fa-plus-square"></i> Guardar
                                                            </button>
                                                        </div>

                                                    </div>

                                                </form>
                                            </div>
                                        <!-- </div> -->
                                    </div>
                                <!-- </div> -->
                            </div>

                            <div class="col-lg-12 table-responsive">

                                <table class="table table-responsive-new table-dark table-sm mt-3">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Producto</th>
                                            <th>Plazo</th>
                                            <th>Valor Neto ($)</th>
                                            <th>Total Neto ($)</th>
                                            <th>Total + IVA ($)</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr v-for="(detailLocal, index) in detailclients" :key="detailLocal.id">
                                            <td data-table-label="ID">{{ index + 1 }}</td>
                                            <td data-table-label="PRODUCTO">{{ detailLocal.product }}</td>
                                            <td data-table-label="PLAZO">{{ detailLocal.days }}</td>
                                            <td data-table-label="Valor Neto ($)">{{ detailLocal.price | currency('', 0, { thousandsSeparator: '.' }) }}</td>
                                            <td data-table-label="Total Neto ($)">{{ detailLocal.total | currency('', 0, { thousandsSeparator: '.' }) }}</td>
                                            <td data-table-label="Total + IVA ($)"><b>{{ Math.round(detailLocal.total * 1.19) | currency('', 0, { thousandsSeparator: '.' }) }}</b></td>

                                            <td>
                                                <a href="#" class="btn btn-warning btn-sm"
                                                    @click.prevent="editDetailclientMechanic( { detailLocal } )"
                                                    data-toggle="tooltip"
                                                    data-placement="top"
                                                    title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <a href="#" class="btn btn-danger btn-sm"
                                                    @click.prevent="deleteDetailclient( { id: detailLocal.id } )"
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
                                            <td></td>
                                            <td></td>
                                            <td data-table-label="Total">{{ totalQuotationclient | currency('', 0, { thousandsSeparator: '.' }) }}</td>
                                            <td data-table-label="Total + IVA">{{ totalQuotationclientIVA | currency('', 0, { thousandsSeparator: '.' }) }}</td>
                                                
                                        </tr>
                                    </tbody>
                                </table>

                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-danger" href="#" role="button"
                            @click.prevent="pdfQuotationclient">
                            <i class="far fa-file-pdf"></i> PDF
                        </a>

                        <a class="btn btn-danger" href="#" role="button"
                            @click.prevent="pdfIvaQuotationclient">
                            <i class="far fa-file-pdf"></i> PDF IVA Incluido
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </form>

</template>

<script>

import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapGetters, mapActions } from 'vuex'
import SelectProduct from '../Product/SelectProduct'

export default {
    components: { SelectProduct },
    computed:{
        ...mapState(['detailclients', 'totalQuotationclient', 'totalUtilidad', 'totalTransporte', 'totalAdicional',
                    'totalQuotationclientIVA', 'newDetailclient', 'totalDetailclient', 'errorsLaravel', 'deliveryTimes', 'modelProductSuggestions']),
        ...mapGetters([]),
        filteredModelProductSuggestions() {
            const term = (this.newDetailclient.product || '').trim().toLowerCase()

            if (term === '') {
                return this.modelProductSuggestions.slice(0, 20)
            }

            return this.modelProductSuggestions.filter((suggestion) => {
                const haystack = `${suggestion.product_name} ${suggestion.product_code || ''}`.toLowerCase()
                return haystack.includes(term)
            }).slice(0, 20)
        }
    },
    methods:{
        ...mapActions(['createDetailclient', 'editDetailclientMechanic', 'deleteDetailclient',
                    'pdfQuotationclient', 'pdfIvaQuotationclient', 'sumTotalProductMechanic']),
        availableDeliveryTimes(currentValue) {
            const options = [...this.deliveryTimes]

            if (currentValue && !options.some(deliveryTime => deliveryTime.label === currentValue)) {
                options.unshift({
                    id: `current-${currentValue}`,
                    label: currentValue
                })
            }

            return options
        }
    },
}
</script>

