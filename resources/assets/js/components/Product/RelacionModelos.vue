<template>
    <form action="POST" v-on:submit.prevent="saveProductVehicleModels">
        <div id="product_vehicle_models" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h4 class="mb-0">Modelos Relacionados</h4>
                            <small class="text-muted">{{ productVehicleModelModal.productName }}</small>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row align-items-end mb-3">
                            <div class="form-group col-lg-4 mb-2">
                                <label for="product-model-brand-search">Filtrar marca</label>
                                <input
                                    id="product-model-brand-search"
                                    type="text"
                                    class="form-control"
                                    v-model="brandSearch"
                                    placeholder="Ej: Toyota"
                                >
                            </div>
                            <div class="form-group col-lg-4 mb-2">
                                <label for="product-model-model-search">Filtrar modelo</label>
                                <input
                                    id="product-model-model-search"
                                    type="text"
                                    class="form-control"
                                    v-model="modelSearch"
                                    placeholder="Ej: Tercel"
                                >
                            </div>
                            <div class="form-group col-lg-4 mb-2 text-lg-right">
                                <small class="text-muted d-block mb-2">{{ selectedProductVehicleModelIds.length }} relacionado(s)</small>
                                <button type="button" class="btn btn-outline-secondary btn-sm mr-2" @click.prevent="selectVisibleFiltered">
                                    Seleccionar visibles
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" @click.prevent="clearAllFiltersAndSelection">
                                    Limpiar
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive relation-table">
                            <table class="table table-sm table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th style="width: 56px;">Sel.</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="model in filteredProductVehicleModelOptions" :key="model.id">
                                        <td>
                                            <input
                                                type="checkbox"
                                                :checked="isProductVehicleModelSelected(model.id)"
                                                @change="toggleProductVehicleModel(model.id)"
                                            >
                                        </td>
                                        <td>{{ model.brand }}</td>
                                        <td>{{ model.model }}</td>
                                    </tr>
                                    <tr v-if="filteredProductVehicleModelOptions.length === 0">
                                        <td colspan="3" class="text-center text-muted py-3">Sin resultados</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
    data() {
        return {
            brandSearch: '',
            modelSearch: ''
        }
    },
    computed: {
        ...mapState([
            'productVehicleModelModal',
            'productVehicleModelOptions',
            'selectedProductVehicleModelIds'
        ]),
        filteredProductVehicleModelOptions() {
            const brandTerm = (this.brandSearch || '').trim().toLowerCase()
            const modelTerm = (this.modelSearch || '').trim().toLowerCase()

            return this.productVehicleModelOptions.filter((model) => {
                const modelBrand = (model.brand || '').toLowerCase()
                const modelName = (model.model || '').toLowerCase()
                const combined = `${modelBrand} ${modelName}`.trim()
                const matchesBrand = brandTerm === '' || modelBrand.includes(brandTerm) || combined.includes(brandTerm)
                const matchesModel = modelTerm === '' || modelName.includes(modelTerm) || combined.includes(modelTerm)

                return matchesBrand && matchesModel
            })
        }
    },
    methods: {
        ...mapActions([
            'saveProductVehicleModels',
            'toggleProductVehicleModel',
            'clearProductVehicleModels',
            'selectVisibleProductVehicleModels'
        ]),
        isProductVehicleModelSelected(id) {
            return this.selectedProductVehicleModelIds.includes(id)
        },
        clearAllFiltersAndSelection() {
            this.brandSearch = ''
            this.modelSearch = ''
            this.clearProductVehicleModels()
        },
        selectVisibleFiltered() {
            this.selectVisibleProductVehicleModels({
                ids: this.filteredProductVehicleModelOptions.map((model) => model.id)
            })
        }
    },
    watch: {
        'productVehicleModelModal.productId'() {
            this.brandSearch = ''
            this.modelSearch = ''
        }
    }
}
</script>

<style scoped>
.relation-table {
    max-height: 420px;
    overflow-y: auto;
}
</style>