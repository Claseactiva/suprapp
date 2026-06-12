<template>
    <div id="modalQuotationclient" class="modal fade modal-fullscreen">
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
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Nuevo Producto</h5>
                                </div>

                                <div class="card-body">
                                    <form action="POST" v-on:submit.prevent="createDetailclient">

                                        <div class="row mb-3">

                                            <div class="col-lg-4 col-md-12">
                                                <label for="cliente">Producto</label>
                                                <SelectProduct></SelectProduct>
                                            </div>

                                            <div class="col-lg-4 col-md-12">
                                                <label for="product">Nombre Producto</label>
                                                <input required type="text" name="product" class="form-control"
                                                    v-model="newDetailclient.product">
                                            </div>

                                            <div class="col-lg-4 col-md-12">
                                                <label for="product">Código</label>
                                                <input type="text" name="product" class="form-control"
                                                    v-model="newDetailclient.detail">
                                            </div>
                                        </div>
                                        <div class="row">

                                            <div class="col-lg-2 col-md-12">
                                                <label for="precio">Precio</label>
                                                <input type="number" name="precio" class="form-control"
                                                    v-model="newDetailclient.price" @keyup="sumTotalProduct" required>

                                            </div>

                                            <div class="col-lg-1 col-md-12">
                                                <label for="cantidad">Cantidad</label>
                                                <input type="number" name="quantity" class="form-control" required
                                                    v-model="newDetailclient.quantity" @keyup="sumTotalProduct" min="0">
                                            </div>

                                            <div class="col-lg-1 col-md-12">
                                                <label for="percentage">%</label>
                                                <input type="number" name="percentage" class="form-control"
                                                    v-model="newDetailclient.percentage" @keyup="sumTotalProduct"
                                                    min="0">
                                            </div>

                                            <div class="col-lg-1 col-md-12">
                                                <label for="aditional">Adicional</label>
                                                <input type="number" name="aditional" class="form-control"
                                                    v-model="newDetailclient.aditional" @keyup="sumTotalProduct"
                                                    min="0">
                                            </div>

                                            <div class="col-lg-1 col-md-12">
                                                <label for="transport">Flete</label>
                                                <input type="number" name="transport" class="form-control"
                                                    v-model="newDetailclient.transport" @keyup="sumTotalProduct"
                                                    min="0">
                                            </div>

                                            <div class="col-lg-1 col-md-12">
                                                <label for="utilidad">Utilidad</label>
                                                <input type="number" name="utilidad" class="form-control"
                                                    v-model="newDetailclient.utility" min="0" disabled>
                                            </div>

                                            <div class="col-lg-2 col-md-12">
                                                <label for="plazo">Días de Plazo</label>
                                                <select name="plazo" class="form-control" v-model="newDetailclient.days">
                                                    <option v-for="deliveryTime in availableDeliveryTimes(newDetailclient.days)"
                                                        :key="deliveryTime.id || deliveryTime.label" :value="deliveryTime.label">
                                                        {{ deliveryTime.label }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-lg-2 col-md-12">
                                                <label for="total">Total</label>
                                                <input type="number" name="total" class="form-control"
                                                    v-model="newDetailclient.total" min="0" disabled>
                                            </div>

                                            <div class="col-lg-1 col-md-12 mt-2">
                                                <label></label>
                                                <button type="submit" class="btn btn-success form-control">
                                                    <i class="fas fa-plus-square"></i> Agregar
                                                </button>
                                            </div>

                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 table-responsive">
                            <table class="table table-responsive-new table-dark table-sm mt-3">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Producto</th>
                                        <th>Código</th>
                                        <th>Plazo</th>
                                        <th>Valor Neto ($)</th>
                                        <th>Cantidad</th>
                                        <th>Porcentaje</th>
                                        <th>Adicional ($)</th>
                                        <th>Transporte ($)</th>
                                        <th>Utilidad ($)</th>
                                        <th>Total Neto ($)</th>
                                        <th>Total + IVA ($)</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="(detailLocal, index) in detailclients" :key="detailLocal.id">
                                        <td data-table-label="ID">{{ index + 1 }}</td>
                                        <td data-table-label="Producto">{{ detailLocal.product }}</td>
                                        <td data-table-label="Código">{{ detailLocal.detail }}</td>
                                        <td data-table-label="Plazo">{{ detailLocal.days }}</td>
                                        <td data-table-label="Valor Neto ($)">{{ formatPrice(detailLocal.price) }}</td>
                                        <td data-table-label="Cantidad">{{ detailLocal.quantity }}</td>
                                        <td data-table-label="Porcentaje">{{ detailLocal.percentage + '%' }}</td>
                                        <td data-table-label="Adicional ($)">{{ formatPrice(detailLocal.aditional) }}</td>
                                        <td data-table-label="Transporte ($)">{{ formatPrice(detailLocal.transport) }}</td>
                                        <td data-table-label="Utilidad ($)">{{ formatPrice(detailLocal.utility) }}</td>
                                        <td data-table-label="Total Neto ($)">{{ formatPrice(detailLocal.price_neto) }}</td>
                                        <td data-table-label="Total + IVA ($)"><b>{{ formatPrice(detailLocal.total) }}</b></td>
                                        <td>
                                            <a href="#" class="btn btn-warning btn-sm"
                                                @click.prevent="editDetailclient({ detailLocal })" data-toggle="tooltip"
                                                data-placement="top" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="#" class="btn btn-danger btn-sm"
                                                @click.prevent="deleteDetailclient({ id: detailLocal.id })"
                                                data-toggle="tooltip" data-placement="top" title="Eliminar">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td data-table-label="Total Adicionales"><b>{{ formatPrice(totalAdicional) }}</b></td>
                                        <td data-table-label="Total Transporte"><b>{{ formatPrice(totalTransporte) }}</b></td>
                                        <td data-table-label="Total Utilidad"><b>{{ formatPrice(totalUtilidad) }}</b></td>
                                        <td data-table-label="Total Neto"><b>{{ formatPrice(totalQuotationclient) }}</b></td>
                                        <td data-table-label="Total + IVA ($)"><b>{{ formatPrice(totalQuotationclientIVA) }}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-lg-12" v-show="checkedSpareParts">
                            <form action="POST" v-on:submit.prevent="saveSparePart" class="row">
                                <div class="col-12 mb-2">
                                    <h5>Solicitar Repuestos</h5>
                                    <textarea required class="form-control" id="spare_parts" rows="5"
                                        v-model="newDetailclient.spare_parts"></textarea>
                                </div>

                                <div class="col-1">
                                    <button type="submit" class="btn btn-success form-control">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <div>
                        <input type="checkbox" :id="idQuotationclient" v-model="checkedSpareParts">
                        <label :for="idQuotationclient" for="spare_parts">Repuestos</label>
                    </div>
                    <div v-if="detailclients.length > 0">
                        <a class="btn btn-danger" href="#" role="button" @click.prevent="pdfQuotationclient">
                            <i class="far fa-file-pdf"></i> PDF
                        </a>

                        <a class="btn btn-danger" href="#" role="button" @click.prevent="pdfIvaQuotationclient">
                            <i class="far fa-file-pdf"></i> PDF IVA Incluido
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapGetters, mapActions, mapMutations } from 'vuex'
import SelectProduct from '../Product/SelectProduct'

export default {
    components: { SelectProduct },
    computed: {
        ...mapState(['detailclients', 'totalQuotationclient', 'totalUtilidad', 'totalTransporte', 'totalAdicional',
            'totalQuotationclientIVA', 'newDetailclient', 'totalDetailclient', 'totalProductIvaFlete', 'errorsLaravel', 'idQuotationclient', 'deliveryTimes']),
        ...mapGetters([]),
        checkedSpareParts: {
            get() {
                return this.$store.state.checkedSpareParts
            },
            set(value) {
                this.$store.commit('setCheckedSpareParts', value)
            }
        },
    },
    methods: {
        ...mapActions(['createDetailclient', 'editDetailclient', 'deleteDetailclient',
            'pdfQuotationclient', 'pdfIvaQuotationclient', 'sumTotalProduct', 'saveSparePart']),
        availableDeliveryTimes(currentValue) {
            const options = [...this.deliveryTimes]

            if (currentValue && !options.some(deliveryTime => deliveryTime.label === currentValue)) {
                options.unshift({
                    id: `current-${currentValue}`,
                    label: currentValue
                })
            }

            return options
        },
        formatPrice(value) {
            return value.toLocaleString('es-CL', { style: 'currency', currency: 'CLP' });
        },
    }
}
</script>
