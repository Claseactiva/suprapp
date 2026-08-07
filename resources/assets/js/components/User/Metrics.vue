<template>
    <div id="userMetrics" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Métricas de {{ selectedUserForMetrics ? selectedUserForMetrics.name : '' }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <p class="text-muted mb-3">
                        Solo conteos: esta cuenta es independiente, su detalle no es visible desde aquí.
                    </p>

                    <div class="alert alert-info" v-if="!userMetrics">
                        Cargando métricas...
                    </div>

                    <div class="row" v-else>
                        <div class="col-6 col-md-4 mb-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">{{ userMetrics.clients_total }}</h3>
                                    <small>Clientes / Proveedores</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">{{ userMetrics.quotations }}</h3>
                                    <small>Cotizaciones</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">{{ userMetrics.purchase_orders }}</h3>
                                    <small>Órdenes de Compra</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">{{ userMetrics.products }}</h3>
                                    <small>Productos / Inventario</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">{{ userMetrics.envios }}</h3>
                                    <small>Envíos</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-md-4 mb-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-0">{{ userMetrics.tipos_pagos }}</h3>
                                    <small>Formas de Pago</small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-secondary">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    computed: {
        ...mapState(['selectedUserForMetrics', 'userMetrics'])
    }
}
</script>
