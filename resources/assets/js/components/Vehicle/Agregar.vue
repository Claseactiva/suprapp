<template>

    <form action="POST" v-on:submit.prevent="createVehicleUser">
        <div id="create" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Registrar Vehículo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">

                            <label for="patente">Patente</label>
                            <input required type="text" name="patente" class="form-control" v-model="newVehicle.patent">
                        </div>

                        <div class="form-group">
                            <label for="patente">Chasis</label>
                            <input required type="text" name="chasis" class="form-control" v-model="newVehicle.chasis">
                        </div>

                        <div class="form-group">
                            <label for="marca">Marca</label>
                            <BrandSelector />
                        </div>

                        <div class="form-group">
                            <label for="modelo">Modelo</label>
                            <ModelSelector />
                        </div>

                        <div class="form-group">
                            <label for="anio">Año</label>
                            <YearSelector />
                        </div>

                        <div class="form-group">
                            <label for="engine">Motor</label>
                            <EngineSelector />
                        </div>

                        <div class="form-group">
                            <label for="color">Color</label>
                            <input required type="text" name="color" class="form-control" v-model="newVehicle.color">
                        </div>

                        <div class="form-group">
                            <label for="km">Kilometraje</label>
                            <input required type="number" name="km" class="form-control" v-model="newVehicle.km">
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" :disabled="!completeVehicleCreate">
                            <i class="fas fa-plus-square"></i> Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</template>

<script>

import { mapState, mapGetters, mapActions } from 'vuex';

import BrandSelector from '../Quotationuser/BrandSelector'
import ModelSelector from '../Quotationuser/ModelSelector'
import YearSelector from '../Quotationuser/YearSelector'
import EngineSelector from '../Quotationuser/EngineSelector'

export default {
    components: { BrandSelector, ModelSelector, YearSelector, EngineSelector },
    computed: {
        ...mapState(['newVehicle', 'errorsLaravel']),
        ...mapGetters(['completeVehicleCreate'])
    },
    methods: {
        ...mapActions(['createVehicleUser'])
    },
}
</script>
