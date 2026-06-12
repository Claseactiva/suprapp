<template>
    <div class="col-lg-12">
        <h5 class="text-white">
            Lista de Precios
        </h5>
        <div class="row mt-3">
            <div class="col-lg-3">
                <input type="text" name="searchInventory" class="form-control my-2" placeholder="Filtrar Producto..."
                    v-model="searchInventory.name" @keyup="getInventories">
            </div>
        </div>
        <table class="table table-responsive-new table-dark table-sm mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Código</th>
                    <th>Proveedor</th>
                    <th>Fecha Factura</th>
                    <th>Precio</th>
                    <th>Unidades</th>
                    <th>Precio Promedio</th>
                    <th>Precio Mas Alto</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="product in inventories">
                    <tr class="accordion-toggle" data-toggle="collapse" :data-target="'#product' + product.id">
                        <td data-table-label="ID">{{ product.id }}</td>
                        <td data-table-label="Producto">{{ product.name }}</td>
                        <td data-table-label="Código">{{ product.codebar }}</td>
                        <td data-table-label="Proveedor">{{ product.client.name }}</td>
                        <td></td>
                        <td></td>
                        <td data-table-label="Unidades">{{ totalUnidades(product) }}</td>

                        <td data-table-label="Precio Promedio">{{ calcularPromedioPreciosCompra(product) | currency('$',
                            0,
                            { thousandsSeparator: '.' }) }}</td>
                        <td data-table-label="Precio Mas Alto"
                            v-if="calcularPrecioCompraMasAlto(product) !== calcularPromedioPreciosCompra(product)">{{
                                calcularPrecioCompraMasAlto(product) | currency('$', 0,
                                    { thousandsSeparator: '.' }) }}</td>


                        <td data-table-label="Precio Mas Alto" v-else>0</td>
                    </tr>
                    <tr v-for="inventory in product.inventories" :key="inventory.id"
                        :id="'product' + inventory.product_id" class="accordian-body collapse bg-secondary">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td data-table-label="Fecha Factura" v-if="inventory.fecha_fact != null">{{ inventory.fecha_fact
                            }}
                        </td>
                        <td data-table-label="Fecha Factura" v-else>0</td>
                        <td data-table-label="Precio">${{ inventory.price }}</td>
                        <td data-table-label="Unidades">{{ inventory.quantity }}</td>
                        <td></td>
                        <td></td>
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

    </div>
</template>

<script>
import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapActions, mapGetters } from 'vuex'


export default {
    components: {},
    computed: {
        ...mapState(['inventories', 'pagination', 'offset', 'searchInventory']),
        ...mapGetters(['isActived', 'pagesNumber'])
    },
    methods: {
        ...mapActions(['getInventories', 'changePageInventory']),
        totalUnidades(item) {
            let total = 0;
            for (let i = 0; i < item.inventories.length; i++) {
                if (item.inventories[i].product_id == item.id) {
                    total += parseInt(item.inventories[i].quantity);
                }
            }
            return total;
        },
        calcularPromedioPreciosCompra(item) {
            let sumatoriaPrecios = 0;
            let cantidadProductos = 0;

            if (item.inventories.length > 0) {
                for (let i = 0; i < item.inventories.length; i++) {
                    if (item.inventories[i].product_id === item.id && item.inventories[i].quantity > 0) {
                        sumatoriaPrecios += parseInt(item.inventories[i].price * item.inventories[i].quantity);
                        cantidadProductos += item.inventories[i].quantity;
                    }
                }
            }

            let flete = parseInt(item.flete);
            let utilidad = (parseInt(item.utilidad) / 100) + 1;


            if (cantidadProductos === 0) {
                return 0;
            }

            let price_total = ((((sumatoriaPrecios / cantidadProductos) * 1.19) * utilidad) + flete)
            let price_round = this.roundedPrice(price_total)

            return price_round;
        },
        calcularPrecioCompraMasAlto(item) {

            let uniquePrices = new Set();
            let flete = parseInt(item.flete);
            let utilidad = (parseInt(item.utilidad) / 100) + 1;

            if (item.inventories.length > 0) {
                for (let i = 0; i < item.inventories.length; i++) {
                    if (item.inventories[i].product_id == item.id && item.inventories[i].quantity > 0) {
                        uniquePrices.add(item.inventories[i].price);
                    }
                }
            }

            if (uniquePrices.size === 0) {
                return 0;
            }

            const valorMasAlto = Math.max(...[...uniquePrices].map(Number));
            let price_formatted = (((valorMasAlto * 1.19) * utilidad) + flete)

            let price_round = this.roundedPrice(price_formatted)

            return price_round;
        },
        roundedPrice(price) {
            const rounded = Math.ceil(price / 10) * 10;
            return rounded;
        }
    },
    created() {
        loadProgressBar();
        this.$store.dispatch('getInventories', { page: 1 })
    }
}
</script>