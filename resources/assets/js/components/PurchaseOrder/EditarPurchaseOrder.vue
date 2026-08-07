<template>
    <form action="POST" v-on:submit.prevent="updatePurchaseOrder({ id: fillPurchaseOrder.id })">
        <div id="editPurchaseOrder" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Orden de Compra</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label for="supplier_id">Proveedor</label>
                                <select id="supplier_id" class="form-control" v-model.number="fillPurchaseOrder.supplier_id">
                                    <option :value="null">Sin proveedor registrado</option>
                                    <option v-for="supplierLocal in optionsSupplier" :key="supplierLocal.value" :value="supplierLocal.value">
                                        {{ supplierLocal.label }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="payment">Forma de Pago</label>
                                <select id="payment" class="form-control" v-model="fillPurchaseOrder.payment">
                                    <option value="">Contado</option>
                                    <option v-for="pagoLocal in optionsPago" :key="pagoLocal.value" :value="pagoLocal.label">
                                        {{ pagoLocal.label }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="state">Estado</label>
                                <input id="state" type="text" class="form-control" v-model="fillPurchaseOrder.state">
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label for="order_number">N° Orden de Compra</label>
                                <input id="order_number" type="text" class="form-control" v-model="fillPurchaseOrder.order_number">
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label for="buyer">Comprador</label>
                                <input id="buyer" type="text" class="form-control" v-model="fillPurchaseOrder.buyer">
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label for="currency">Moneda</label>
                                <input id="currency" type="text" class="form-control" v-model="fillPurchaseOrder.currency">
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label for="promised_date">Fecha Acordada</label>
                                <input id="promised_date" type="date" class="form-control" v-model="fillPurchaseOrder.promised_date">
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label for="shipping_method">Método de Envío</label>
                                <input id="shipping_method" type="text" class="form-control" v-model="fillPurchaseOrder.shipping_method">
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label for="requested_by">Solicitado Por</label>
                                <input id="requested_by" type="text" class="form-control" v-model="fillPurchaseOrder.requested_by">
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label for="ship_to">Enviar a</label>
                                <input id="ship_to" type="text" class="form-control" v-model="fillPurchaseOrder.ship_to">
                            </div>

                            <div class="col-lg-12 mb-3">
                                <label for="observaciones">Observaciones</label>
                                <textarea id="observaciones" class="form-control" rows="2" v-model="fillPurchaseOrder.observaciones"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Guardar cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['fillPurchaseOrder', 'optionsPago', 'errorsLaravel']),
        ...mapGetters(['optionsSupplier'])
    },
    methods: {
        ...mapActions(['updatePurchaseOrder'])
    }
}
</script>
