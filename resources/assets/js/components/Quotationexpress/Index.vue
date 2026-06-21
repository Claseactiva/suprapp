<template>
    <div class="col-lg-12 quotation-public-form">
        <h5 class="text-white">
            Formulario de Cotizaci&oacute;n Express
        </h5>
        <form action="POST" v-on:submit.prevent="submitQuotation">
            <div class="card w-50">
                <div class="card-body">
                    <div class="row">
                        <p v-if="errorsLaravel.length">
                            <b class="text-danger">Por favor, corrija los siguientes errores:</b>
                        <ul v-for="error in errorsLaravel" class="text-danger" :key="error.field + error.msg">
                            <li>{{ error.msg }}</li>
                        </ul>
                        </p>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Veh&iacute;culo</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-car"></i></div>
                            </div>
                            <input v-validate="'min:6|max:60'"
                                :class="{ 'input': true, 'is-invalid': errors.has('patente o chasis') }"
                                class="form-control" type="text" name="patente o chasis"
                                v-model="formCotizacionExpress.patentchasis" placeholder="Patente o Chasis (opcional)" />
                        </div>
                        <p v-show="errors.has('patente o chasis')" class="text-danger">{{ errors.first('patente o chasis') }}</p>
                    </div>
                    <div class="quotation-vehicle-stack">
                        <div class="quotation-vehicle-stack__field">
                            <label class="font-weight-bold quotation-vehicle-stack__label">Marca *</label>
                            <BrandSelector></BrandSelector>
                        </div>
                        <div class="quotation-vehicle-stack__field">
                            <label class="font-weight-bold quotation-vehicle-stack__label">Modelo *</label>
                            <ModelSelector></ModelSelector>
                        </div>
                        <div class="quotation-vehicle-stack__field">
                            <label class="font-weight-bold quotation-vehicle-stack__label">A&ntilde;o *</label>
                            <YearSelector></YearSelector>
                        </div>
                    </div>
                    <div class="form-group quotation-request-builder">
                        <div class="quotation-request-builder__search-box"><div class="quotation-request-builder__search-wrap">
                            <input id="template-search-express" ref="templateSearchInput" type="text" class="form-control"
                                v-model="templateSearch" @input="handleTemplateInput" @focus="handleTemplateFocus" @keydown.enter.prevent="addSelectedTemplate"
                                placeholder="Ej: bandeja, amortiguador, capot..." autocomplete="off" />
                            <button type="button" class="btn btn-info quotation-request-builder__add"
                                @click.prevent="addSelectedTemplate">Agregar</button>
                        </div><ul v-if="templateSuggestions.length" class="quotation-request-builder__suggestion-list">
                            <li v-for="suggestion in templateSuggestions" :key="suggestion.id" class="quotation-request-builder__suggestion-item">
                                <button type="button" class="quotation-request-builder__suggestion-row"
                                    @click.prevent="addTemplateSuggestion(suggestion)">
                                    <span class="quotation-request-builder__suggestion-name">{{ suggestion.product_name }}</span>
                                    <small class="quotation-request-builder__suggestion-category">{{ suggestion.categoria }}</small>
                                </button>
                            </li>
                        </ul></div>
                        <div v-if="selectedTemplateParts.length" class="quotation-request-builder__selected">
                            <div class="quotation-request-builder__selected-title">Repuestos agregados</div>
                            <ol class="quotation-request-builder__selected-list">
                                <li v-for="(part, index) in selectedTemplateParts" :key="part.key" class="quotation-request-builder__selected-item">
                                    <span class="quotation-request-builder__selected-text">{{ part.product_name }}</span>
                                    <button type="button" class="quotation-request-builder__selected-remove" @click.prevent="removeTemplatePart(index)">Quitar</button>
                                </li>
                            </ol>
                        </div>
                        <label class="font-weight-bold mt-3" for="description-express">Detalle adicional</label>
                        <textarea id="description-express" class="form-control" style="resize:none" v-model="additionalDescription"
                            @input="syncDescription" placeholder="Si quieres aclarar algo mas, escribelo aqui..." cols="30" rows="4"></textarea>
                        <p v-if="localDescriptionError" class="text-danger mb-0">{{ localDescriptionError }}</p>
                    </div>
