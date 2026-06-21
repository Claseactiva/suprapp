<template>
    <div class="col-lg-12 product-admin-view">
        <ul class="nav nav-pills mb-3 product-admin-tabs" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-new-product-tab" data-toggle="pill" href="#pills-new-product"
                    @click="activeSection = 'products'" role="tab" aria-controls="pills-new-product" aria-selected="false">Nuevo Producto</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-utility-tab" data-toggle="pill" href="#pills-utility"
                    @click="activeSection = 'utility'" role="tab" aria-controls="pills-utility" aria-selected="false">Utilidad Por Defecto</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-freight-tab" data-toggle="pill" href="#pills-freight"
                    @click="activeSection = 'freight'" role="tab" aria-controls="pills-freight" aria-selected="false">Flete Por Defecto</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-delivery-time-tab" data-toggle="pill" href="#pills-delivery-time"
                    @click="activeSection = 'delivery-time'" role="tab" aria-controls="pills-delivery-time" aria-selected="false">Plazos de Entrega</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-product-catalog-tab" data-toggle="pill" href="#pills-product-catalog"
                    @click="activeSection = 'catalog'" role="tab" aria-controls="pills-product-catalog" aria-selected="false">Catalogo Sugerencias</a>
            </li>
        </ul>
        <div :class="tabContentClasses" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-new-product" role="tabpanel"
                aria-labelledby="pills-new-product-tab">
                <form action="POST" v-on:submit.prevent="createProduct">
                    <div class="form-row">
                        <div class="form-group col-lg-6">
                            <label for="nombre">*Nombre</label>
                            <input required type="text" name="nombre" class="form-control" v-model="newProduct.name">
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="detalle">*Detalle</label>
                            <input required type="text" name="detalle" class="form-control" v-model="newProduct.detail">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-6">
                            <label for="cliente">*Proveedor</label>
                            <SelectProvider></SelectProvider>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="codigo">*Codigo</label>
                            <input required type="text" name="codigo" class="form-control" v-model="newProduct.codebar">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-plus-square"></i> Guardar
                    </button>
                </form>
            </div>
            <div class="tab-pane fade" id="pills-utility" role="tabpanel" aria-labelledby="pills-utility-tab">
                <UtilidadDefect></UtilidadDefect>
            </div>
            <div class="tab-pane fade" id="pills-freight" role="tabpanel" aria-labelledby="pills-freight-tab">
                <FleteDefect></FleteDefect>
            </div>
            <div class="tab-pane fade" id="pills-delivery-time" role="tabpanel" aria-labelledby="pills-delivery-time-tab">
                <DeliveryTimeIndex></DeliveryTimeIndex>
            </div>
            <div class="tab-pane fade" id="pills-product-catalog" role="tabpanel" aria-labelledby="pills-product-catalog-tab">
                <CatalogTemplates></CatalogTemplates>
            </div>
        </div>
        <div v-if="activeSection !== 'catalog'" class="row mt-3">
            <div class="col-lg-6">
                <input type="text" v-model="searchProduct.name" @keyup="getProducts({ page: 1, per_page: pagination.per_page })" class="form-control"
                    placeholder="Filtrar Producto...">
            </div>
        </div>
        <div v-if="activeSection !== 'catalog'" class="product-table-shell mt-3">
            <table class="table table-responsive-new table-dark table-sm product-table mb-0">
                <thead>
                    <tr>
                        <th class="product-col-id">ID</th>
                        <th class="product-col-name">Producto</th>
                        <th class="product-col-client">Cliente</th>
                        <th class="product-col-detail">Detalle</th>
                        <th class="product-col-code">Codigo</th>
                        <th class="product-col-date">Fecha</th>
                        <th class="product-col-utility">%Utilidad</th>
                        <th class="product-col-freight">Flete</th>
                        <th class="product-col-models">Modelos</th>
                        <th class="product-col-actions">Accion</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products" :key="product.id">
                        <td data-table-label="ID" class="product-col-id">{{ product.id }}</td>
                        <td data-table-label="Producto" class="product-col-name product-cell-strong" :title="product.name">{{ product.name }}</td>
                        <td data-table-label="Cliente" class="product-col-client product-cell-wrap" :title="product.client.name">{{ product.client.name }}</td>
                        <td data-table-label="Detalle" class="product-col-detail product-cell-wrap" :title="product.detail">{{ product.detail }}</td>
                        <td data-table-label="Codigo" class="product-col-code product-cell-code" :title="product.codebar">{{ product.codebar }}</td>
                        <td data-table-label="Fecha" class="product-col-date product-cell-meta">{{ product.created_at | moment('DD/MM/YY') }}</td>
                        <td data-table-label="%Utilidad" class="product-col-utility product-cell-meta">{{ product.utilidad }}%</td>
                        <td data-table-label="Flete" class="product-col-freight product-cell-meta">${{ product.flete }}</td>
                        <td data-table-label="Modelos" class="product-col-models product-cell-meta">{{ product.related_vehicle_models_count || 0 }}</td>
                        <td data-table-label="Accion" class="product-col-actions product-action-cell">
                            <a href="#" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#product_vehicle_models" title="Modelos relacionados"
                                @click.prevent="openProductVehicleModels({ product })">
                                <i class="fas fa-car-side"></i>
                            </a>
                            <a href="#" class="btn btn-warning btn-sm" data-toggle="modal"
                                data-target="#edit_product" title="Editar"
                                @click.prevent="editProduct({ product, page: pagination.current_page })">
                                <i class="far fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-info btn-sm" data-toggle="modal"
                                data-target="#edit_inventory" title="Inventario"
                                @click.prevent="editInventory({ id: product.id, inventories: product.inventories })">
                                <i class="fas fa-boxes"></i>
                            </a>
                            <a href="#" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#delete_product" title="Eliminar"
                                @click.prevent="modalDeleteProduct({ id: product.id })">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="activeSection !== 'catalog'" class="table-list-toolbar">
            <div class="table-list-toolbar__rows">
                <span>Filas</span>
                <select class="custom-select custom-select-sm" v-model.number="pagination.per_page"
                    @change="getProducts({ page: 1, per_page: pagination.per_page })">
                    <option :value="10">10</option>
                    <option :value="20">20</option>
                    <option :value="50">50</option>
                </select>
            </div>
            <nav>
            <ul class="pagination">
                <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageProduct({ page: 1, per_page: pagination.per_page })"><span>Primera</span></a>
                </li>
                <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageProduct({ page: pagination.current_page - 1, per_page: pagination.per_page })"><span>Atras</span></a>
                </li>
                <li class="page-item" v-for="page in pagesNumber" v-bind:class="[page == isActived ? 'active' : '']"
                    :key="page">
                    <a class="page-link border-light bg-dark" href="#" @click.prevent="changePageProduct({ page, per_page: pagination.per_page })">{{
                        page
                        }}</a>
                </li>
                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageProduct({ page: pagination.current_page + 1, per_page: pagination.per_page })"><span>Siguiente</span></a>
                </li>
                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link border-light bg-dark" href="#"
                        @click.prevent="changePageProduct({ page: pagination.last_page, per_page: pagination.per_page })"><span>Ultima</span></a>
                </li>
            </ul>
            </nav>
        </div>
        <EditarProduct></EditarProduct>
        <EliminarProduct></EliminarProduct>
        <Inventory></Inventory>
        <RelacionModelos></RelacionModelos>
    </div>
