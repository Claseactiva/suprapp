<template>
    <div>
        <div class="row">
            <div class="col-lg-6 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="mb-1">{{ isEditing ? 'Editar sugerencia' : 'Nueva sugerencia' }}</h5>
                        <small class="text-muted">Crea nombres base reutilizables por categoria.</small>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submitTemplate">
                            <div class="form-row">
                                <div class="form-group col-lg-6">
                                    <label for="categoria-template">Categoria</label>
                                    <input id="categoria-template" required type="text" class="form-control"
                                        v-model="activeForm.categoria">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label for="nombre-template">Nombre</label>
                                    <input id="nombre-template" required type="text" class="form-control"
                                        v-model="activeForm.nombre">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> {{ isEditing ? 'Actualizar' : 'Guardar' }}
                            </button>
                            <button v-if="isEditing" type="button" class="btn btn-secondary ml-2"
                                @click.prevent="cancelProductCatalogTemplateEdition">
                                Cancelar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-3">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="mb-1">Importar CSV</h5>
                        <small class="text-muted">Carga masiva con columnas `categoria,nombre`.</small>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submitImport">
                            <div class="form-group">
                                <label for="catalog-template-file">Archivo CSV</label>
                                <input id="catalog-template-file" ref="catalogImportFile" type="file"
                                    class="form-control-file" accept=".csv,text/csv" @change="handleFileChange">
                                <small class="form-text text-muted">
                                    El archivo reemplaza el catalogo actual para mantenerlo ordenado.
                                </small>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-upload"></i> Importar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="catalogErrors.length > 0" class="alert alert-danger mb-3">
            <ul class="mb-0 pl-3">
                <li v-for="(error, index) in catalogErrors" :key="index">{{ error }}</li>
            </ul>
        </div>

        <div class="catalog-template-list mt-3">
            <div class="row align-items-end mb-3">
                <div class="col-lg-5 mb-2 mb-lg-0">
                    <input type="text" v-model="searchProductCatalogTemplate.categoria"
                        @keyup="getProductCatalogTemplates" class="form-control"
                        placeholder="Filtrar categoria...">
                </div>
                <div class="col-lg-5 mb-2 mb-lg-0">
                    <input type="text" v-model="searchProductCatalogTemplate.nombre"
                        @keyup="getProductCatalogTemplates" class="form-control"
                        placeholder="Filtrar nombre...">
                </div>
                <div class="col-lg-2 text-lg-right">
                    <span class="badge badge-info catalog-template-total">{{ productCatalogTemplates.length }} registros</span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-responsive-new table-dark table-sm mt-3">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Categoria</th>
                            <th>Nombre</th>
                            <th>Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="template in productCatalogTemplates" :key="template.id">
                            <td data-table-label="ID">{{ template.id }}</td>
                            <td data-table-label="Categoria">{{ template.categoria }}</td>
                            <td data-table-label="Nombre">{{ template.nombre }}</td>
                            <td>
                                <a href="#" class="btn btn-warning btn-sm"
                                    @click.prevent="editProductCatalogTemplate({ template })" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-sm"
                                    @click.prevent="deleteProductCatalogTemplate({ id: template.id })" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <tr v-if="productCatalogTemplates.length === 0">
                            <td colspan="4" class="text-center">No hay sugerencias registradas.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapState } from 'vuex'

export default {
    computed: {
        ...mapState([
            'productCatalogTemplates',
            'searchProductCatalogTemplate',
            'newProductCatalogTemplate',
            'fillProductCatalogTemplate',
            'errorsLaravel'
        ]),
        isEditing() {
            return !!this.fillProductCatalogTemplate.id
        },
        activeForm() {
            return this.isEditing ? this.fillProductCatalogTemplate : this.newProductCatalogTemplate
        },
        catalogErrors() {
            const errors = this.errorsLaravel || {}

            if (Array.isArray(errors)) {
                return errors
            }

            if (typeof errors === 'string') {
                return [errors]
            }

            if (errors.errors && typeof errors.errors === 'object') {
                return Object.values(errors.errors).flat()
            }

            if (typeof errors === 'object') {
                return Object.values(errors).flat().filter(Boolean)
            }

            return []
        }
    },
    methods: {
        ...mapActions([
            'getProductCatalogTemplates',
            'createProductCatalogTemplate',
            'editProductCatalogTemplate',
            'cancelProductCatalogTemplateEdition',
            'updateProductCatalogTemplate',
            'deleteProductCatalogTemplate',
            'setProductCatalogTemplateImportFile',
            'importProductCatalogTemplates'
        ]),
        submitTemplate() {
            if (this.isEditing) {
                this.updateProductCatalogTemplate({ id: this.fillProductCatalogTemplate.id })
                return
            }

            this.createProductCatalogTemplate()
        },
        handleFileChange(event) {
            const file = event.target.files && event.target.files[0] ? event.target.files[0] : null
            this.setProductCatalogTemplateImportFile({ file })
        },
        submitImport() {
            this.importProductCatalogTemplates()
            window.setTimeout(() => {
                if (this.$refs.catalogImportFile) {
                    this.$refs.catalogImportFile.value = ''
                }
            }, 600)
        }
    },
    created() {
        this.getProductCatalogTemplates()
    }
}
</script>

<style>
.catalog-template-list {
    padding-top: 0.25rem;
}

.catalog-template-total {
    font-size: 0.85rem;
    padding: 0.5rem 0.75rem;
}
</style>