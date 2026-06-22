<template>
    <div class="container quotation-public-form">
        <form action="POST" v-on:submit.prevent="submitQuotation">
            <div class="row">
                <h3>Formulario de Cotizaci&oacute;n</h3>
            </div>
            <div class="row">
                <p v-if="errorsLaravel.length">
                    <b class="text-danger">Por favor, corrija los siguientes errores:</b>
                <ul v-for="error in errorsLaravel" class="text-danger" :key="error.field + error.msg">
                    <li>{{ error.msg }}</li>
                </ul>
                </p>
            </div>
            <div class="row">
                <h4>Contacto</h4>
                <div class="input-group input-group-icon">
                    <input v-validate="'required|alpha_spaces|min:3|max:60'"
                        :class="{ 'input': true, 'is-invalid': errors.has('nombre') }" class="form-control" type="text"
                        name="nombre" v-model="formCotizacion.name" placeholder="Nombre *" />
                    <div class="input-icon"><i class="fa fa-user"></i></div>
                    <p v-show="errors.has('nombre')" class="text-danger">{{ errors.first('nombre') }}</p>
                </div>
                <div class="input-group input-group-icon">
                    <input v-validate="'required|min:7'" :class="{ 'input': true, 'is-invalid': errors.has('telefono') }"
                        class="form-control" type="text" name="telefono" placeholder="WhatsApp *"
                        v-model="formCotizacion.phone" />
                    <div class="input-icon"><i class="fa fa-phone"></i></div>
                    <p v-show="errors.has('telefono')" class="text-danger">{{ errors.first('telefono') }}</p>
                </div>
            </div>
            <div class="row">
                <h4>Veh&iacute;culo</h4>
                <div class="input-group input-group-icon">
                    <input v-validate="'min:6|max:60'"
                        :class="{ 'input': true, 'is-invalid': errors.has('patente o chasis') }" class="form-control"
                        type="text" name="patente o chasis" v-model="formCotizacion.patentchasis"
                        placeholder="Patente o Chasis (opcional)" />
                    <div class="input-icon"><i class="fa fa-car"></i></div>
                    <p v-show="errors.has('patente o chasis')" class="text-danger">{{ errors.first('patente o chasis') }}</p>
                </div>
                <div class="quotation-vehicle-stack">
                    <div class="quotation-vehicle-stack__field">
                        <label class="quotation-vehicle-stack__label">Marca *</label>
                        <BrandSelector></BrandSelector>
                    </div>
                    <div class="quotation-vehicle-stack__field">
                        <label class="quotation-vehicle-stack__label">Modelo *</label>
                        <ModelSelector></ModelSelector>
                    </div>
                    <div class="quotation-vehicle-stack__field">
                        <label class="quotation-vehicle-stack__label">A&ntilde;o *</label>
                        <YearSelector></YearSelector>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="input-group quotation-request-builder">
                    <label class="quotation-request-builder__label" for="template-search-main">Buscar repuesto sugerido</label>
                    <div class="quotation-request-builder__search-box"><div class="quotation-request-builder__search-wrap">
                        <input id="template-search-main" ref="templateSearchInput" type="text" class="form-control"
                            v-model="templateSearch" @input="handleTemplateInput" @focus="handleTemplateFocus" @keydown.enter.prevent="addSelectedTemplate"
                            placeholder="Ej: amortiguador, capot, bandeja..." autocomplete="off" />
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
                    <label class="quotation-request-builder__label mt-3" for="description-main">Detalle adicional</label>
                    <textarea id="description-main" class="form-control" style="resize:none" v-model="additionalDescription"
                        @input="syncDescription" placeholder="Si necesitas aclarar algo mas, escribelo aqui..." cols="30" rows="4"></textarea>
                    <p v-if="localDescriptionError" class="text-danger mb-0">{{ localDescriptionError }}</p>
                </div>
            </div>
