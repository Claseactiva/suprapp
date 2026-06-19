<template>

    <form action="POST" v-on:submit.prevent="createNote">
        <div id="create" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Ingresar Nota</h4>
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
                                class="form-control" v-model="newNote.price">
                        <p v-show="errors.has('precio')" class="text-danger">{{ errors.first('precio') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.price }}</p>
                        </div>

                        <label for="detalle">Detalle</label>
                        <textarea v-validate="'required|min:1'"
                                :class="{'input': true, 'is-invalid': errors.has('detalle') }"
                                name="detalle" rows="12"
                                class="form-control" v-model="newNote.detail"></textarea>
                        <p v-show="errors.has('detalle')" class="text-danger">{{ errors.first('detalle') }}</p>

                        <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                            <p>{{ error.detail }}</p>
                        </div>

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
    computed:{
        ...mapState(['newNote', 'errorsLaravel']),
        ...mapGetters([])
    },
    methods:{
        ...mapActions(['createNote'])
    },
}
</script>

