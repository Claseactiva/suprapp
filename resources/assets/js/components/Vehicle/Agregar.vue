<template>

    <form action="POST" v-on:submit.prevent="createVehicleUser">
        <div id="create" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>Registrar Vehículo</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">

                            <label for="patente">Patente</label>
                            <div class="input-group">
                                <input required type="text" name="patente" class="form-control" v-model="newVehicle.patent">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" :disabled="buscandoPatente"
                                        @click="buscarPorPatente">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                            <small v-if="mensajePatente" class="form-text text-muted">{{ mensajePatente }}</small>
                        </div>

                        <div class="form-group">
                            <label for="patente">Chasis</label>
                            <input required type="text" name="chasis" class="form-control" v-model="newVehicle.chasis">
                        </div>

                        <div class="form-group">
                            <label for="numero_interno">N° Interno</label>
                            <input type="text" name="numero_interno" class="form-control" v-model="newVehicle.numero_interno">
                        </div>

                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <v-select :options="optionsTiposVehiculoLabels" v-model="newVehicle.tipo"
                                :taggable="true" :push-tags="true" placeholder="Camión, Generador, Maquinaria, etc.">
                            </v-select>
                        </div>

                        <div class="form-group">
                            <label for="marca">Marca</label>
                            <BrandSelector />
                        </div>

                        <div class="form-group">
                            <label for="modelo">Modelo</label>
                            <ModelSelector />
                            <small v-if="sugerenciaModelo" class="form-text">
                                ¿Quisiste decir "{{ sugerenciaModelo.label }}"? (según registro de patente, {{ sugerenciaModelo.confianza }}% de coincidencia)
                                <button type="button" class="btn btn-sm btn-link p-0 ml-1" @click="confirmarSugerenciaModelo">Usar este modelo</button>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="anio">Año</label>
                            <YearSelector />
                            <small v-if="anioReferencia" class="form-text text-muted">
                                Año según registro de patente: {{ anioReferencia }} (no encontrado en el catálogo para este modelo, selecciónalo manualmente si corresponde)
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="engine">Motor</label>
                            <EngineSelector />
                            <small v-if="cilindradaReferencia" class="form-text text-muted">
                                Cilindrada según registro de patente: {{ cilindradaReferencia }} (no se encontró una única coincidencia en el catálogo de motores, selecciónalo manualmente)
                            </small>
                        </div>

                        <div class="form-group" v-if="rol !== 'client'">
                            <label for="motor_number">N° de Serie</label>
                            <input type="text" name="motor_number" class="form-control" v-model="newVehicle.motor_number">
                        </div>

                        <div class="form-group" v-if="rol !== 'client'">
                            <label for="motor_model">Modelo de Motor</label>
                            <input type="text" name="motor_model" class="form-control" v-model="newVehicle.motor_model">
                        </div>

                        <div class="form-group" v-if="rol !== 'client'">
                            <label for="arreglo_cpl">Arreglo / CPL</label>
                            <input type="text" name="arreglo_cpl" class="form-control" v-model="newVehicle.arreglo_cpl">
                        </div>

                        <div class="form-group">
                            <label for="color">Color</label>
                            <input required type="text" name="color" class="form-control" v-model="newVehicle.color">
                        </div>

                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="trackKm" v-model="newVehicle.trackKm">
                                <label class="form-check-label" for="trackKm">Kilometraje</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="trackHorometro" v-model="newVehicle.trackHorometro">
                                <label class="form-check-label" for="trackHorometro">Horómetro</label>
                            </div>

                            <div class="row">
                                <div class="form-group" :class="newVehicle.trackKm && newVehicle.trackHorometro ? 'col-6' : 'col-12'"
                                    v-if="newVehicle.trackKm">
                                    <label for="km">Kilometraje</label>
                                    <input type="number" name="km" class="form-control" v-model="newVehicle.km">
                                </div>
                                <div class="form-group" :class="newVehicle.trackKm && newVehicle.trackHorometro ? 'col-6' : 'col-12'"
                                    v-if="newVehicle.trackHorometro">
                                    <label for="horometro">Horómetro</label>
                                    <input type="number" name="horometro" class="form-control" v-model="newVehicle.horometro">
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" :disabled="!completeVehicleCreate">
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

import BrandSelector from '../Quotationuser/BrandSelector'
import ModelSelector from '../Quotationuser/ModelSelector'
import YearSelector from '../Quotationuser/YearSelector'
import EngineSelector from '../Quotationuser/EngineSelector'