</div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success form-control" :disabled="publicQuotationSubmitting">
                        <span v-if="publicQuotationSubmitting">Enviando...</span>
                        <span v-else><i class="far fa-paper-plane"></i> Solicitar</span>
                    </button>
                </div>
            </div>
        </form>
        <div v-if="submissionModalVisible" class="quotation-success-modal">
            <div class="quotation-success-modal__card">
                <template v-if="submissionModalState === 'loading'">
                    <div class="quotation-success-modal__spinner"></div>
                    <h3>Estamos enviando tu solicitud</h3>
                    <p>Esto puede tardar unos segundos. No cierres esta ventana.</p>
                    <p v-if="submissionMissingPatent" class="quotation-success-modal__hint">No agregaste patente o numero de chasis. Ese dato nos ayuda a ser mas precisos y es posible que te lo pidamos por WhatsApp si no encontramos una compatibilidad exacta.</p>
                </template>
                <template v-else>
                    <h3>Solicitud enviada</h3>
                    <p>Te redirigiremos automaticamente al canal de origen para continuar con Comercial Supra.</p>
                    <p v-if="redirectCountdown > 0">Seras redirigido automaticamente en {{ redirectCountdown }} segundos.</p>
                    <div class="quotation-success-modal__actions">
                        <button type="button" class="btn btn-success" @click="goToWhatsApp">Ir ahora</button>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { mapState, mapActions } from 'vuex'
import BrandSelector from '../Quotationuser/BrandSelector'
import ModelSelector from '../Quotationuser/ModelSelector'
import YearSelector from '../Quotationuser/YearSelector'

