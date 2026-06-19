<template>
    <div>
        <div class="col-lg-12">
            <h5 class="text-white">Generar Boleta</h5>
        </div>
        <div class="col-lg-12">
            <div id="accordion">
                <div class="card">
                    <div class="card-header p-0" id="headingOne">
                        <h5 class="mb-0">
                            <button id="btn-user-card" class="btn btn-block text-left p-3" data-toggle="collapse"
                                data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Nueva
                                Boleta<span class="text-right"><i class="fas fa-arrows-alt-v"></i></span></button>
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row mb-5">
                                <div class="col-lg-3">
                                    <label>Fecha (Opcional)</label>
                                    <datepicker :value="data1.fecha" v-model="data1.fecha" @keyup.enter="juntarDatos"
                                        :bootstrap-styling="true"></datepicker>
                                </div>
                                <div class="col-lg-3">
                                    <label>Monto</label>
                                    <input name="precio" min="0" v-model="data2.precio" @keyup.enter="crearArreglo"
                                        type="number" class="form-control" placeholder="Ej: Precio">
                                </div>
                                <div class="col-lg-3 align-self-end">
                                    <button @click="crearArreglo" type="submit" class="btn btn-success form-control"><i
                                            class="fas fa-plus-square"></i> Agregar</button>
                                </div>
                            </div>
                            <section class="data">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Valor Ingresado</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th scope="col">Monto Total</th>
                                            <th>$ {{ totalBoleta }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </section>
                            <div class="row">
                                <div class="col-lg-3">
                                    <input @click="juntarDatos" type="button" value="Generar Boleta"
                                        class="btn btn-success form-control" />
                                </div>
                                <div class="col-lg-3 align-self-center">
                                    <a v-bind:href="formatImage(resultado)">{{ obtenerNombre(resultado) }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { loadProgressBar } from "axios-progress-bar";
import { mapState, mapActions, mapGetters } from "vuex";
import Datepicker from "vuejs-datepicker";
import { formatImage } from '../../utils/imageUtils';

export default {
    computed: {
        ...mapState(['resultado', 'data1', 'data2', 'arrayBoleta', 'errorsLaravel']),
        totalBoleta: function () {
            var sumaTotal;
            var sumaTotalBoleta = 0;
            this.arrayBoleta.map(function (bar) {
                sumaTotal = 1 * bar.precio;
                sumaTotalBoleta = sumaTotalBoleta + sumaTotal;
                sumaTotal = 0;
            });
            return sumaTotalBoleta;
        },
    },
    components: {
        Datepicker,
    },

    methods: {
        ...mapActions(['juntarDatos', 'crearArreglo']),
        /*Este procedimiento no se pudo realizar en el archivo mutations.js, por ende lo he realizado aquí*/
        borrar(borrar_id) {
            this.arrayBoleta.splice(borrar_id, 1);
        },
        obtenerNombre(texto) {
            const match = texto.match(/Boleta/);
            if (match) {
                return match[0]
            } else {
                return ''
            }
        },
        formatImage
    },
};
</script>
