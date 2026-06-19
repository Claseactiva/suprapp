<template>

    <form action="POST" v-on:submit.prevent="updateFacebookShipping({ id: fillFacebookShipping.id })">
        <div id="modalEditFacebook" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <label for="url">URL</label>
                        <div class="input-group">
                            <input required class="form-control" type="url" name="url"
                                v-model="fillFacebookShipping.url" />
                            <div class="input-group-append">
                                <a class="btn btn-primary" v-if="fillFacebookShipping.url != null"
                                    :href="fillFacebookShipping.url" target="_blank" type="button"><i
                                        class="fab fa-facebook-messenger"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Actualizar
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
        ...mapState(['fillFacebookShipping', 'errorsLaravel']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions(['updateFacebookShipping'])
    },
}
</script>
