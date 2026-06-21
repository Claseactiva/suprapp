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
                            <input v-validate="'required|min:6|max:60'"
                                :class="{ 'input': true, 'is-invalid': errors.has('patente o chasis') }"
                                class="form-control" type="text" name="patente o chasis"
                                v-model="formCotizacionExpress.patentchasis" placeholder="Patente o Chasis *" />
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
                        <div class="quotation-request-builder__search-wrap">
                            <input id="template-search-express" ref="templateSearchInput" type="text" class="form-control"
                                v-model="templateSearch" @input="handleTemplateInput" @keydown.enter.prevent="addSelectedTemplate"
                                placeholder="Ej: bandeja, amortiguador, capot..." autocomplete="off" />
                            <button type="button" class="btn btn-info quotation-request-builder__add"
                                @click.prevent="addSelectedTemplate">Agregar</button>
                        </div>
                        <ul v-if="templateSuggestions.length" class="quotation-request-builder__suggestion-list">
                            <li v-for="suggestion in templateSuggestions" :key="suggestion.id" class="quotation-request-builder__suggestion-item">
                                <button type="button" class="quotation-request-builder__suggestion-row"
                                    @click.prevent="addTemplateSuggestion(suggestion)">
                                    <span class="quotation-request-builder__suggestion-name">{{ suggestion.product_name }}</span>
                                    <small class="quotation-request-builder__suggestion-category">{{ suggestion.categoria }}</small>
                                </button>
                            </li>
                        </ul>
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
                    <div class="form-group">
                        <h5>(*): Campos Requeridos</h5>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success form-control">
                        <i class="far fa-paper-plane"></i> Solicitar
                    </button>
                </div>
            </div>
        </form>

        <div v-if="submissionModalVisible" class="quotation-success-modal">
            <div class="quotation-success-modal__card">
                <h3>Solicitud enviada</h3>
                <p>Te llevaremos a WhatsApp para seguir con Comercial Supra.</p>
                <p v-if="redirectCountdown > 0">Redirigiendo en {{ redirectCountdown }}...</p>
                <div class="quotation-success-modal__actions">
                    <button type="button" class="btn btn-success" @click="goToWhatsApp">Ir ahora</button>
                    <button type="button" class="btn btn-light" @click="closeSubmissionModal">Cerrar</button>
                </div>
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
            redirectCountdown: 0,
            whatsAppRedirectUrl: '',
            redirectTimeoutId: null,
            redirectIntervalId: null,
            templateSearch: '',
            templateSuggestions: [],
            selectedTemplateParts: [],
            additionalDescription: '',
            templateSearchTimeoutId: null,
            localDescriptionError: '',
        }
    },
    computed: {
        ...mapState([
            'errorsLaravel', 'formCotizacionExpress'
        ])
    },
    methods: {
        ...mapActions([
            'createQuotationUserExpress'
        ]),
        submitQuotation() {
            this.syncDescription()

            if (!this.formCotizacionExpress.description || this.formCotizacionExpress.description.trim().length < 6) {
                this.localDescriptionError = 'Agrega al menos un repuesto o escribe un detalle adicional.'
                return
            }

            this.localDescriptionError = ''
            this.createQuotationUserExpress()
        },
        handleTemplateInput() {
            this.localDescriptionError = ''
            const term = this.templateSearch.trim()

            if (this.templateSearchTimeoutId) {
                window.clearTimeout(this.templateSearchTimeoutId)
            }

            if (term.length < 2) {
                this.templateSuggestions = []
                return
            }

            this.templateSearchTimeoutId = window.setTimeout(() => {
                this.fetchTemplateSuggestions(term)
            }, 180)
        },
        fetchTemplateSuggestions(term) {
            axios.get('product-catalog-templates-suggestions', {
                params: { term, limit: 8 }
            }).then(response => {
                const selectedKeys = new Set(this.selectedTemplateParts.map(part => part.product_key))
                this.templateSuggestions = (response.data.suggestions || []).filter(suggestion => !selectedKeys.has(suggestion.product_key))
            }).catch(() => {
                this.templateSuggestions = []
            })
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
        handleSubmissionEvent(event) {
            const detail = event.detail || {}
            this.resetRequestBuilder()
            this.whatsAppRedirectUrl = detail.whatsAppUrl || ''
            this.submissionModalVisible = true
            this.redirectCountdown = 2
            this.clearRedirectTimers()
            this.redirectIntervalId = window.setInterval(() => {
                if (this.redirectCountdown > 0) {
                    this.redirectCountdown -= 1
                }
            }, 1000)
            this.redirectTimeoutId = window.setTimeout(() => {
                this.goToWhatsApp()
            }, 1800)
        },
        goToWhatsApp() {
            if (!this.whatsAppRedirectUrl) {
                return
            }

            this.clearRedirectTimers()
            window.location.href = this.whatsAppRedirectUrl
        },
        closeSubmissionModal() {
            this.clearRedirectTimers()
            this.submissionModalVisible = false
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
    mounted() {
        window.addEventListener('public-quotation-sent', this.handleSubmissionEvent)
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
    list-style: none;
    margin: 0.75rem 0 0;
    padding: 0;
    border: 1px solid #d9e6ea;
    border-radius: 10px;
    overflow: hidden;
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

.quotation-success-modal__actions {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
    flex-wrap: wrap;
}

@media only screen and (max-width: 540px) {
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
