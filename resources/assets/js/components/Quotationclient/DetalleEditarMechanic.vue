<template>

    <form action="POST" v-on:submit.prevent="updateDetailclient({ id: fillDetailclient.id })">
        <div id="editDetailClientMechanic" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Producto Cotización Formal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <label for="producto">Producto</label>
                        <input required type="text" name="producto" class="form-control"
                            v-model="fillDetailclient.product">


                        <label for="precio">Precio</label>
                        <input required type="number" name="precio" class="form-control"
                            v-model="fillDetailclient.price" @keyup="sumTotalEditProductMechanic">


                        <label for="dias">Días de Plazo</label>
                        <select name="dias" class="form-control" v-model="fillDetailclient.days">
                            <option v-for="deliveryTime in availableDeliveryTimes(fillDetailclient.days)"
                                :key="deliveryTime.id || deliveryTime.label" :value="deliveryTime.label">
                                {{ deliveryTime.label }}
                            </option>
                        </select>


                        <label for="total_neto">Total neto</label>
                        <input type="number" name="total_neto" class="form-control" v-model="fillDetailclient.total"
                            disabled>


                        <label for="total_IVA">Total + IVA</label>
                        <input type="number" name="total_IVA" class="form-control" v-model="fillDetailclient.totalIVA"
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
import { mapState, mapGetters, mapActions } from 'vuex';

export default {
    computed: {
        ...mapState(['fillDetailclient', 'errorsLaravel', 'deliveryTimes']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions(['updateDetailclient', 'sumTotalEditProductMechanic']),
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
