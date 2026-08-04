<template>

    <form action="POST" v-on:submit.prevent="updateMotorSpec({ id: fillMotorSpec.id })">
        <div id="edit_motorspec" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Especificación de Motor</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="col-0">
                            <label for="raw_label">Motor</label>
                            <input v-validate="'required|min:1|max:90'"
                                    :class="{'input': true, 'is-invalid': errors.has('raw_label') }"
                                    type="text"
                                    name="raw_label"
                                    class="form-control" v-model="fillMotorSpec.raw_label">
                            <p v-show="errors.has('raw_label')" class="text-danger">{{ errors.first('raw_label') }}</p>

                            <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                                <p>{{ error.raw_label }}</p>
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
        ...mapState(['fillMotorSpec', 'errorsLaravel'])
    },
    methods:{
        ...mapActions(['updateMotorSpec'])
    },
}
</script>
