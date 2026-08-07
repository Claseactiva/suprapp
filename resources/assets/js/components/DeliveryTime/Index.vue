<template>
    <div>
        <form action="POST" v-on:submit.prevent="createDeliveryTime">
            <div class="form-row align-items-end">
                <div class="form-group col-lg-7">
                    <label for="delivery-time-label">Plazo</label>
                    <input id="delivery-time-label" required type="text" name="delivery_time_label" class="form-control"
                        v-model.trim="newDeliveryTime.label" placeholder="24 a 48 Hrs">
                </div>
                <div class="form-group col-lg-3">
                    <div class="form-check mt-4">
                        <input id="delivery-time-default" class="form-check-input" type="checkbox"
                            v-model="newDeliveryTime.is_default">
                        <label class="form-check-label" for="delivery-time-default">
                            Dejar por defecto
                        </label>
                    </div>
                </div>
                <div class="form-group col-lg-2">
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-plus-square"></i> Guardar
                    </button>
                </div>
            </div>
        </form>

        <div v-if="deliveryTimes.length === 0" class="mt-2">
            <small class="text-muted d-block mb-1">Sugerencias:</small>
            <button v-for="suggestion in suggestions" :key="suggestion" type="button"
                class="btn btn-outline-secondary btn-sm mr-2 mb-2" @click.prevent="useSuggestion(suggestion)">
                {{ suggestion }}
            </button>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-sm table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Plazo</th>
                        <th width="120px">Defecto</th>
                        <th width="90px">Accion</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="deliveryTime in deliveryTimes" :key="deliveryTime.id">
                        <td>{{ deliveryTime.label }}</td>
                        <td class="text-center">
                            <input type="radio" name="default_delivery_time" :checked="deliveryTime.is_default"
                                @change="setDefaultDeliveryTime({ deliveryTime })">
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn btn-danger btn-icon-sm"
                                @click.prevent="deleteDeliveryTime({ id: deliveryTime.id })">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
                    <tr v-if="deliveryTimes.length === 0">
                        <td colspan="3" class="text-center">Sin plazos creados</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
    data() {
        return {
            suggestions: ['24 a 48 Hrs', '3 a 5 días hábiles', '7 días hábiles']
        }
    },
    computed: {
        ...mapState(['deliveryTimes', 'newDeliveryTime'])
    },
    methods: {
        ...mapActions(['getDeliveryTimes', 'createDeliveryTime', 'setDefaultDeliveryTime', 'deleteDeliveryTime']),
        useSuggestion(label) {
            this.newDeliveryTime.label = label
            this.createDeliveryTime()
        }
    },
    created() {
        this.getDeliveryTimes()
    }
}
</script>
