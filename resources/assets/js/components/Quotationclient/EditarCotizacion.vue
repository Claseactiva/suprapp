<template>
    <form action="POST" v-on:submit.prevent="updateQuotationclient({ id: fillQuotationclient.id })">
        <div id="editQuotationclient" class="modal fade">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Cotización Formal</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6 mb-3">
                                <label for="client_id">Cliente</label>
                                <select id="client_id" class="form-control" v-model.number="fillQuotationclient.client_id">
                                    <option :value="1">Cliente General</option>
                                    <option v-for="clientLocal in optionsClient" :key="clientLocal.value" :value="clientLocal.value">
                                        {{ clientLocal.label }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="payment">Forma de Pago</label>
                                <select id="payment" class="form-control" v-model="fillQuotationclient.payment">
                                    <option value="">Contado</option>
                                    <option v-for="pagoLocal in optionsPago" :key="pagoLocal.value" :value="pagoLocal.label">
                                        {{ pagoLocal.label }}
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="client_text">Nombre Cliente</label>
                                <input id="client_text" type="text" class="form-control" v-model="fillQuotationclient.client_text">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="state">Estado</label>
                                <input id="state" type="text" class="form-control" v-model="fillQuotationclient.state">
                            </div>

                            <div class="col-lg-12 mb-3">
                                <label for="vehicle">Vehículo</label>
                                <input id="vehicle" type="text" class="form-control" v-model="fillQuotationclient.vehicle">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="ppu">P.P.U / N° Interno</label>
                                <input id="ppu" type="text" class="form-control" v-model="fillQuotationclient.ppu">
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="url">URL</label>
                                <input id="url" type="text" class="form-control" v-model="fillQuotationclient.url">
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
import { mapState, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['fillQuotationclient', 'optionsClient', 'optionsPago', 'errorsLaravel'])
    },
    methods: {
        ...mapActions(['updateQuotationclient'])
    }
}
</script>
