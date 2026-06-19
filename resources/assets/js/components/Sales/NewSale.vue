<template>
	<form action="POST" v-on:submit.prevent="">
		<div id="create" class="modal fade modal-fullscreen">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header bg-dark text-white">
						<h4>Nueva Venta</h4>
						<button type="button" class="close text-white" data-dismiss="modal"
							aria-label="Close"><span>&times;</span></button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-lg-12">
								<form action="POST" v-on:submit.prevent="addToCart">
									<div class="row mb-3">
										<div class="col-6">
											<label for="select-product">Producto</label>
											<SelectProductSale></SelectProductSale>
										</div>
										<div class="col-6">
											<label for="code">Código</label>
											<input type="text" class="form-control" v-model="newSale.codebar">
										</div>
									</div>
									<div class="row">
										<div class="col-lg-4">
											<label for="quantity">Cantidad</label>
											<input class="form-control" type="number" min="1" @keyup="sumTotalProductSale"
												v-model="newSale.quantity">
										</div>
										<div class="col-lg-4">
											<label for="total">Precio Venta</label>
											<input class="form-control" type="number" v-model="newSale.price_sale" disabled>
										</div>
										<div class="col-lg-4 mt-2">
											<label />
											<button :disabled="!newSale.price_sale" type="submit"
												class="btn btn-info form-control">Agregar</button>
										</div>
									</div>
								</form>
								<div class="row">
									<div class="col-lg-12">
										<h5 class="text-white">Productos</h5>
										<table class="table table-responsive-new table-dark table-sm mt-3">
											<thead>
												<tr>
													<th>Producto</th>
													<th>Cantidad</th>
													<th>Valor Neto</th>
													<th>Valor Total</th>
													<th>Acción</th>
												</tr>
											</thead>
											<tbody>
												<tr v-for="product in cart" :key="product.id">
													<td data-table-label="PRODUCTO">{{ product.name }}</td>
													<td data-table-label="CANTIDAD">{{ product.quantity }}</td>
													<td data-table-label="VALOR NETO">{{ product.totalNeto | currency('$', 0, { thousandsSeparator: '.' }) }}
													</td>
													<td data-table-label="VALOR TOTAL">{{ product.total | currency('$', 0, { thousandsSeparator: '.' }) }}
													</td>
													<td>
														<a href="#" class="btn btn-danger btn-sm"
															@click.prevent="removeFromCart({ id: product.id })"
															data-toggle="tooltip" data-placement="top" title="Eliminar">
															<i class="fas fa-ban"></i>
														</a>
													</td>
												</tr>
												<tr>
													<td></td>
													<td></td>
													<td data-table-label="VALOR NETO">{{ cartNeto | currency('$', 0, { thousandsSeparator: '.' }) }}</td>
													<td data-table-label="VALOR TOTAL">{{ cartTotal | currency('$', 0, { thousandsSeparator: '.' }) }}</td>
													<td></td>
												</tr>
												<tr>
													<td></td>
													<td></td>
													<td>Forma de Pago:</td>
													<td>{{ formapago }}</td>
													<td></td>
												</tr>
												<tr>
													<td></td>
													<td></td>
													<td>Descuento:</td>
													<td>{{ aplicardescuento }}%</td>
													<td>
														<a href="#" class="btn btn-danger btn-sm"
															@click.prevent="removeDescuento()" data-toggle="tooltip"
															data-placement="top" title="Eliminar">
															<i class="fas fa-ban"></i>
														</a>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button href="#" :disabled="cartNeto === 0" class="col-lg-1 btn btn-success form-control"
							data-toggle="modal" data-target="#formaPago"><i class="fas fa-money-bill-wave"></i> Forma de
							Pago</button>
						<button :disabled="cartNeto === 0 || newDescuento.contar_descuentos === 0"
							v-on:click="aplicarDescuento" class="col-lg-2 btn btn-success form-control"><i
								class="fas fa-tags"></i> Aplicar
							Descuento</button>
						<button :disabled="cartNeto === 0" v-on:click="createSale"
							class="col-lg-1 btn btn-success form-control"><i class="fas fa-check"></i> Venta</button>
					</div>
				</div>
			</div>
		</div>
		<AgregarFormaPago></AgregarFormaPago>
	</form>
</template>
<script>
import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapActions, mapMutations } from 'vuex'
import SelectProductSale from './SelectProductSale'
import AgregarFormaPago from './AgregarFormaPago'

export default {
	components: { SelectProductSale, AgregarFormaPago },
	computed: {
		...mapState(['newSale', 'cartNeto', 'cartTotal', 'formapago', 'aplicardescuento', 'newDescuento', 'cart']),
	},
	methods: {
		...mapActions(['createSale', 'aplicarDescuento', 'sumTotalProductSale', 'addToCart', 'removeFromCart', 'removeDescuento']),
	},
	created() {
		this.$store.dispatch('descuentoDefect')
	}
}
</script>