export default {
    components: { BrandSelector, ModelSelector, YearSelector, EngineSelector },
    data() {
        return {
            buscandoPatente: false,
            mensajePatente: '',
            sugerenciaModelo: null,
            anioReferencia: null,
            cilindradaReferencia: null,
            cilindradaPendiente: null
        }
    },
    computed: {
        ...mapState(['newVehicle', 'errorsLaravel', 'rol', 'optionsTiposVehiculo', 'optionsVYear', 'optionsVEngine']),
        ...mapGetters(['completeVehicleCreate']),
        optionsTiposVehiculoLabels() {
            return this.optionsTiposVehiculo.map(tipo => tipo.label)
        }
    },
    methods: {
        ...mapActions(['createVehicleUser', 'allTiposVehiculos', 'setVBrand', 'setVModel', 'setVYear', 'setVEngine']),
        // Prueba local: autocompletar Chasis/Marca/Modelo/Año/N° de motor buscando
        // la patente en la base de referencia (vehicle_reference_data). Chasis y
        // N° de motor son texto libre y siempre confiables; marca/modelo/año son
        // "mejor esfuerzo" (se dejan vacíos para elegir a mano si no calzan con el catálogo).
        async buscarPorPatente() {
            const patente = (this.newVehicle.patent || '').trim()
            if (!patente) return

            this.buscandoPatente = true
            this.mensajePatente = ''
            this.sugerenciaModelo = null
            this.anioReferencia = null
            this.cilindradaReferencia = null
            this.cilindradaPendiente = null
            try {
                const res = await fetch('vehiculo-referencia/' + encodeURIComponent(patente))
                const data = await res.json()

                if (!data) {
                    this.mensajePatente = 'Sin coincidencia en la base de referencia.'
                    return
                }

                if (data.chasis) this.newVehicle.chasis = data.chasis
                if (data.motor_number) this.newVehicle.motor_number = data.motor_number
                this.cilindradaPendiente = data.cilindrada_texto || null

                if (!data.marca_match) {
                    this.mensajePatente = `Coincidencia encontrada (${data.marca_texto} ${data.modelo_texto}), pero la marca no está en tu catálogo. Chasis y N° de motor se completaron; marca/modelo/año selecciónalos manualmente.`
                    return
                }

                await this.setVBrand(data.marca_match)

                if (!data.modelo_match) {
                    if (data.modelo_sugerido) {
                        this.sugerenciaModelo = data.modelo_sugerido
                        this.anioReferencia = data.ano_fabricacion || null
                        this.mensajePatente = `Marca autocompletada. Modelo sugerido más abajo, confírmalo si corresponde.`
                    } else {
                        this.anioReferencia = data.ano_fabricacion || null
                        this.mensajePatente = `Marca autocompletada. El modelo del registro ("${data.modelo_texto}") no coincide con tu catálogo, selecciónalo manualmente.`
                    }
                    return
                }

                await this.setVModel(data.modelo_match)
                await this.intentarSeleccionarAnio(data.ano_fabricacion)
            } catch (e) {
                this.mensajePatente = 'Error al consultar la base de referencia.'
            } finally {
                this.buscandoPatente = false
            }
        },
        // El usuario confirma con un clic la sugerencia de modelo (nunca se
        // autoselecciona sola): recién ahí se intenta encadenar el año.
        async confirmarSugerenciaModelo() {
            if (!this.sugerenciaModelo) return
            const anio = this.anioReferencia
            await this.setVModel(this.sugerenciaModelo)
            this.sugerenciaModelo = null
            this.anioReferencia = null
            await this.intentarSeleccionarAnio(anio)
        },
        async intentarSeleccionarAnio(anioTexto) {
            if (!anioTexto) {
                this.mensajePatente = 'Marca y modelo autocompletados.'
                return
            }
            const years = await this.esperarOpciones(() => this.optionsVYear)
            const anioMatch = years.find(y => String(y.label).includes(String(anioTexto)))
            if (anioMatch) {
                await this.setVYear(anioMatch)
                this.mensajePatente = 'Coincidencia encontrada: marca, modelo y año autocompletados.'
                await this.intentarSeleccionarCilindrada()
            } else {
                this.anioReferencia = anioTexto
                this.mensajePatente = 'Marca y modelo autocompletados. Año no encontrado en catálogo para este modelo.'
            }
        },
        // Solo se puede intentar una vez que el año quedó seleccionado, porque el
        // catálogo de motores depende de marca+modelo+año. El número de cilindrada
        // (ej. "2.0") es mucho menos ambiguo que un nombre de modelo, pero igual
        // solo se autoselecciona si hay una única coincidencia entre las opciones
        // de motor disponibles para ese año; si hay más de una o ninguna, se deja
        // como referencia para elegir a mano.
        async intentarSeleccionarCilindrada() {
            const cilindrada = this.cilindradaPendiente
            this.cilindradaPendiente = null
            if (!cilindrada) return

            const motores = await this.esperarOpciones(() => this.optionsVEngine)
            const patron = new RegExp('(^|[^0-9.])' + cilindrada.replace('.', '\\.') + '([^0-9]|$)')
            const candidatos = motores.filter(m => patron.test(String(m.label)))

            if (candidatos.length === 1) {
                await this.setVEngine(candidatos[0])
                this.mensajePatente += ' Motor autocompletado según cilindrada.'
            } else {
                this.cilindradaReferencia = cilindrada
            }
        },
        esperarOpciones(getter, timeoutMs = 4000, intervalMs = 100) {
            const inicio = Date.now()
            return new Promise(resolve => {
                const check = () => {
                    const valor = getter()
                    if ((valor && valor.length > 0) || Date.now() - inicio > timeoutMs) {
                        resolve(valor || [])
                    } else {
                        setTimeout(check, intervalMs)
                    }
                }
                check()
            })
        }
    },
    created() {
        this.allTiposVehiculos()
    }
}
</script>
