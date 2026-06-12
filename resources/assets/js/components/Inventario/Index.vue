<template>
    <div class="col-lg-12">
        <h5 class="text-white">
            Inventario
            <a href="#" class="btn btn-success pull-right btn-sm" data-toggle="modal" data-target="#upload_invoice"
                title="Cargar">
                <i class="fas fa-plus-circle"></i>
            </a>
        </h5>
        <input type="text" name="searchInventory" class="form-control my-2" placeholder="Filtrar Producto..."
            v-model="searchInventory.name" @keyup="getInventories">
        <table class="table table-responsive-new table-dark table-sm mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Codigo</th>
                    <th>Cliente</th>
                    <th>Fecha Factura</th>
                    <th>Precio</th>
                    <th>Unidades</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="product in inventories">
                    <tr class="accordion-toggle" data-toggle="collapse" :data-target="'#product' + product.id">
                        <td data-table-label="ID">{{ product.id }}</td>
                        <td data-table-label="Nombre">{{ product.name }}</td>
                        <td data-table-label="Codigo">{{ product.codebar }}</td>
                        <td data-table-label="Cliente">{{ product.client.name }}</td>
                        <td></td>
                        <td></td>
                        <td data-table-label="Unidades">{{ totalUnidades(product) }}</td>
                        <td data-table-label="Total" :class="getCellColor(product)">${{ totalPrecio(product) }}</td>
                    </tr>
                    <tr v-for="inventory in product.inventories" :key="inventory.id"
                        :id="'product' + inventory.product_id" class="accordian-body collapse bg-secondary">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td data-table-label="Fecha Factura" v-if="inventory.fecha_fact != null">{{ inventory.fecha_fact }}
                        </td>
                        <td data-table-label="Precio">${{ inventory.price }}</td>
                        <td data-table-label="Unidades">{{ inventory.quantity }}</td>
                        <td data-table-label="Total">${{ totalInventario(inventory) }}</td>
                    </tr>
                </template>
            </tbody>
        </table>

        <nav>
            <ul class="pagination">
                <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link bg-dark" href="#" @click.prevent="changePageInventory({ page: 1 })">
                        <span>Primera</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page > 1">
                    <a class="page-link bg-dark" href="#"
                        @click.prevent="changePageInventory({ page: pagination.current_page - 1 })">
                        <span>Atrás</span>
                    </a>
                </li>

                <li class="page-item" v-for="page in pagesNumber" v-bind:class="[page == isActived ? 'active' : '']"
                    :key="page">
                    <a class="page-link bg-dark" href="#" @click.prevent="changePageInventory({ page })">
                        {{ page }}
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link bg-dark" href="#"
                        @click.prevent="changePageInventory({ page: pagination.current_page + 1 })">
                        <span>Siguiente</span>
                    </a>
                </li>

                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                    <a class="page-link bg-dark" href="#"
                        @click.prevent="changePageInventory({ page: pagination.last_page })">
                        <span>Última</span>
                    </a>
                </li>
            </ul>
        </nav>

        <Agregar></Agregar>
    </div>
</template>

<script>

import { loadProgressBar } from 'axios-progress-bar'
import Agregar from './Agregar'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: { Agregar },
    computed: {
        ...mapState(['inventories', 'pagination', 'offset', 'searchInventory']),
        ...mapGetters(['isActived', 'pagesNumber'])
    },
    methods: {
        ...mapActions(['getInventories', 'getUtilities', 'getFletes', 'changePageInventory']),
        totalInventario(item) {
            return item.price * item.quantity
        },
        totalUnidades(item) {
            let total = 0
            for (let i = 0; i < item.inventories.length; i++) {
                if (item.inventories[i].product_id == item.id) {
                    total += parseInt(item.inventories[i].quantity)
                }
            }
            return total
        },
        totalPrecio(item) {
            let total = 0

            for (let i = 0; i < item.inventories.length; i++) {
                if (item.inventories[i].product_id == item.id) {
                    total += parseInt(item.inventories[i].price * item.inventories[i].quantity)
                }
            }

            return total
        },
        getCellColor(item) {
            let uniquePrices = new Set();

            if (item.inventories.length > 1) {
                for (let i = 0; i < item.inventories.length; i++) {
                    if (item.inventories[i].product_id == item.id) {
                        uniquePrices.add(item.inventories[i].price);
                    }
                }

                if (uniquePrices.size === 1) {
                    return 'even-cell-color';
                } else {
                    return 'odd-cell-color';
                }
            }
        },
    },
    created() {
        loadProgressBar();
        this.$store.dispatch('getInventories', { page: 1 })
        this.$store.dispatch('getUtilities')
        this.$store.dispatch('getFletes')
    }
}

</script>
<style>
.even-cell-color {
    background-color: green;
}

.odd-cell-color {
    background-color: red;
}
</style>