<div class="row">
                <div class="input-group" style="text-align:center;">
                    <button type="submit" class="btn btn-success" @click="scrollToTop" :disabled="publicQuotationSubmitting">
                        {{ publicQuotationSubmitting ? 'Enviando...' : 'Enviar' }}
                    </button>
                </div>
            </div>
        </form>
        <div v-if="submissionModalVisible" class="quotation-success-modal">
            <div class="quotation-success-modal__card">
                <template v-if="submissionModalState === 'loading'">
                    <div class="quotation-success-modal__spinner"></div>
                    <h3>Estamos procesando tu solicitud</h3>
                    <p>Un momento. Estamos registrando tu solicitud.</p>
                    <p v-if="submissionMissingPatent" class="quotation-success-modal__hint">No agregaste patente o numero de chasis. Ese dato nos ayuda a ser mas precisos y es posible que te lo pidamos por WhatsApp si no encontramos una compatibilidad exacta.</p>
                </template>
                <template v-else>
                    <h3>Gracias por cotizar con nosotros</h3>
                    <p v-if="redirectChannel === 'fb'">Te redirigiremos a Facebook nuevamente.</p>
                    <p v-else-if="redirectChannel === 'ig'">Te redirigiremos a Instagram nuevamente.</p>
                    <p v-else>Te redirigiremos a WhatsApp nuevamente.</p>
                    <p v-if="redirectCountdown > 0">Seras redirigido en {{ redirectCountdown }} segundos.</p>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import { mapState, mapActions } from 'vuex'
import BrandSelector from './BrandSelector'
import ModelSelector from './ModelSelector'
import YearSelector from './YearSelector'