export default {
    components: { BrandSelector, ModelSelector, YearSelector },
    data() {
        return {
            submissionModalVisible: false,
            submissionModalState: 'idle',
            submissionMissingPatent: false,
            redirectCountdown: 0,
            whatsAppRedirectUrl: '',
            redirectTimeoutId: null,
            redirectIntervalId: null,
            templateSearch: '',
            templateSuggestions: [],
            allTemplateSuggestions: [],
            templatesLoaded: false,
            selectedTemplateParts: [],
            additionalDescription: '',
            templateSearchTimeoutId: null,
            localDescriptionError: '',
        }
    },
    computed: {
        ...mapState([
            'errorsLaravel', 'formCotizacionExpress', 'publicQuotationSubmitting'
        ])
    },
    methods: {
        ...mapActions([
            'createQuotationUserExpress'
        ]),
        submitQuotation() {
            if (this.publicQuotationSubmitting) {
                return
            }

            this.syncDescription()

            if (!this.formCotizacionExpress.description || this.formCotizacionExpress.description.trim().length < 6) {
                this.localDescriptionError = 'Agrega al menos un repuesto o escribe un detalle adicional.'
                return
            }

            this.localDescriptionError = ''
            this.showSubmissionLoadingModal()
            this.createQuotationUserExpress()
        },
        handleTemplateInput() {
            this.localDescriptionError = ''
            this.filterTemplateSuggestions()
        },
        handleTemplateFocus() {
            this.filterTemplateSuggestions()
        },
        loadTemplateSuggestions() {
            axios.get('product-catalog-templates-suggestions', {
                params: { limit: 500 }
            }).then(response => {
                this.allTemplateSuggestions = (response.data.suggestions || []).map(suggestion => ({
                    ...suggestion,
                    category_key: this.normalizeText(suggestion.categoria),
                    search_blob: `${this.normalizeText(suggestion.product_name)} ${this.normalizeText(suggestion.categoria)}`.trim(),
                }))
                this.templatesLoaded = true
                this.filterTemplateSuggestions()
            }).catch(() => {
                this.allTemplateSuggestions = []
                this.templatesLoaded = true
            })
        },
        filterTemplateSuggestions() {
            const rawTerm = (this.templateSearch || '').trim()
            if (rawTerm.length < 2) {
                this.templateSuggestions = []
                return
            }

            if (!this.templatesLoaded || !this.allTemplateSuggestions.length) {
                return
            }

            const normalizedTerm = this.normalizeText(rawTerm)
            const termParts = normalizedTerm.split(' ').filter(Boolean)
            const selectedKeys = new Set(this.selectedTemplateParts.map(part => part.product_key))

            this.templateSuggestions = this.allTemplateSuggestions
                .filter(suggestion => !selectedKeys.has(suggestion.product_key))
                .map(suggestion => {
                    const haystack = suggestion.search_blob || ''
                    const matches = termParts.every(part => haystack.indexOf(part) !== -1)

                    if (!matches) {
                        return null
                    }

                    let score = 0
                    if (suggestion.product_key === normalizedTerm) score += 1000
                    if (suggestion.product_key.indexOf(normalizedTerm) === 0) score += 500
                    if (suggestion.category_key.indexOf(normalizedTerm) === 0) score += 250
                    if (haystack.indexOf(normalizedTerm) !== -1) score += 100
                    score -= suggestion.product_name.length

                    return {
                        ...suggestion,
                        __score: score,
                    }
                })
                .filter(Boolean)
                .sort((left, right) => {
                    if (left.__score !== right.__score) {
                        return right.__score - left.__score
                    }

                    return left.product_name.localeCompare(right.product_name)
                })
                .slice(0, 8)
        },
        addSelectedTemplate() {
            const normalizedTerm = this.normalizeText(this.templateSearch)
            if (!normalizedTerm) {
                return
            }

            const match = this.templateSuggestions.find(suggestion => suggestion.product_key === normalizedTerm)
            if (match) {
                this.addTemplateSuggestion(match)
                return
            }

            const label = this.templateSearch.trim()
            if (label.length < 2) {
                return
            }

            this.pushTemplatePart({
                id: 'manual-' + Date.now(),
                categoria: 'Sugerencia',
                product_name: label,
                product_key: normalizedTerm,
            })
        },
        addTemplateSuggestion(suggestion) {
            this.pushTemplatePart(suggestion)
        },
        pushTemplatePart(suggestion) {
            if (this.selectedTemplateParts.some(part => part.product_key === suggestion.product_key)) {
                this.templateSearch = ''
                this.templateSuggestions = []
                return
            }

            this.selectedTemplateParts.push({
                key: suggestion.id + '-' + suggestion.product_key,
                id: suggestion.id,
                categoria: suggestion.categoria,
                product_name: suggestion.product_name,
                product_key: suggestion.product_key,
            })

            this.templateSearch = ''
            this.templateSuggestions = []
            this.syncDescription()

            this.$nextTick(() => {
                if (this.$refs.templateSearchInput) {
                    this.$refs.templateSearchInput.focus()
                }
            })
        },
        removeTemplatePart(index) {
            this.selectedTemplateParts.splice(index, 1)
            this.syncDescription()
        },
        syncDescription() {
            const parts = this.selectedTemplateParts.map((part, index) => `${index + 1}. ${part.product_name}`)
            const extra = (this.additionalDescription || '').trim()
            let description = parts.join('\n')

            if (extra) {
                description = description ? `${description}\n\nDetalle adicional:\n${extra}` : extra
            }

            this.formCotizacionExpress.description = description
            if (description.trim().length >= 6) {
                this.localDescriptionError = ''
            }
        },
        normalizeText(value) {
            return String(value || '')
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toUpperCase()
                .replace(/[^A-Z0-9]+/g, ' ')
                .trim()
        },
        showSubmissionLoadingModal() {
            this.clearRedirectTimers()
            this.whatsAppRedirectUrl = ''
            this.redirectCountdown = 0
            this.submissionMissingPatent = !String(this.formCotizacionExpress.patentchasis || '').trim()
            this.submissionModalState = 'loading'
            this.submissionModalVisible = true
        },
        handleSubmissionEvent(event) {
            const detail = event.detail || {}
            this.resetRequestBuilder()
            this.submissionMissingPatent = !!detail.missingPatent
            this.whatsAppRedirectUrl = detail.redirectUrl || ''
            this.clearRedirectTimers()
            this.submissionModalState = 'loading'
            this.submissionModalVisible = true
            this.redirectCountdown = 0
            this.redirectTimeoutId = window.setTimeout(() => {
                this.submissionModalState = 'success'
                this.redirectCountdown = 3
                this.redirectIntervalId = window.setInterval(() => {
                    if (this.redirectCountdown > 0) {
                        this.redirectCountdown -= 1
                    }
                }, 1000)
                this.redirectTimeoutId = window.setTimeout(() => {
                    this.goToWhatsApp()
                }, 3000)
            }, 2000)
        },
        handleSubmissionFailed() {
            this.clearRedirectTimers()
            this.whatsAppRedirectUrl = ''
            this.redirectCountdown = 0
            this.submissionModalState = 'idle'
            this.submissionModalVisible = false
        },
        goToWhatsApp() {
            if (!this.whatsAppRedirectUrl) {
                return
            }

            this.clearRedirectTimers()
            window.location.href = this.whatsAppRedirectUrl
        },
        closeSubmissionModal() {
            if (this.submissionModalState === 'loading') {
                return
            }

            this.clearRedirectTimers()
            this.submissionModalVisible = false
            this.submissionModalState = 'idle'
            this.submissionMissingPatent = false
            this.whatsAppRedirectUrl = ''
            this.redirectCountdown = 0
        },
        clearRedirectTimers() {
            if (this.redirectTimeoutId) {
                window.clearTimeout(this.redirectTimeoutId)
                this.redirectTimeoutId = null
            }
            if (this.redirectIntervalId) {
                window.clearInterval(this.redirectIntervalId)
                this.redirectIntervalId = null
            }
            if (this.templateSearchTimeoutId) {
                window.clearTimeout(this.templateSearchTimeoutId)
                this.templateSearchTimeoutId = null
            }
        },
        resetRequestBuilder() {
            this.templateSearch = ''
            this.templateSuggestions = []
            this.selectedTemplateParts = []
            this.additionalDescription = ''
            this.localDescriptionError = ''
        }
    },
    watch: {
        publicQuotationSubmitting(isSubmitting) {
            if (!isSubmitting && this.submissionModalState === 'loading' && Array.isArray(this.errorsLaravel) && this.errorsLaravel.length) {
                this.handleSubmissionFailed()
            }
        }
    },
    mounted() {
        window.addEventListener('public-quotation-sent', this.handleSubmissionEvent)
        this.loadTemplateSuggestions()
    },
    beforeDestroy() {
        window.removeEventListener('public-quotation-sent', this.handleSubmissionEvent)
        this.clearRedirectTimers()
    }
}
</script>

