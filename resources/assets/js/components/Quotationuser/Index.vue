<template>
    <div class="container">
        <form action="POST" v-on:submit.prevent="createQuotationUser">
            <div class="row">
                <h3>Formulario de Cotización</h3>
            </div>
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
            <div class="row">
                <h4>Contacto</h4>
                <div class="input-group input-group-icon">
                    <input v-validate="'required|alpha_spaces|min:3|max:60'"
                        :class="{ 'input': true, 'is-invalid': errors.has('nombre') }" class="form-control" type="text"
                        name="nombre" v-model="formCotizacion.name" placeholder="Nombre *" />
                    <div class="input-icon"><i class="fa fa-user"></i></div>
                    <p v-show="errors.has('nombre')" class="text-danger">{{ errors.first('nombre') }}</p>
                </div>
                <div class="input-group input-group-icon">
                    <input v-validate="'required|email'" :class="{ 'input': true, 'is-invalid': errors.has('correo') }"
                        class="form-control" type="email" name="correo" v-model="formCotizacion.email"
                        placeholder="Correo Electrónico *" />
                    <div class="input-icon"><i class="fa fa-envelope"></i></div>
                    <p v-show="errors.has('correo')" class="text-danger">{{ errors.first('correo') }}</p>
                </div>
                <div class="input-group input-group-icon">
                    <input v-validate="'required|min:6'" :class="{ 'input': true, 'is-invalid': errors.has('telefono') }"
                        class="form-control" type="text" name="telefono" placeholder="Teléfono *"
                        v-model="formCotizacion.phone" />
                    <div class="input-icon"><i class="fa fa-phone"></i></div>
                    <p v-show="errors.has('telefono')" class="text-danger">{{ errors.first('telefono') }}</p>
                </div>
            </div>
            <div class="row">
                <h4>Vehículo</h4>
                <div class="input-group input-group-icon">
                    <input v-validate="'required|min:6|max:60'"
                        :class="{ 'input': true, 'is-invalid': errors.has('patente o chasis') }" class="form-control"
                        type="text" name="patente o chasis" v-model="formCotizacion.patentchasis"
                        placeholder="Patente o Chasis *" />
                    <div class="input-icon"><i class="fa fa-car"></i></div>
                    <p v-show="errors.has('patente o chasis')" class="text-danger">{{ errors.first('patente o chasis')
                        }}</p>
                </div>
                <div class="row">
                    <div class="input-group">
                        <div>
                            <h4>Marca *</h4>
                        </div>
                        <BrandSelector></BrandSelector>
                    </div>
                    <div class="input-group">
                        <div>
                            <h4>Modelo *</h4>
                        </div>
                        <ModelSelector></ModelSelector>
                    </div>
                    <div class="input-group">
                        <div>
                            <h4>Año *</h4>
                        </div>
                        <YearSelector></YearSelector>
                    </div>
                    <div class="input-group">
                        <div>
                            <h4>Motor *</h4>
                        </div>
                        <EngineSelector></EngineSelector>
                    </div>
                </div>
            </div>
            <div class="row">
                <h4>Repuestos a solicitar *</h4>
                <div class="input-group">
                    <textarea :class="{ 'input': true, 'is-invalid': errors.has('repuestos a solicitar') }"
                        class="form-control" v-validate="'required|min:6'" style="resize:none"
                        name="repuestos a solicitar" id="description" v-model="formCotizacion.description"
                        placeholder="Repuestos..." cols="30" rows="9">
                    </textarea>
                    <p v-show="errors.has('repuestos a solicitar')" class="text-danger">{{ errors.first('repuestos a solicitar') }}</p>
                </div>
            </div>
            <div class="row">
                <h4>(*): Campos Requeridos</h4>
            </div>
            <div class="row">
                <div class="input-group" style="text-align:center;">
                    <button type="submit" class="btn btn-success" @click="scrollToTop">
                        Enviar
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapActions, mapGetters } from 'vuex'
import BrandSelector from './BrandSelector'
import ModelSelector from './ModelSelector'
import YearSelector from './YearSelector'
import EngineSelector from './EngineSelector'

export default {
    components: { BrandSelector, ModelSelector, YearSelector, EngineSelector },
    computed: {
        ...mapState([
            'errorsLaravel', 'formCotizacion'
        ])
    },
    methods: {
        ...mapActions([
            'createQuotationUser'
        ]),
        scrollToTop() {
            window.scrollTo(0, 0);
        }
    }

}
</script>