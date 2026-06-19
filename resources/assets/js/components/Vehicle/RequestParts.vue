<template>
    <form action="POST" v-on:submit.prevent="requestPartsVehicle">
        <div id="requestParts" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Cotizar Repuestos</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="parts">Repuestos a Solicitar</label>
                        <textarea :class="{ 'input': true, 'is-invalid': errors.has('description') }"
                            class="form-control" v-model="formCotizacion.description" style="resize:none"
                            placeholder="Repuestos..." cols="30" rows="9">
                        </textarea>
                        <p v-show="errors.has('description')" class="text-danger">{{ errors.first('description') }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">
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

export default {

    computed: {
        ...mapState(['formCotizacion'])
    },
    methods: {
        ...mapActions(['requestPartsVehicle'])
    }



}
</script>