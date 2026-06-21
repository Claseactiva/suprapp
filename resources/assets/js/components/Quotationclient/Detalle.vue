<template>
    <div>
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

                                        <div class="row mb-3 align-items-end">

                                            <div class="col-lg-5 col-md-12">
                                                <label for="cliente">Producto</label>
                                                <SelectProduct></SelectProduct>
                                            </div>

                                            <div class="col-lg-4 col-md-12">
                                                <label for="product">Nombre Producto</label>
                                                <input required type="text" name="product" class="form-control"
                                                    v-model="newDetailclient.product"
                                                    list="quotation-product-suggestions"
                                                    autocomplete="off">
                                                <datalist id="quotation-product-suggestions">
                                                    <option
                                                        v-for="suggestion in filteredProductSuggestions"
                                                        :key="`${suggestion.source_type}-${suggestion.product_key}`"
                                                        :value="suggestion.product_name"
                                                        :label="suggestion.display_label">
                                                    </option>
                                                </datalist>
                                            </div>

                                            <div class="col-lg-3 col-md-12">
                                                <label for="product">Código</label>
                                                <input type="text" name="product" class="form-control"
                                                    v-model="newDetailclient.detail">
                                            </div>
                                        </div>
                                        <div class="row align-items-end">

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
                                                <select name="plazo" class="form-control quotation-detail-select" v-model="newDetailclient.days">
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

                                            <div class="col-lg-1 col-md-12 quotation-detail-action">
                                                <label class="d-block invisible">Agregar</label>
                                                <button type="submit" class="btn btn-success form-control">
                                                    <i class="fas fa-plus-square"></i> Agregar
                                                </button>
                                            </div>

                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#bulkQuotationImportModal">
                                    <i class="fas fa-file-import"></i> Importar Precoti
                                </button>
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
                        <a class="btn btn-success" href="#" role="button" @click.prevent="copyQuotationWhatsappText">
                            <i class="far fa-copy"></i> Copiar WhatsApp
                        </a>

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
    <div id="bulkQuotationImportModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="mb-0">Importar Precoti</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <small class="text-muted d-block mb-3">Formato: CODIGO | PRODUCTO | CANTIDAD | PRECIO</small>

                    <textarea
                        v-model="bulkQuotationText"
                        class="form-control bulk-quotation-textarea"
                        rows="6"
                        placeholder="INT-0006665 | ALTERNADOR TOYOTA TERCEL | 1 | 45000&#10;| FILTRO ACEITE TOYOTA | 2 | 8000"></textarea>
                    <small class="form-text text-muted">
                        Si encuentra un codigo interno existente, vincula ese producto y no lo replica.
                        Lo demas entra con utilidad, flete y plazo por defecto.
                    </small>

                    <div class="d-flex flex-wrap bulk-quotation-actions mt-3">
                        <button type="button" class="btn btn-info" @click.prevent="processBulkQuotationText">
                            <i class="fas fa-cogs"></i> Procesar texto
                        </button>
                        <button type="button" class="btn btn-outline-secondary" @click.prevent="clearBulkQuotationText">
                            <i class="fas fa-eraser"></i> Limpiar
                        </button>
                        <button type="button" class="btn btn-success"
                            @click.prevent="importBulkQuotationItems"
                            :disabled="bulkImporting || bulkPreviewItems.length === 0">
                            <i class="fas fa-file-import"></i>
                            {{ bulkImporting ? 'Agregando...' : 'Agregar a cotizacion' }}
                        </button>
                    </div>

                    <div v-if="bulkIgnoredLines.length" class="alert alert-warning mt-3 mb-0 bulk-quotation-alert">
                        <strong>Lineas omitidas:</strong> {{ bulkIgnoredLines.length }}
                        <div v-for="ignoredLine in bulkIgnoredLines.slice(0, 5)" :key="`${ignoredLine.line}-${ignoredLine.reason}`">
                            {{ ignoredLine.line }} <small class="text-muted">({{ ignoredLine.reason }})</small>
                        </div>
                    </div>

                    <div v-if="bulkPreviewItems.length" class="table-responsive mt-3">
                        <table class="table table-sm table-bordered table-dark mb-0 bulk-quotation-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Estado</th>
                                    <th>Codigo</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in bulkPreviewItems" :key="`${item.product}-${index}`">
                                    <td>{{ index + 1 }}</td>
                                    <td><span :class="item.matchStatusClass">{{ item.matchStatus }}</span></td>
                                    <td>{{ item.code || '-' }}</td>
                                    <td>{{ item.product }}</td>
                                    <td>{{ item.quantity }}</td>
                                    <td>{{ formatPrice(item.price) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</template>

<script>

import axios from 'axios'
import { mapState, mapGetters, mapActions } from 'vuex'
import toastr from 'toastr'
import SelectProduct from '../Product/SelectProduct'
import { buildCombinedProductSuggestions } from './productSuggestionHelpers'

export default {
    components: { SelectProduct },
    data() {
        return {
            bulkQuotationText: '',
            bulkPreviewItems: [],
            bulkIgnoredLines: [],
            bulkImporting: false
        }
    },
    computed: {
        ...mapState(['detailclients', 'totalQuotationclient', 'totalUtilidad', 'totalTransporte', 'totalAdicional',
            'totalQuotationclientIVA', 'newDetailclient', 'totalDetailclient', 'totalProductIvaFlete', 'errorsLaravel', 'idQuotationclient', 'deliveryTimes', 'modelProductSuggestions', 'optionsProduct', 'productCatalogTemplateSuggestions', 'newUtilidad', 'newFlete', 'defaultDeliveryTime']),
        ...mapGetters([]),
        filteredProductSuggestions() {
            return buildCombinedProductSuggestions({
                modelSuggestions: this.modelProductSuggestions,
                productOptions: this.optionsProduct,
                catalogSuggestions: this.productCatalogTemplateSuggestions,
                term: this.newDetailclient.product,
                limit: 20
            })
        },
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
            'pdfQuotationclient', 'pdfIvaQuotationclient', 'sumTotalProduct', 'saveSparePart', 'getQuotationclientDetails']),
        processBulkQuotationText() {
            const rawLines = this.bulkQuotationText
                .split(/\r?\n/)
                .map(line => line.trim())
                .filter(Boolean)

            if (!rawLines.length) {
                toastr.warning('Pega una precoti antes de procesar')
                this.bulkPreviewItems = []
                this.bulkIgnoredLines = []
                return
            }

            const previewItems = []
            const ignoredLines = []

            rawLines.forEach((line) => {
                if (this.shouldSkipBulkLine(line)) {
                    return
                }

                const parsedItem = this.parseBulkQuotationLine(line)

                if (!parsedItem) {
                    ignoredLines.push({
                        line,
                        reason: 'No se pudo reconocer'
                    })
                    return
                }

                if (!parsedItem.product || parsedItem.price <= 0) {
                    ignoredLines.push({
                        line,
                        reason: 'Falta producto o precio'
                    })
                    return
                }

                previewItems.push(parsedItem)
            })

            this.bulkPreviewItems = previewItems
            this.bulkIgnoredLines = ignoredLines

            if (!previewItems.length) {
                toastr.warning('No se encontraron lineas validas para agregar')
                return
            }

            toastr.success(`Se procesaron ${previewItems.length} lineas de la precoti`)
        },
        clearBulkQuotationText() {
            this.bulkQuotationText = ''
            this.bulkPreviewItems = []
            this.bulkIgnoredLines = []
        },
        shouldSkipBulkLine(line) {
            const normalizedLine = this.normalizeBulkToken(line)
            return normalizedLine === 'COTIZACION' || normalizedLine === 'TOTAL' || normalizedLine.startsWith('TOTAL:')
        },
        parseBulkQuotationLine(line) {
            const columns = this.splitBulkQuotationColumns(line)

            if (columns.length < 3) {
                return null
            }

            let code = ''
            let product = ''
            let quantityToken = ''
            let priceToken = ''

            if (columns.length == 3) {
                ;[product, quantityToken, priceToken] = columns
            } else {
                code = columns[0]
                quantityToken = columns[columns.length - 2]
                priceToken = columns[columns.length - 1]
                product = columns.slice(1, columns.length - 2).join(' | ')
            }

            const quantity = this.parseBulkQuantity(quantityToken)
            const price = this.parseBulkPrice(priceToken)
            const matchedProduct = this.findExistingProductByCode(code)
            const finalProductName = matchedProduct ? matchedProduct.label : product.trim()
            const finalCode = matchedProduct ? matchedProduct.codebar : code.trim()

            return {
                code: finalCode,
                product: finalProductName,
                quantity,
                price,
                matchedProduct,
                matchStatus: matchedProduct
                    ? 'Vinculado'
                    : (finalCode ? 'Codigo no encontrado' : 'Manual'),
                matchStatusClass: matchedProduct
                    ? 'badge badge-success'
                    : (finalCode ? 'badge badge-warning' : 'badge badge-secondary')
            }
        },
        splitBulkQuotationColumns(line) {
            if (line.includes('|')) {
                return line
                    .split('|')
                    .map(part => part.trim())
                    .filter(part => part !== '')
            }

            if (line.includes('\t')) {
                return line
                    .split('\t')
                    .map(part => part.trim())
                    .filter(part => part !== '')
            }

            return line
                .split(/\s{2,}/)
                .map(part => part.trim())
                .filter(part => part !== '')
        },
        normalizeBulkToken(value) {
            return (value || '')
                .toString()
                .trim()
                .replace(/\s+/g, ' ')
                .toUpperCase()
        },
        parseBulkQuantity(value) {
            const normalized = (value || '').toString().replace(',', '.').replace(/[^\d.]/g, '')
            const parsed = Number(normalized)
            return Number.isFinite(parsed) && parsed > 0 ? parsed : 1
        },
        parseBulkPrice(value) {
            const normalized = (value || '').toString().replace(/[^\d]/g, '')
            const parsed = Number(normalized)
            return Number.isFinite(parsed) ? parsed : 0
        },
        findExistingProductByCode(code) {
            const normalizedCode = this.normalizeBulkToken(code)

            if (!normalizedCode) {
                return null
            }

            return this.optionsProduct.find(product => this.normalizeBulkToken(product.codebar) === normalizedCode) || null
        },
        async importBulkQuotationItems() {
            if (!this.bulkPreviewItems.length) {
                toastr.warning('Primero procesa una precoti valida')
                return
            }

            this.bulkImporting = true
            let importedCount = 0

            try {
                for (const item of this.bulkPreviewItems) {
                    await axios.post('detailclients', this.buildBulkDetailPayload(item))
                    importedCount += 1
                }

                this.getQuotationclientDetails()
                this.clearBulkQuotationText()
                toastr.success(`Se agregaron ${importedCount} productos a la cotizacion`)
            } catch (error) {
                toastr.error('No se pudo completar la carga masiva de la precoti')
            } finally {
                this.bulkImporting = false
            }
        },
        buildBulkDetailPayload(item) {
            const percentage = this.getBulkDefaultPercentage()
            const transport = this.getBulkDefaultTransport()
            const quantity = Number(item.quantity) || 1
            const price = Number(item.price) || 0
            const aditional = 0
            const utility = Math.round((price * percentage / 100) * quantity)
            const total = Math.round(((price * ((percentage / 100) + 1) * 1.19) + aditional + transport) * quantity)

            return {
                quotationclient_id: this.idQuotationclient,
                product_id: item.matchedProduct ? item.matchedProduct.value : null,
                product: item.product,
                detail: item.code || '',
                days: this.getBulkDefaultDeliveryTime(),
                price,
                quantity,
                percentage,
                aditional,
                transport,
                utility,
                total
            }
        },
        getBulkDefaultPercentage() {
            const percentage = Number(this.newUtilidad && this.newUtilidad.utilidad)
            return Number.isFinite(percentage) ? percentage : Number(this.newDetailclient.percentage || 0)
        },
        getBulkDefaultTransport() {
            const transport = Number(this.newFlete && this.newFlete.flete)
            return Number.isFinite(transport) ? transport : Number(this.newDetailclient.transport || 0)
        },
        getBulkDefaultDeliveryTime() {
            return (this.defaultDeliveryTime && this.defaultDeliveryTime.label) || this.newDetailclient.days || '24 a 48 Hrs'
        },
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
                const total = this.formatPriceForCopy(detailLocal.total)
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

            if (!Number.isFinite(numericValue)) {
                return value || '1'
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
        formatPrice(value) {
            const numericValue = Number(value) || 0
            return numericValue.toLocaleString('es-CL', { style: 'currency', currency: 'CLP' })
        },
    }
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

.bulk-quotation-textarea {
    font-family: Consolas, 'Courier New', monospace;
}

.bulk-quotation-actions {
    gap: 0.5rem;
}

.bulk-quotation-alert {
    font-size: 0.85rem;
}

.bulk-quotation-table th,
.bulk-quotation-table td {
    vertical-align: middle;
}
</style>
