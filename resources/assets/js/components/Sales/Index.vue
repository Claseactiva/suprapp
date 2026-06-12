<template>
	<div class="col-lg-12">
		<h5 class="text-white">
			Ventas
			<a href="#" class="btn btn-success pull-right btn-sm" data-toggle="modal" data-target="#create"
				title="Agregar"><i class="fas fa-plus-circle"></i> Generar Venta</a>

			<a class="btn btn-success pull-right btn-sm" data-toggle="collapse" href="#showcalendar" role="button"
				aria-expanded="false" aria-controls="showcalendar">
				Ventas Anteriores
			</a>
		</h5>
		<NewSale></NewSale>

		<div class="row mt-3">
			<div class="collapse col-lg-2 col-md-12 mb-3" id="showcalendar">
				<Datepicker valueType="format" :inline="true" v-model="calendar.search" />
				<div class="row">
					<div class="col-6 pr-0">
						<button class="btn btn-success btn-block" style="border-radius: 0;"
							@click.prevent="allSales({ page: 1, calendar: calendar.search })"><i
								class="fas fa-search"></i></button>
					</div>
					<div class="col-6 pl-0">
						<button class="btn btn-danger btn-block" style="border-radius: 0;"
							@click.prevent="allSales({ page: 1, calendar: calendar.search = '' })"><i
								class="fas fa-times"></i></button>
					</div>
				</div>
			</div>
			<div class="col-lg-10 col-md-12">
				<div v-if="!sales.length">
					<table class="table table-responsive-new table-dark table-sm">
						<thead>
							<tr>
								<th>Producto</th>
								<th>Precio</th>
								<th>Cantidad</th>
								<th>Utilidad</th>
								<th>Neto</th>
								<th>Total</th>
								<th>Fecha</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="7">
									<h4 class="text-center text-white">¡NO HAY RESULTADOS!</h4>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div v-else>
					<table class="table table-responsive-new table-dark table-sm">
						<thead>
							<tr>
								<th>Producto</th>
								<th>Forma de Pago</th>
								<th>Descuento</th>
								<th>Cantidad</th>
								<th>Neto</th>
								<th>Total</th>
								<th>Fecha</th>
							</tr>
						</thead>
						<tbody v-for="sale in sales" :key="sale.id">
							<tr data-toggle="collapse" aria-expanded="false" :aria-controls="'#sale' + sale.id"
								:data-target="'#sale' + sale.id">
								<td style="width: 25%"></td>
								<td style="width: 10%"></td>
								<td></td>
								<td></td>
								<td></td>
								<td data-table-label="TOTAL">{{ descuento(sale) | currency('$', 0, { thousandsSeparator: '.' }) }}</td>
								<td data-table-label="FECHA">{{ $moment(sale.created_at).format('DD-MM-YYYY HH:mm:ss') }}</td>
								<td>
									<a href="#" class="btn btn-primary btn-sm"
										@click.prevent="generarRecibo({ id: sale.id })" role="button">
										<i class="fas fa-file-invoice-dollar"></i> Recibo
									</a>

									<a href="#" class="btn btn-danger btn-sm"
										@click.prevent="eliminarVenta({ id: sale.id })" role="button">
										<i class="fas fa-trash-alt"></i>
									</a>
								</td>
							</tr>
							<tr v-for="product_sale in sale.product_sales" :key="product_sale.id" :id="'sale' + sale.id"
								class="collapse">
								<td data-table-label="PRODUCTO">{{ product_sale.product.name }}</td>
								<td data-table-label="FORMA DE PAGO">{{ sale.forma_pago }}</td>
								<td data-table-label="DESCUENTO">{{ sale.descuento * 100 }}%</td>
								<td data-table-label="CANTIDAD">{{ product_sale.quantity }}</td>
								<td data-table-label="NETO">{{ product_sale.neto | currency('$', 0, { thousandsSeparator: '.' }) }}</td>
								<td data-table-label="TOTAL">{{ descuento(product_sale) | currency('$', 0, { thousandsSeparator: '.' }) }}</td>
								<td></td>
								<td></td>
							</tr>

						</tbody>
					</table>
				</div>
				<div class="row">
					<div class="col-10">
						<nav>
							<ul class="pagination">
								<li class="page-item" v-if="pagination.current_page > 1">
									<a class="page-link border-light bg-dark" href="#"
										@click.prevent="changePageSales({ page: 1, calendar: calendar.search })">
										<span>Primera</span>
									</a>
								</li>

								<li class="page-item" v-if="pagination.current_page > 1">
									<a class="page-link border-light bg-dark" href="#"
										@click.prevent="changePageSales({ page: pagination.current_page - 1, calendar: calendar.search })">
										<span>Atrás</span>
									</a>
								</li>

								<li class="page-item" v-for="page in pagesNumber"
									v-bind:class="[page == isActived ? 'active' : '']" :key="page">
									<a class="page-link border-light bg-dark" href="#"
										@click.prevent="changePageSales({ page, calendar: calendar.search })">
										{{ page }}
									</a>
								</li>

								<li class="page-item" v-if="pagination.current_page < pagination.last_page">
									<a class="page-link border-light bg-dark" href="#"
										@click.prevent="changePageSales({ page: pagination.current_page + 1, calendar: calendar.search })">
										<span>Siguiente</span>
									</a>
								</li>

								<li class="page-item" v-if="pagination.current_page < pagination.last_page">
									<a class="page-link border-light bg-dark" href="#"
										@click.prevent="changePageSales({ page: pagination.last_page, calendar: calendar.search })">
										<span>Última</span>
									</a>
								</li>
							</ul>
						</nav>
					</div>
					<div class="col-2">
						<button class="btn btn-primary btn-block" @click.prevent="cierreCajaZ()"><i
								class="fas fa-file-invoice-dollar"></i> Cierre de caja Z</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'
//import Datepicker from 'vuejs-datetimepicker'
import Datepicker from 'vue2-datepicker';
import 'vue2-datepicker/index.css';
import 'vue2-datepicker/locale/es';
import NewSale from './NewSale'

export default {
	components: { NewSale, Datepicker },
	computed: {
		...mapState(['calendar', 'sales', 'searchFecha', 'pagination']),
		...mapGetters(['isActived', 'pagesNumber'])
	},
	methods: {
		...mapActions(['cierreCajaZ', 'allSales', 'changePageSales', 'generarRecibo', 'eliminarVenta']),
		descuento(product_sale) {
			let total_descuento = 0;
			if (product_sale.descuento > 0) {
				total_descuento = product_sale.total - (product_sale.total * product_sale.descuento)
			} else {
				total_descuento = product_sale.total
			}
			return total_descuento;
		}
	},
	created() {
		this.$store.dispatch('allSales', { page: 1, calendar: this.calendar.search })
	},

}
</script>

<style>
.mx-btn {
	color: #fff;
}

.mx-datepicker-main {
	color: #fff;
	background-color: #343a40;
	border: 0;
}

.mx-calendar {
	width: 100%;
}

.mx-input {
	display: block;
	width: 100%;
	height: calc(1.6em + 0.75rem + 2px);
	padding: 0.375rem 0.75rem;
	font-size: 1rem;
	line-height: 1.5;
	color: #495057;
	background-color: #fff;
	background-clip: padding-box;
	border: 1px solid #ced4da;
	border-top-left-radius: 0.25rem;
	border-top-right-radius: 0;
	border-bottom-right-radius: 0;
	border-bottom-left-radius: 0.25rem;
	transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
</style>