</template>

<script>
import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapActions, mapGetters } from 'vuex'
import SelectProduct from './SelectProduct'
import SelectProvider from '../Provider/Select'
import EditarProduct from './EditarProduct'
import EliminarProduct from './EliminarProduct'
import Inventory from './Inventory'
import UtilidadDefect from '../Utilidad/UtilidadDefect'
import FleteDefect from '../Flete/FleteDefect'
import DeliveryTimeIndex from '../DeliveryTime/Index'
import RelacionModelos from './RelacionModelos'
import CatalogTemplates from './CatalogTemplates'

export default {
    components: { SelectProduct, SelectProvider, UtilidadDefect, FleteDefect, DeliveryTimeIndex, Inventory, EditarProduct, EliminarProduct, RelacionModelos, CatalogTemplates },
    data() {
        return {
            activeSection: 'products'
        }
    },
    computed: {
        ...mapState(['products', 'searchProduct', 'newProduct', 'pagination']),
        ...mapGetters(['isActived', 'pagesNumber']),
        tabContentClasses() {
            if (this.activeSection === 'catalog') {
                return 'tab-content p-0 bg-transparent'
            }

            return 'tab-content bg-white p-3'
        }
    },
    methods: {
        ...mapActions(['createProduct', 'getProducts', 'editProduct', 'changePageProduct', 'editInventory', 'modalDeleteProduct', 'openProductVehicleModels'])
    },
    created() {
        loadProgressBar()
        this.$store.dispatch('getProducts', { page: 1 })
    }
}
</script>

