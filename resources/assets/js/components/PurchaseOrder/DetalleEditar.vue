<template>

    <form action="POST" v-on:submit.prevent="updatePurchaseOrderDetail({ id: fillPurchaseOrderDetail.id })">
        <div id="editPurchaseOrderDetail" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Producto Orden de Compra</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <label for="producto">Producto</label>
                        <input required type="text" name="producto" class="form-control"
                            v-model="fillPurchaseOrderDetail.product">

                        <label for="codigo">Código</label>
                        <input type="text" name="codigo" class="form-control" v-model="fillPurchaseOrderDetail.detail">

                        <label for="precio">Precio</label>
                        <input required type="number" step="0.01" name="precio" class="form-control"
                            v-model="fillPurchaseOrderDetail.price" @keyup="sumTotalEditPurchaseOrderDetail">

                        <label for="cantidad">Cantidad</label>
                        <input required type="number" step="0.01" name="cantidad" class="form-control"
                            v-model="fillPurchaseOrderDetail.quantity" @keyup="sumTotalEditPurchaseOrderDetail">

                        <label for="dias">Días de Plazo</label>
                        <select name="dias" class="form-control" v-model="fillPurchaseOrderDetail.days">
                            <option v-for="deliveryTime in availableDeliveryTimes(fillPurchaseOrderDetail.days)"
                                :key="deliveryTime.id || deliveryTime.label" :value="deliveryTime.label">
                                {{ deliveryTime.label }}
                            </option>
                        </select>

                        <label for="total">Total</label>
                        <input type="number" step="0.01" name="total" class="form-control" v-model="fillPurchaseOrderDetail.total"
                            disabled>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</template>

<script>
import { mapState, mapActions } from 'vuex';

export default {
    computed: {
        ...mapState(['fillPurchaseOrderDetail', 'errorsLaravel', 'deliveryTimes'])
    },
    methods: {
        ...mapActions(['updatePurchaseOrderDetail', 'sumTotalEditPurchaseOrderDetail']),
        availableDeliveryTimes(currentValue) {
            const options = [...this.deliveryTimes]

            if (currentValue && !options.some(deliveryTime => deliveryTime.label === currentValue)) {
                options.unshift({
                    id: `current-${currentValue}`,
                    label: currentValue
                })
            }

            return options
        }
    },
}
</script>
