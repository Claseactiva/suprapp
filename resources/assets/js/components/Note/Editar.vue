<template>

    <form action="POST" v-on:submit.prevent="updateNote({ id: fillNote.id })">
        <div id="edit" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Editar Nota</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <label for="precio">Precio</label>
                        <input v-validate="'required|min:1'"
                                :class="{'input': true, 'is-invalid': errors.has('precio') }"
                                type="number"
                                name="precio"
                                class="form-control" v-model="fillNote.price">
                        <p v-show="errors.has('precio')" class="text-danger">{{ errors.first('precio') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.price }}</p>
                        </div>

                        <label for="detalle">Detalle</label>
                        <textarea v-validate="'required|min:1'"
                                :class="{'input': true, 'is-invalid': errors.has('detalle') }"
                                name="detalle" rows="12"
                                class="form-control" v-model="fillNote.detail"></textarea>
                        <p v-show="errors.has('detalle')" class="text-danger">{{ errors.first('detalle') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.detail }}</p>
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
        ...mapState(['fillNote', 'errorsLaravel']),
        ...mapGetters([])
    },
    methods:{
        ...mapActions(['updateNote'])
    },
}
</script>