<style scoped>
.quotation-public-form input.form-control {
    height: 34px;
}

.quotation-public-form >>> .vs__dropdown-toggle {
    min-height: 34px;
    border-color: #ced4da;
    padding: 0 0.2rem;
}

.quotation-public-form >>> .vs__selected-options {
    padding: 0 0.2rem;
}

.quotation-public-form >>> .vs__actions {
    padding-right: 0.35rem;
}

.quotation-public-form >>> .vs__search,
.quotation-public-form >>> .vs__selected,
.quotation-public-form >>> .vs__placeholder {
    font-size: 0.88rem;
    line-height: 1;
}

.quotation-vehicle-stack {
    width: 100%;
    margin-bottom: 0.4rem;
}

.quotation-vehicle-stack__field + .quotation-vehicle-stack__field {
    margin-top: 0.35rem;
}

.quotation-vehicle-stack__label {
    display: block;
    margin-bottom: 0.12rem;
    color: #158bac;
}

.quotation-request-builder textarea.form-control {
    min-height: 92px;
}

.quotation-request-builder__label {
    display: block;
    color: #158bac;
    font-weight: 700;
    margin-bottom: 0.5rem;
}
.quotation-request-builder__search-box {
    position: relative;
}

.quotation-request-builder__search-wrap {
    display: flex;
    gap: 0.75rem;
    align-items: stretch;
}

