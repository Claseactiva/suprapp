<template>
    <div class="col-lg-12">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-new-product-tab" data-toggle="pill" href="#pills-new-product"
                    role="tab" aria-controls="pills-new-product" aria-selected="false">Nuevo Producto</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-utility-tab" data-toggle="pill" href="#pills-utility" role="tab"
                    aria-controls="pills-utility" aria-selected="false">Utilidad Por Defecto</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-freight-tab" data-toggle="pill" href="#pills-freight" role="tab"
                    aria-controls="pills-freight" aria-selected="false">Flete Por Defecto</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-delivery-time-tab" data-toggle="pill" href="#pills-delivery-time" role="tab"
                    aria-controls="pills-delivery-time" aria-selected="false">Plazos de Entrega</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-product-catalog-tab" data-toggle="pill" href="#pills-product-catalog" role="tab"
                    aria-controls="pills-product-catalog" aria-selected="false">Catalogo Sugerencias</a>
            </li>
        </ul>
        <div class="tab-content bg-white p-3" id="pills-tabContent">
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
        <div class="row mt-3">
            <div class="col-lg-6">
                <input type="text" v-model="searchProduct.name" @keyup="getProducts({ page: 1, per_page: pagination.per_page })" class="form-control"
                    placeholder="Filtrar Producto...">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-responsive-new table-dark table-sm mt-3">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Detalle</th>
                        <th>Cliente</th>
                        <th>Codigo</th>
                        <th>Fecha</th>
                        <th>%Utilidad</th>
                        <th>Flete</th>
                        <th>Modelos</th>
                        <th>Accion</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products" :key="product.id">
                        <td data-table-label="ID">{{ product.id }}</td>
                        <td data-table-label="Producto">{{ product.name }}</td>
                        <td data-table-label="Detalle">{{ product.detail }}</td>
                        <td data-table-label="Cliente">{{ product.client.name }}</td>
                        <td data-table-label="Codigo">{{ product.codebar }}</td>
                        <td data-table-label="Fecha">{{ product.created_at | moment('DD/MM/YYYY') }}</td>
                        <td data-table-label="%Utilidad">{{ product.utilidad }}%</td>
                        <td data-table-label="Flete">${{ product.flete }}</td>
                        <td data-table-label="Modelos">{{ product.related_vehicle_models_count || 0 }}</td>
                        <td>
                            <a href="#" class="btn btn-primary pull-right btn-sm" data-toggle="modal"
                                data-target="#product_vehicle_models" title="Modelos relacionados"
                                @click.prevent="openProductVehicleModels({ product })">
                                <i class="fas fa-car-side"></i>
                            </a>
                            <a href="#" class="btn btn-warning pull-right btn-sm" data-toggle="modal"
                                data-target="#edit_product" title="Editar"
                                @click.prevent="editProduct({ product, page: pagination.current_page })">
                                <i class="far fa-edit"></i>
                            </a>
                            <a href="#" class="btn btn-info pull-right btn-sm" data-toggle="modal"
                                data-target="#edit_inventory" title="Inventario"
                                @click.prevent="editInventory({ id: product.id, inventories: product.inventories })">
                                <i class="fas fa-boxes"></i>
                            </a>
                            <a href="#" class="btn btn-danger pull-right btn-sm" data-toggle="modal"
                                data-target="#delete_product" title="Eliminar"
                                @click.prevent="modalDeleteProduct({ id: product.id })">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-list-toolbar">
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
    computed: {
        ...mapState(['products', 'searchProduct', 'newProduct', 'pagination']),
        ...mapGetters(['isActived', 'pagesNumber'])
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

<style></style>