<template>
    <div class="col-lg-12">
        <h5 class="text-white">
            Formulario de Cotización Express
        </h5>
        <form action="POST" v-on:submit.prevent="createQuotationUserExpress">
            <div class="card w-50">
                <div class="card-body">
                    <div class="row">
                        <p v-if="errorsLaravel.length">
                            <b class="text-danger">Por favor, corrija los siguientes errores:</b>
                        <ul v-for="(error) in errorsLaravel" class="text-danger" :key="error.key">
                            <li>
                                {{ error.msg }}
                            </li>
                        </ul>
                        </p>
                    </div>
                    <div class="form-group">

                        <label class="font-weight-bold">Vehículo</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fa fa-car"></i></div>
                            </div>
                            <input v-validate="'required|min:6|max:60'"
                                :class="{ 'input': true, 'is-invalid': errors.has('patente o chasis') }"
                                class="form-control" type="text" name="patente o chasis"
                                v-model="formCotizacionExpress.patentchasis" placeholder="Patente o Chasis *" />
                        </div>
                        <p v-show="errors.has('patente o chasis')" class="text-danger">{{ errors.first('patente o chasis') }}</p>

                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Marca *</label>
                        <BrandSelector></BrandSelector>

                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Modelo *</label>
                        <ModelSelector></ModelSelector>

                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Año *</label>
                        <YearSelector></YearSelector>

                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Motor *</label>
                        <EngineSelector></EngineSelector>

                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Repuestos a solicitar *</label>
                        <textarea :class="{ 'input': true, 'is-invalid': errors.has('repuestos a solicitar') }"
                            class="form-control" v-validate="'required|min:6'" style="resize:none"
                            name="repuestos a solicitar" id="description" v-model="formCotizacionExpress.description"
                            placeholder="Repuestos..." cols="30" rows="9">
                                </textarea>
                        <p v-show="errors.has('repuestos a solicitar')" class="text-danger">{{ errors.first('repuestos a solicitar') }}</p>

                    </div>
                    <div class="form-group">
                        <h5>(*): Campos Requeridos</h5>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success form-control">
                        <i class="far fa-paper-plane"></i> Solicitar
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapActions, mapGetters } from 'vuex'
import BrandSelector from '../Quotationuser/BrandSelector'
import ModelSelector from '../Quotationuser/ModelSelector'
import YearSelector from '../Quotationuser/YearSelector'
import EngineSelector from '../Quotationuser/EngineSelector'

export default {
    components: { BrandSelector, ModelSelector, YearSelector, EngineSelector },
    computed: {
        ...mapState([
            'errorsLaravel', 'formCotizacionExpress'
        ])
    },
    methods: {
        ...mapActions([
            'createQuotationUserExpress'
        ]),
        scrollToTop() {
            window.scrollTo(0, 0);
        }
    }
}
</script>