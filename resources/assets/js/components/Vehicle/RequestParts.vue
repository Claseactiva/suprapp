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
                        <label for="newPartName">Repuestos a Solicitar</label>
                        <div class="d-flex" style="gap: .5rem">
                            <div class="flex-grow-1">
                                <v-select name="newPartName" placeholder="Buscar en la biblioteca o escribir uno nuevo..."
                                    :options="templateOptionLabels" :taggable="true" :push-tags="true"
                                    @input="addPartLine" :value="null">
                                </v-select>
                            </div>
                        </div>

                        <table class="table table-dark table-sm mt-3 mb-0" v-if="partLines.length">
                            <tbody>
                                <tr v-for="(line, index) in partLines" :key="index">
                                    <td>
                                        <input type="text" class="form-control form-control-sm" v-model="line.text">
                                    </td>
                                    <td class="text-right" style="width: 40px">
                                        <a href="#" class="btn btn-danger btn-icon-sm" @click.prevent="removePartLine(index)"
                                            data-toggle="tooltip" data-placement="top" title="Quitar">
                                            <i class="fas fa-ban"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p v-else class="text-muted mt-3 mb-0">Todavía no agregaste repuestos.</p>

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
import $ from 'jquery'
import { mapState, mapGetters, mapActions } from 'vuex';

export default {

    data() {
        return {
            partLines: []
        }
    },
    computed: {
        ...mapState(['formCotizacion', 'productCatalogTemplateSuggestions']),
        templateOptionLabels() {
            return this.productCatalogTemplateSuggestions.map(suggestion => suggestion.product_name)
        }
    },
    watch: {
        partLines: {
            deep: true,
            handler() {
                this.formCotizacion.description = this.partLines.map(line => line.text).join('\n')
            }
        }
    },
    methods: {
        ...mapActions(['requestPartsVehicle', 'getProductCatalogTemplateSuggestions']),
        addPartLine(name) {
            const text = (name || '').trim()

            if (!text) {
                return
            }

            this.partLines.push({ text })
        },
        removePartLine(index) {
            this.partLines.splice(index, 1)
        }
    },
    created() {
        this.getProductCatalogTemplateSuggestions()
    },
    mounted() {
        $('#requestParts').on('hidden.bs.modal', () => {
            this.partLines = []
        })
    }


}
</script>
