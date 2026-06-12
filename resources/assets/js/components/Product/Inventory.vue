<template>
    <form action="POST" v-on:submit.prevent="">
        <div id="edit_inventory" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Gestión de Inventario</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="">
                            <div id="accordion">
                                <div class="card">
                                    <div class="card-header p-0" id="headingOne">
                                        <h5 class="mb-0">
                                            <button id="btn-inventory-card" class="btn btn-block text-left p-3"
                                                data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                                                aria-controls="collapseOne">Nuevo Inventario
                                                <span class="text-right"><i class="fas fa-arrows-alt-v"></i></span>
                                            </button>
                                        </h5>
                                    </div>
                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <form action="POST" v-on:submit.prevent="createInventory">
                                                <div class="form-group">
                                                    <label for="precio">Precio</label>
                                                    <input required type="number" name="codigo" class="form-control"
                                                        v-model="newInventory.price">
                                                </div>
                                                <div class="form-group">
                                                    <label for="cantidad">Cantidad</label>
                                                    <input required type="number" name="cantidad" class="form-control"
                                                        v-model="newInventory.quantity">
                                                </div>
                                                <button type="submit" class="btn btn-success form-control">
                                                    <i class="fas fa-plus-square"></i> Guardar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-responsive-new table-dark table-sm mt-3">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Total</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr v-for="inventory in inventories" :key="inventory.id">
                                        <td data-table-label="id">{{ inventory.id }}</td>
                                        <td data-table-label="Precio">{{ inventory.price | currency('$', 1) }}</td>
                                        <td data-table-label="Cantidad">{{ inventory.quantity }}</td>
                                        <td data-table-label="Total">{{ totalInventario(inventory) | currency('$', 0, {
                                            thousandsSeparator: '.'
                                        }) }}</td>
                                        <td>
                                            <a href="#" class="btn btn-danger pull-right btn-sm" data-toggle="modal"
                                                data-target="#delete" title="Eliminar"
                                                @click.prevent="deleteInventory({ id: inventory.id })">
                                                <i class="fas fa-trash-alt"></i>
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
    </form>
</template>

<script>
import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapGetters, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['inventories', 'newInventory', 'errorsLaravel', 'totalInventory']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions(['createInventory', 'deleteInventory']),
        totalInventario(item) {
            return item.price * item.quantity
        },
    },
}
</script>