export default {
    components: { BrandSelector, ModelSelector, YearSelector },
    data() {
        return {
            submissionModalVisible: false,
            submissionModalState: 'idle',
            submissionMissingPatent: false,
            redirectCountdown: 0,
            redirectChannel: 'wsp',
            whatsAppRedirectUrl: '',
            redirectTimeoutId: null,
            redirectIntervalId: null,
            templateSearch: '',
            templateSuggestions: [],
            allTemplateSuggestions: [],
            templatesLoaded: false,
            templatesLoading: false,
            selectedTemplateParts: [],
            additionalDescription: '',
            templateSearchTimeoutId: null,
            localDescriptionError: '',
        }
    },
    computed: {
        ...mapState([
            'errorsLaravel', 'formCotizacion', 'publicQuotationSubmitting'
        ])
    },
    methods: {
        ...mapActions([
            'createQuotationUser'
        ]),
        scrollToTop() {
            window.scrollTo(0, 0)
        },
        submitQuotation() {
            if (this.publicQuotationSubmitting) {
                return
            }

            this.syncDescription()

            if (!this.formCotizacion.description || this.formCotizacion.description.trim().length < 6) {
                this.localDescriptionError = 'Agrega al menos un repuesto o escribe un detalle adicional.'
                this.scrollToTop()
                return
            }

            this.localDescriptionError = ''
            this.handleSubmissionFailed()
            this.createQuotationUser()
        },
        handleTemplateInput() {
            this.localDescriptionError = ''
            this.filterTemplateSuggestions()
        },
        handleTemplateFocus() {
            this.loadTemplateSuggestions()
            this.filterTemplateSuggestions()
        },
        loadTemplateSuggestions() {
            if (this.templatesLoaded || this.templatesLoading) {
                return
            }
            this.templatesLoading = true
            axios.get('product-catalog-templates-suggestions', {
                params: { limit: 500 }
            }).then(response => {
                this.allTemplateSuggestions = (response.data.suggestions || []).map(suggestion => ({
                    ...suggestion,
                    category_key: this.normalizeText(suggestion.categoria),
                    search_blob: `${this.normalizeText(suggestion.product_name)} ${this.normalizeText(suggestion.categoria)}`.trim(),
                }))
                this.templatesLoaded = true
                this.templatesLoading = false
                this.filterTemplateSuggestions()
            }).catch(() => {
                this.allTemplateSuggestions = []
                this.templatesLoaded = true
                this.templatesLoading = false
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

            this.formCotizacion.description = description
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
            this.cleanupBootstrapBackdrop()
            this.whatsAppRedirectUrl = ''
            this.redirectCountdown = 0
            this.submissionMissingPatent = !String(this.formCotizacion.patentchasis || '').trim()
            this.submissionModalState = 'loading'
            this.submissionModalVisible = true
        },
        handleSubmissionEvent(event) {
            const detail = event.detail || {}
            this.resetRequestBuilder()
            this.submissionMissingPatent = !!detail.missingPatent
            this.whatsAppRedirectUrl = detail.redirectUrl || ''
            this.redirectChannel = detail.channel || 'wsp'
            this.clearRedirectTimers()
            this.cleanupBootstrapBackdrop()
            this.submissionModalState = 'success'
            this.submissionModalVisible = true
            this.redirectCountdown = 5
            this.redirectIntervalId = window.setInterval(() => {
                if (this.redirectCountdown > 0) {
                    this.redirectCountdown -= 1
                }
            }, 1000)
            this.redirectTimeoutId = window.setTimeout(() => {
                this.goToWhatsApp()
            }, 5000)
        },
        handleSubmissionFailed() {
            this.clearRedirectTimers()
            this.cleanupBootstrapBackdrop()
            this.whatsAppRedirectUrl = ''
            this.redirectCountdown = 0
            this.submissionMissingPatent = false
            this.submissionModalState = 'idle'
            this.submissionModalVisible = false
        },
        goToWhatsApp() {
            if (!this.whatsAppRedirectUrl) {
                this.handleSubmissionFailed()
                return
            }

            this.clearRedirectTimers()
            this.cleanupBootstrapBackdrop()
            window.location.href = this.whatsAppRedirectUrl
        },
        closeSubmissionModal() {
            if (this.submissionModalState === 'loading') {
                return
            }

            this.clearRedirectTimers()
            this.cleanupBootstrapBackdrop()
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
        cleanupBootstrapBackdrop() {
            if (typeof document === 'undefined') {
                return
            }

            document.querySelectorAll('.modal-backdrop').forEach(backdrop => backdrop.remove())
            document.body.classList.remove('modal-open')
            document.body.style.removeProperty('padding-right')
        },
        prefillFromQuery() {
            const params = new URLSearchParams(window.location.search)
            const name = params.get('nombre') || params.get('name') || ''
            const phone = params.get('telefono') || params.get('phone') || params.get('whatsapp') || ''
            const patent = params.get('patente') || params.get('patentchasis') || ''
            const description = params.get('descripcion') || params.get('description') || ''

            if (name) {
                this.formCotizacion.name = name
            }
            if (phone) {
                this.formCotizacion.phone = phone
            }
            if (patent) {
                this.formCotizacion.patentchasis = patent
            }
            if (description) {
                this.additionalDescription = description
                this.syncDescription()
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
        window.addEventListener('public-quotation-failed', this.handleSubmissionFailed)
        this.cleanupBootstrapBackdrop()
        this.prefillFromQuery()
    },
    beforeDestroy() {
        window.removeEventListener('public-quotation-sent', this.handleSubmissionEvent)
        window.removeEventListener('public-quotation-failed', this.handleSubmissionFailed)
        this.clearRedirectTimers()
        this.cleanupBootstrapBackdrop()
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
    margin: 0.15rem 0 0.35rem;
}

.quotation-vehicle-stack__field + .quotation-vehicle-stack__field {
    margin-top: 0.35rem;
}

.quotation-vehicle-stack__label {
    display: block;
    margin-bottom: 0.12rem;
    color: #158bac;
    font-weight: 700;
}
.quotation-request-builder textarea.form-control {
    min-height: 92px;
}

.quotation-request-builder {
    display: block;
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