.quotation-public-form .btn.btn-success {
    padding: 0.34rem 0.72rem;
    font-size: 0.86rem;
    line-height: 1.1;
}

.quotation-request-builder__add {
    min-width: 92px;
    padding: 0.3rem 0.65rem;
    font-size: 0.84rem;
    line-height: 1.1;
}

.quotation-request-builder__suggestion-list {
    position: absolute;
    top: calc(100% + 0.2rem);
    left: 0;
    right: 0;
    z-index: 30;
    max-height: 220px;
    overflow-y: auto;
    list-style: none;
    margin: 0.2rem 0 0;
    padding: 0;
    border: 1px solid #d9e6ea;
    border-radius: 10px;
    overflow-x: hidden;
    background: #ffffff;
}

.quotation-request-builder__suggestion-item + .quotation-request-builder__suggestion-item {
    border-top: 1px solid #e7eef1;
}

.quotation-request-builder__suggestion-row {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    border: 0;
    background: #ffffff;
    color: #1f2d3d;
    padding: 0.75rem 0.9rem;
    text-align: left;
    box-shadow: none;
}

.quotation-request-builder__suggestion-row:hover {
    background: #f3fafc;
}

.quotation-request-builder__suggestion-name {
    font-weight: 600;
}

.quotation-request-builder__suggestion-category {
    color: #158bac;
    font-weight: 700;
    white-space: nowrap;
}

.quotation-request-builder__selected {
    margin-top: 1rem;
}

.quotation-request-builder__selected-title {
    font-weight: 700;
    color: #158bac;
    margin-bottom: 0.5rem;
}

.quotation-request-builder__selected-list {
    margin: 0;
    padding-left: 1.25rem;
}

.quotation-request-builder__selected-item {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
    padding: 0.35rem 0;
}

.quotation-request-builder__selected-text {
    color: #1f2d3d;
    line-height: 1.35;
}

.quotation-request-builder__selected-remove {
    border: 0;
    background: transparent;
    color: #dc3545;
    font-weight: 700;
    padding: 0;
    line-height: 1;
    box-shadow: none;
    white-space: nowrap;
}

.quotation-request-builder__selected-remove:hover {
    color: #b21f2d;
    text-decoration: underline;
}

.quotation-success-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    z-index: 9999;
}

.quotation-success-modal__card {
    width: 100%;
    max-width: 430px;
    background: #ffffff;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.25);
    text-align: center;
}

.quotation-success-modal__card h3 {
    margin-bottom: 0.75rem;
    color: #158bac;
}

.quotation-success-modal__card p {
    color: #4d4d4d;
    margin-bottom: 0.9rem;
}

.quotation-success-modal__spinner {
    width: 48px;
    height: 48px;
    margin: 0 auto 1rem;
    border-radius: 999px;
    border: 4px solid #d7eef4;
    border-top-color: #158bac;
    animation: quotation-modal-spin 0.9s linear infinite;
}

.quotation-success-modal__hint {
    background: #f3f8fa;
    border-radius: 12px;
    padding: 0.85rem 0.95rem;
    font-size: 0.92rem;
}

.quotation-success-modal__actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
    flex-wrap: wrap;
}


@keyframes quotation-modal-spin {
    to {
        transform: rotate(360deg);
    }
}
@media only screen and (max-width: 540px) {
    .quotation-request-builder__search-box {
    position: relative;
}

.quotation-request-builder__search-wrap {
        flex-direction: column;
    }

    .quotation-public-form .btn.btn-success {
    padding: 0.34rem 0.72rem;
    font-size: 0.86rem;
    line-height: 1.1;
}

.quotation-request-builder__add {
        width: 100%;
    }

    .quotation-request-builder__suggestion-row {
        flex-direction: column;
        align-items: flex-start;
    }

    .quotation-request-builder__suggestion-category {
        white-space: normal;
    }
}
</style>



