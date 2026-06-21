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

                                                    <div class="row align-items-end">

                                                        <!-- <div class="col-lg-3">
                                                            <label for="cliente">Producto</label>
                                                            <SelectProduct></SelectProduct>
                                                        </div> -->

                                                        <div class="col-lg-4">
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
                                                                    v-for="suggestion in filteredProductSuggestions"
                                                                    :key="`${suggestion.source_type}-${suggestion.product_key}`"
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
                                                            <select name="plazo" class="form-control quotation-detail-select" v-model="newDetailclient.days">
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

                                                        <div class="col-lg-2 quotation-detail-action">
                                                            <label class="d-block invisible">Guardar</label>
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
                        <a class="btn btn-success" href="#" role="button"
                            @click.prevent="copyQuotationWhatsappText">
                            <i class="far fa-copy"></i> Copiar WhatsApp
                        </a>

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
    </form>

</template>

<script>

import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapGetters, mapActions } from 'vuex'
import toastr from 'toastr'
import SelectProduct from '../Product/SelectProduct'
import { buildCombinedProductSuggestions } from './productSuggestionHelpers'

export default {
    components: { SelectProduct },
    data() {
        return {
            pdfPreviewUrl: '',
            pdfPreviewTitle: ''
        }
    },
    computed:{
        ...mapState(['detailclients', 'totalQuotationclient', 'totalUtilidad', 'totalTransporte', 'totalAdicional',
                    'totalQuotationclientIVA', 'newDetailclient', 'totalDetailclient', 'errorsLaravel', 'deliveryTimes', 'modelProductSuggestions', 'optionsProduct', 'productCatalogTemplateSuggestions']),
        ...mapGetters([]),
        filteredProductSuggestions() {
            return buildCombinedProductSuggestions({
                modelSuggestions: this.modelProductSuggestions,
                productOptions: this.optionsProduct,
                catalogSuggestions: this.productCatalogTemplateSuggestions,
                term: this.newDetailclient.product,
                limit: 20
            })
        }
    },
    methods:{
        ...mapActions(['createDetailclient', 'editDetailclientMechanic', 'deleteDetailclient',
                    'pdfQuotationclient', 'pdfIvaQuotationclient', 'sumTotalProductMechanic']),
        copyQuotationWhatsappText() {
            if (!this.detailclients.length) {
                toastr.warning('No hay productos para copiar')
                return
            }

            const text = this.buildQuotationWhatsappText()

            this.copyTextToClipboard(text, 'Se copio la cotizacion para WhatsApp')
        },
        buildQuotationWhatsappText() {
            const lines = this.detailclients.map((detailLocal, index) => {
                const product = (detailLocal.product || 'Producto sin nombre').trim()
                const quantity = this.formatQuantity(detailLocal.quantity)
                const total = this.formatPriceForCopy(Math.round((Number(detailLocal.total) || 0) * 1.19))
                const delivery = this.normalizeDeliveryTime(detailLocal.days)

                return `${index + 1}. ${product}\nCantidad: ${quantity}\nValor: ${total}\nEntrega: ${delivery}`
            })

            return `COTIZACION\n\n${lines.join('\n\n')}\n\nTotal: ${this.formatPriceForCopy(this.totalQuotationclientIVA)}`
        },
        copyTextToClipboard(text, successMessage) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    toastr.success(successMessage)
                }).catch(() => {
                    this.copyTextToClipboardLegacy(text, successMessage)
                })
                return
            }

            this.copyTextToClipboardLegacy(text, successMessage)
        },
        copyTextToClipboardLegacy(text, successMessage) {
            const textArea = document.createElement('textarea')
            textArea.value = text
            textArea.setAttribute('readonly', '')
            textArea.style.position = 'fixed'
            textArea.style.opacity = '0'
            document.body.appendChild(textArea)
            textArea.select()

            try {
                const successful = document.execCommand('copy')

                if (successful) {
                    toastr.success(successMessage)
                } else {
                    toastr.error('No se pudo copiar la cotizacion')
                }
            } catch (error) {
                toastr.error('No se pudo copiar la cotizacion')
            } finally {
                document.body.removeChild(textArea)
                window.getSelection().removeAllRanges()
            }
        },
        normalizeDeliveryTime(value) {
            const text = (value || '').toString().trim()
            return text || 'Por confirmar'
        },
        formatQuantity(value) {
            const numericValue = Number(value)

            if (!Number.isFinite(numericValue) || numericValue <= 0) {
                return '1'
            }

            return Number.isInteger(numericValue)
                ? String(numericValue)
                : numericValue.toString().replace('.', ',')
        },
        formatPriceForCopy(value) {
            const numericValue = Number(value) || 0
            return new Intl.NumberFormat('es-CL', {
                style: 'currency',
                currency: 'CLP',
                maximumFractionDigits: 0
            }).format(numericValue)
        },
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
        pdfQuotationclient() {
            this.openPdfPreview(`quotationclient-pdf/${this.$store.state.idQuotationclient}`, 'PDF Cotizacion Formal')
        },
        pdfIvaQuotationclient() {
            this.openPdfPreview(`quotationclient-pdf-iva/${this.$store.state.idQuotationclient}`, 'PDF Cotizacion Formal con IVA')
        },
        openPdfPreview(url, title) {
            const quotationId = this.$store.state.idQuotationclient

            if (!quotationId) {
                toastr.warning('No se encontro la cotizacion para generar el PDF')
                return
            }

            const separator = url.indexOf('?') === -1 ? '?' : '&'
            this.pdfPreviewTitle = title
            this.pdfPreviewUrl = `${url}${separator}preview=${Date.now()}`
        },
        resetPdfPreview() {
            this.pdfPreviewUrl = ''
            this.pdfPreviewTitle = ''
        }
    },
}
</script>

<style scoped>
.quotation-detail-select {
    min-height: 30px !important;
    height: 30px !important;
    padding: 0 1.6rem 0 0.5rem !important;
    line-height: 28px !important;
    box-sizing: border-box;
}

.quotation-detail-action .btn {
    white-space: nowrap;
}

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