<style>
.product-admin-tabs .nav-item {
    margin: 0 0.55rem 0.55rem 0;
}

.product-admin-tabs .nav-link {
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 999px;
    background: rgba(15, 23, 42, 0.88);
    color: #dbeafe;
    font-weight: 600;
    letter-spacing: 0.01em;
    padding: 0.55rem 1rem;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.22);
    transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
}

.product-admin-tabs .nav-link:hover,
.product-admin-tabs .nav-link:focus {
    background: rgba(30, 41, 59, 0.96);
    color: #ffffff;
    transform: translateY(-1px);
}

.product-admin-tabs .nav-link.active,
.product-admin-tabs .show > .nav-link {
    background: linear-gradient(135deg, #0f766e, #0891b2);
    color: #ffffff;
    border-color: rgba(125, 211, 252, 0.65);
    box-shadow: 0 14px 30px rgba(8, 145, 178, 0.28);
}

.product-table-shell {
    overflow-x: visible;
}

.product-cell-strong,
.product-cell-wrap,
.product-cell-code {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.product-cell-strong {
    font-weight: 600;
}

.product-cell-code,
.product-cell-meta {
    font-size: 0.78rem;
}

.product-cell-meta,
.product-col-id,
.product-col-date,
.product-col-utility,
.product-col-freight,
.product-col-models {
    text-align: center;
}

.product-col-id,
.product-col-date,
.product-col-utility,
.product-col-freight,
.product-col-models,
.product-col-actions {
    padding-left: 0.15rem !important;
    padding-right: 0.15rem !important;
}

.product-action-cell {
    display: flex;
    flex-wrap: nowrap;
    justify-content: flex-end;
    align-items: center;
    gap: 0.25rem;
    white-space: nowrap;
}

.product-action-cell .btn {
    margin: 0;
    width: 28px;
    height: 28px;
    min-width: 28px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
}

@media (min-width: 769px) {
    .product-admin-view .product-table {
        table-layout: fixed;
        width: 100%;
    }

    .product-admin-view .product-table th,
    .product-admin-view .product-table td {
        padding: 0.28rem 0.32rem !important;
        font-size: 0.74rem;
        white-space: nowrap !important;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .product-admin-view .product-col-id,
    .product-admin-view .product-col-date,
    .product-admin-view .product-col-utility,
    .product-admin-view .product-col-freight,
    .product-admin-view .product-col-models,
    .product-admin-view .product-col-actions {
        padding-left: 0.15rem !important;
        padding-right: 0.15rem !important;
    }

    .product-admin-view .product-col-id {
        width: 3.6rem;
    }

    .product-admin-view .product-col-name {
        width: 37.5%;
    }

    .product-admin-view .product-col-client {
        width: 8.5%;
    }

    .product-admin-view .product-col-detail {
        width: 9%;
    }

    .product-admin-view .product-col-code {
        width: 9%;
    }

    .product-admin-view .product-col-date {
        width: 4.3rem;
    }

    .product-admin-view .product-col-utility {
        width: 3.5rem;
    }

    .product-admin-view .product-col-freight {
        width: 4.3rem;
    }

    .product-admin-view .product-col-models {
        width: 2.8rem;
    }

    .product-admin-view .product-col-actions {
        width: 8.1rem;
        overflow: visible !important;
        text-overflow: clip !important;
    }
}
</style>







