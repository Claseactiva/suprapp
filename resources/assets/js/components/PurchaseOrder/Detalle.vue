<template>
    <div>
    <div id="modalPurchaseOrder" class="modal fade modal-fullscreen">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h4>Administrar Orden de Compra</h4>
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
                                    <form action="POST" v-on:submit.prevent="createPurchaseOrderDetail">
                                        <div class="row align-items-end">

                                            <div class="col-lg-4 col-md-12">
                                                <label for="product">Producto</label>
                                                <input required type="text" name="product" class="form-control"
                                                    v-model="newPurchaseOrderDetail.product">
                                            </div>

                                            <div class="col-lg-2 col-md-12">
                                                <label for="detail">Código</label>
                                                <input type="text" name="detail" class="form-control"
                                                    v-model="newPurchaseOrderDetail.detail">
                                            </div>

                                            <div class="col-lg-2 col-md-12">
                                                <label for="precio">Precio</label>
                                                <input type="number" step="0.01" name="precio" class="form-control"
                                                    v-model="newPurchaseOrderDetail.price" @keyup="sumTotalPurchaseOrderDetail" required>
                                            </div>

                                            <div class="col-lg-1 col-md-12">
                                                <label for="cantidad">Cantidad</label>
                                                <input type="number" step="0.01" name="quantity" class="form-control" required
                                                    v-model="newPurchaseOrderDetail.quantity" @keyup="sumTotalPurchaseOrderDetail" min="0.01">
                                            </div>

                                            <div class="col-lg-2 col-md-12">
                                                <label for="plazo">Días de Plazo</label>
                                                <select name="plazo" class="form-control" v-model="newPurchaseOrderDetail.days">
                                                    <option v-for="deliveryTime in availableDeliveryTimes(newPurchaseOrderDetail.days)"
                                                        :key="deliveryTime.id || deliveryTime.label" :value="deliveryTime.label">
                                                        {{ deliveryTime.label }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div class="col-lg-1 col-md-12">
                                                <label for="total">Total</label>
                                                <input type="number" step="0.01" name="total" class="form-control"
                                                    v-model="newPurchaseOrderDetail.total" disabled>
                                            </div>

                                            <div class="col-12 mt-3 text-right">
                                                <button type="submit" class="btn btn-success">
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
                                        <th>Precio ($)</th>
                                        <th>Cantidad</th>
                                        <th>Total ($)</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="(detailLocal, index) in purchaseOrderLines" :key="detailLocal.id">
                                        <td data-table-label="ID">{{ index + 1 }}</td>
                                        <td data-table-label="Producto">{{ detailLocal.product }}</td>
                                        <td data-table-label="Código">{{ detailLocal.detail }}</td>
                                        <td data-table-label="Plazo">{{ detailLocal.days }}</td>
                                        <td data-table-label="Precio ($)">{{ formatPrice(detailLocal.price) }}</td>
                                        <td data-table-label="Cantidad">{{ detailLocal.quantity }}</td>
                                        <td data-table-label="Total ($)"><b>{{ formatPrice(detailLocal.total) }}</b></td>
                                        <td>
                                            <a href="#" class="btn btn-warning btn-icon-sm"
                                                @click.prevent="editPurchaseOrderDetail({ detailLocal })" data-toggle="tooltip"
                                                data-placement="top" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="#" class="btn btn-info btn-icon-sm" style="position: relative;"
                                                @click.prevent="openPurchaseOrderDetailImages(detailLocal)"
                                                data-toggle="tooltip" data-placement="top" title="Fotos">
                                                <i class="fas fa-camera"></i>
                                                <span class="badge badge-light" v-if="detailLocal.images && detailLocal.images.length > 0">
                                                    {{ detailLocal.images.length }}
                                                </span>
                                            </a>

                                            <a href="#" class="btn btn-danger btn-icon-sm"
                                                @click.prevent="deletePurchaseOrderDetail({ id: detailLocal.id })"
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
                                        <td data-table-label="Total"><b>{{ formatPrice(totalPurchaseOrder) }}</b></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <div v-if="purchaseOrderLines.length > 0">
                        <a class="btn btn-danger" href="#" role="button" @click.prevent="pdfPurchaseOrder">
                            <i class="far fa-file-pdf"></i> PDF
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-if="pdfPreviewUrl" class="quotation-pdf-preview" @click.self="resetPdfPreview">
        <div class="quotation-pdf-preview__dialog">
            <div class="quotation-pdf-preview__header bg-dark text-white">
                <h5 class="mb-0">{{ pdfPreviewTitle }}</h5>
                <button type="button" class="close text-white" aria-label="Close" @click="resetPdfPreview">
                    <span>&times;</span>
                </button>
            </div>
            <div class="quotation-pdf-preview__body">
                <iframe :src="pdfPreviewUrl" class="quotation-pdf-preview__frame" title="Vista previa PDF"></iframe>
            </div>
        </div>
    </div>
    <PurchaseOrderDetailImages></PurchaseOrderDetailImages>
    </div>
</template>

<script>

import { mapState, mapActions } from 'vuex'
import PurchaseOrderDetailImages from './PurchaseOrderDetailImages'

export default {
    components: { PurchaseOrderDetailImages },
    data() {
        return {
            pdfPreviewUrl: '',
            pdfPreviewTitle: ''
        }
    },
    computed: {
        ...mapState(['purchaseOrderLines', 'totalPurchaseOrder', 'newPurchaseOrderDetail', 'errorsLaravel', 'idPurchaseOrder', 'deliveryTimes'])
    },
    methods: {
        ...mapActions(['createPurchaseOrderDetail', 'editPurchaseOrderDetail', 'deletePurchaseOrderDetail',
            'sumTotalPurchaseOrderDetail', 'openPurchaseOrderDetailImages']),
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
        pdfPurchaseOrder() {
            this.openPdfPreview(`purchase-order-pdf/${this.idPurchaseOrder}`, 'PDF Orden de Compra')
        },
        openPdfPreview(url, title) {
            const separator = url.indexOf('?') === -1 ? '?' : '&'
            this.pdfPreviewTitle = title
            this.pdfPreviewUrl = `${url}${separator}preview=${Date.now()}`
        },
        resetPdfPreview() {
            this.pdfPreviewUrl = ''
            this.pdfPreviewTitle = ''
        },
        formatPrice(value) {
            const numericValue = Number(value) || 0
            const hasDecimals = Math.abs(numericValue - Math.round(numericValue)) >= 0.005
            const decimals = hasDecimals ? 2 : 0
            return '$ ' + numericValue.toLocaleString('es-CL', { minimumFractionDigits: decimals, maximumFractionDigits: decimals })
        }
    }
}
</script>

<style scoped>
.quotation-pdf-preview {
    position: fixed;
    inset: 0;
    z-index: 1065;
    background: rgba(0, 0, 0, 0.78);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem;
}

.quotation-pdf-preview__dialog {
    width: min(98vw, 1600px);
    height: calc(100vh - 1.5rem);
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
    display: flex;
    flex-direction: column;
}

.quotation-pdf-preview__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
}

.quotation-pdf-preview__body {
    flex: 1;
    background: #d9d9d9;
}

.quotation-pdf-preview__frame {
    width: 100%;
    height: 100%;
    border: 0;
    background: #fff;
}
</style>
