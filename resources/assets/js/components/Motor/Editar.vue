<template>

    <form action="POST" v-on:submit.prevent="updateMotor({ id: fillMotor.id })">
        <div id="edit_motor_asset" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Motor</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="motor_number">N° de Serie</label>
                            <input type="text" name="motor_number" class="form-control" v-model="fillMotor.motor_number">

                            <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                <p>{{ error.motor_number }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="numero_interno">N° Interno</label>
                            <input type="text" name="numero_interno" class="form-control" v-model="fillMotor.numero_interno">

                            <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                <p>{{ error.numero_interno }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="arreglo_cpl">Arreglo / CPL</label>
                            <input type="text" name="arreglo_cpl" class="form-control" v-model="fillMotor.arreglo_cpl">

                            <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                <p>{{ error.arreglo_cpl }}</p>
                            </div>
                        </div>
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
    computed:{
        ...mapState(['fillMotor', 'errorsLaravel'])
    },
    methods:{
        ...mapActions(['updateMotor'])
    },
}
</script>
