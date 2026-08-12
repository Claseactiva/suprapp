<template>
    <div class="col-lg-12 fleet-admin">

        <h5 class="text-white">
            Flota de Equipos por Cliente
        </h5>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <label for="cliente">Cliente</label>
                        <v-select name="cliente" placeholder="Seleccionar Cliente" @input="setFleetClient"
                            :options="fleetClientOptions" :value="fleetSelectedClient">
                        </v-select>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="fleetSelectedClient" class="mt-3">

            <h6 class="text-white">
                Equipos de la flota
                <a href="#" class="btn btn-success btn-sm pull-right" @click.prevent="openAddEquipo" title="Agregar equipo">
                    <i class="fas fa-plus-circle"></i> Agregar equipo
                </a>
                <a href="#" class="btn btn-info btn-sm pull-right mr-2" data-toggle="modal" data-target="#bulkFleetImportModal">
                    <i class="fas fa-file-import"></i> Importar por texto
                </a>
            </h6>

            <div class="fleet-table-shell mt-3">
                <table class="table table-dark table-sm fleet-table mb-0">
                    <thead>
                        <tr>
                            <th class="fleet-col-patente">Patente</th>
                            <th class="fleet-col-interno">N° Interno</th>
                            <th class="fleet-col-chasis">Chasis</th>
                            <th class="fleet-col-tipo">Tipo</th>
                            <th class="fleet-col-marca">Marca</th>
                            <th class="fleet-col-modelo">Modelo</th>
                            <th class="fleet-col-anio">Año</th>
                            <th class="fleet-col-color">Color</th>
                            <th class="fleet-col-km">Km</th>
                            <th class="fleet-col-horometro">Horómetro</th>
                            <th class="fleet-col-motor">N° Motor</th>
                            <th class="fleet-col-motor-model">Modelo Motor</th>
                            <th class="fleet-col-arreglo">Arreglo CPL</th>
                            <th class="fleet-col-actions"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="fleetVehicles.length === 0">
                            <td colspan="14">Este cliente aun no tiene equipos cargados.</td>
                        </tr>
                        <tr v-for="vehicleLocal in fleetVehicles" :key="vehicleLocal.id">
                            <td class="fleet-cell-strong" :title="vehicleLocal.patent">{{ vehicleLocal.patent }}</td>
                            <td class="fleet-cell-wrap" :title="vehicleLocal.numero_interno">{{ vehicleLocal.numero_interno }}</td>
                            <td class="fleet-cell-wrap" :title="vehicleLocal.chasis">{{ vehicleLocal.chasis }}</td>
                            <td class="fleet-cell-wrap" :title="vehicleLocal.tipo">{{ vehicleLocal.tipo }}</td>
                            <td class="fleet-cell-wrap" :title="vehicleLocal.brand">{{ vehicleLocal.brand }}</td>
                            <td class="fleet-cell-wrap" :title="vehicleLocal.model">{{ vehicleLocal.model }}</td>
                            <td class="fleet-cell-meta">{{ vehicleLocal.year }}</td>
                            <td class="fleet-cell-meta">{{ vehicleLocal.color }}</td>
                            <td class="fleet-cell-meta">{{ vehicleLocal.km }}</td>
                            <td class="fleet-cell-meta">{{ vehicleLocal.horometro }}</td>
                            <td class="fleet-cell-wrap" :title="vehicleLocal.current_motor ? vehicleLocal.current_motor.motor.motor_number : ''">{{ vehicleLocal.current_motor ? vehicleLocal.current_motor.motor.motor_number : '' }}</td>
                            <td class="fleet-cell-wrap" :title="vehicleLocal.current_motor ? vehicleLocal.current_motor.motor.modelo_motor : ''">{{ vehicleLocal.current_motor ? vehicleLocal.current_motor.motor.modelo_motor : '' }}</td>
                            <td class="fleet-cell-wrap" :title="vehicleLocal.current_motor ? vehicleLocal.current_motor.motor.arreglo_cpl : ''">{{ vehicleLocal.current_motor ? vehicleLocal.current_motor.motor.arreglo_cpl : '' }}</td>
                            <td class="fleet-action-cell">
                                <a href="#" class="btn btn-warning btn-icon-sm" @click.prevent="editVehicle({ vehicleLocal })"
                                    data-toggle="tooltip" data-placement="top" title="Editar">
                                    <i class="far fa-edit"></i>
                                </a>
                                <a href="#" class="btn btn-danger btn-icon-sm" @click.prevent="deleteVehicle({ id: vehicleLocal.id })"
                                    data-toggle="tooltip" data-placement="top" title="Eliminar (papelera)">
                                    <i class="fas fa-ban"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        <div id="bulkFleetImportModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="mb-0">Importar Flota por Texto</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <small class="text-muted d-block mb-3">
                            Formato: Patente | N° Interno | Chasis/VIN | Tipo | Marca | Modelo | Año | Color | Km | Horómetro | N° Motor | Modelo Motor | Arreglo CPL
                            (Tipo: camión, generador, maquinaria, etc. Dejar vacío entre pipes si falta un dato, manteniendo las 13 columnas)
                        </small>

                        <textarea
                            v-model="bulkFleetText"
                            class="form-control"
                            rows="6"
                            placeholder="Pega aquí el texto ya convertido con el formato de arriba"></textarea>

                        <div class="d-flex flex-wrap mt-3" style="gap: .5rem">
                            <button type="button" class="btn btn-secondary" @click.prevent="copyFleetPrompt">
                                <i class="fas fa-robot"></i> Copiar Prompt
                            </button>
                            <button type="button" class="btn btn-info" @click.prevent="processBulkFleetText">
                                <i class="fas fa-cogs"></i> Procesar texto
                            </button>
                            <button type="button" class="btn btn-outline-secondary" @click.prevent="clearBulkFleetText">
                                <i class="fas fa-eraser"></i> Limpiar
                            </button>
                            <button type="button" class="btn btn-success"
                                @click.prevent="importBulkFleetItems"
                                :disabled="bulkFleetPreviewItems.length === 0">
                                <i class="fas fa-file-import"></i> Agregar a la flota
                            </button>
                        </div>

                        <div v-if="bulkFleetIgnoredLines.length" class="alert alert-warning mt-3 mb-0">
                            <strong>Lineas omitidas:</strong> {{ bulkFleetIgnoredLines.length }}
                            <div v-for="ignoredLine in bulkFleetIgnoredLines.slice(0, 5)" :key="`${ignoredLine.line}-${ignoredLine.reason}`">
                                {{ ignoredLine.line }} <small class="text-muted">({{ ignoredLine.reason }})</small>
                            </div>
                        </div>

                        <div v-if="bulkFleetPreviewItems.length" class="table-responsive mt-3">
                            <table class="table table-sm table-bordered table-dark mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Patente</th>
                                        <th>N° Interno</th>
                                        <th>Chasis/VIN</th>
                                        <th>Tipo</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Año</th>
                                        <th>Color</th>
                                        <th>Km</th>
                                        <th>Horómetro</th>
                                        <th>N° Motor</th>
                                        <th>Modelo Motor</th>
                                        <th>Arreglo CPL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in bulkFleetPreviewItems" :key="`${item.patent}-${index}`">
                                        <td>{{ index + 1 }}</td>
                                        <td>{{ item.patent }}</td>
                                        <td>{{ item.numero_interno }}</td>
                                        <td>{{ item.chasis }}</td>
                                        <td>{{ item.tipo }}</td>
                                        <td>{{ item.brand }}</td>
                                        <td>{{ item.model }}</td>
                                        <td>{{ item.year }}</td>
                                        <td>{{ item.color }}</td>
                                        <td>{{ item.km }}</td>
                                        <td>{{ item.horometro }}</td>
                                        <td>{{ item.motor_number }}</td>
                                        <td>{{ item.motor_model }}</td>
                                        <td>{{ item.arreglo_cpl }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Agregar></Agregar>
        <Editar></Editar>

    </div>
</template>

<script>

import $ from 'jquery'
import toastr from 'toastr'
import { mapState, mapActions } from 'vuex'
import Agregar from '../Vehicle/Agregar'
import Editar from '../Vehicle/Editar'

export default {
    components: { Agregar, Editar },
    data() {
        return {
            bulkFleetText: '',
            bulkFleetPreviewItems: [],
            bulkFleetIgnoredLines: []
        }
    },
    computed: {
        ...mapState(['fleetClientOptions', 'fleetSelectedClient', 'fleetVehicles', 'errorsLaravel'])
    },
    methods: {
        ...mapActions(['getFleetClientOptions', 'setFleetClient', 'importFleetBulk', 'setNewVehicleClient',
            'editVehicle', 'deleteVehicle']),
        openAddEquipo() {
            this.setNewVehicleClient(this.fleetSelectedClient.value)
            $('#create').modal('show')
        },
        copyFleetPrompt() {
            const prompt = 'Convierte el siguiente texto en una lista de equipos/vehiculos, una linea por equipo, '
                + 'usando EXACTAMENTE este formato separado por "|" (deja el espacio vacio entre pipes si falta un '
                + 'dato, pero manten siempre las 13 columnas):\n\n'
                + 'Patente | N° Interno | Chasis/VIN | Tipo | Marca | Modelo | Año | Color | Km | Horómetro | N° Motor | Modelo Motor | Arreglo CPL\n\n'
                + 'El Tipo es la clasificacion del equipo, por ejemplo: Camion, Generador, Maquinaria, '
                + 'Vehiculo Liviano, Perforadora, Equipos de Apoyo, Componente, u Otro. '
                + 'El N° Interno es el codigo o numero con el que el cliente identifica internamente ese equipo. '
                + 'Usa Km para vehiculos y Horometro para equipos que se miden en horas de uso (deja vacio el que no aplique).\n\n'
                + 'Ejemplo:\n'
                + 'ABCD11 | INT-001 | 9BWZZZ377VT004251 | Camion | Toyota | Hilux | 2020 | Blanco | 50000 | | MTR-123 | Cummins QSK19 | ARR-1\n'
                + 'EFGH22 | | | Generador | Nissan | Navara | 2021 | Negro | | 1200 | |\n\n'
                + 'No agregues texto adicional, encabezados ni numeracion, solo las lineas en ese formato. '
                + 'Este es el texto a convertir:\n\n'

            this.copyTextToClipboard(prompt, 'Prompt copiado, pegalo en tu IA junto con el texto a convertir')
        },
        processBulkFleetText() {
            const rawLines = this.bulkFleetText
                .split(/\r?\n/)
                .map(line => line.trim())
                .filter(Boolean)

            if (!rawLines.length) {
                toastr.warning('Pega el texto de la flota antes de procesar')
                this.bulkFleetPreviewItems = []
                this.bulkFleetIgnoredLines = []
                return
            }

            const previewItems = []
            const ignoredLines = []

            rawLines.forEach((line) => {
                const parsedItem = this.parseBulkFleetLine(line)

                if (!parsedItem) {
                    ignoredLines.push({ line, reason: 'Falta la patente' })
                    return
                }

                previewItems.push(parsedItem)
            })

            this.bulkFleetPreviewItems = previewItems
            this.bulkFleetIgnoredLines = ignoredLines

            if (!previewItems.length) {
                toastr.warning('No se encontraron lineas validas para agregar')
                return
            }

            toastr.success(`Se procesaron ${previewItems.length} lineas`)
        },
        parseBulkFleetLine(line) {
            const columns = line.split('|').map(part => part.trim())
            const [patent, numeroInterno, chasis, tipo, brand, model, year, color, km, horometro, motorNumber, motorModel, arregloCpl] = columns

            if (!patent) {
                return null
            }

            return {
                patent,
                numero_interno: numeroInterno || '',
                chasis: chasis || '',
                tipo: tipo || '',
                brand: brand || '',
                model: model || '',
                year: year || '',
                color: color || '',
                km: km || '',
                horometro: horometro || '',
                motor_number: motorNumber || '',
                motor_model: motorModel || '',
                arreglo_cpl: arregloCpl || ''
            }
        },
        clearBulkFleetText() {
            this.bulkFleetText = ''
            this.bulkFleetPreviewItems = []
            this.bulkFleetIgnoredLines = []
        },
        importBulkFleetItems() {
            if (!this.bulkFleetPreviewItems.length) {
                toastr.warning('Primero procesa un texto valido')
                return
            }

            this.importFleetBulk(this.bulkFleetPreviewItems)
            this.clearBulkFleetText()
            $('#bulkFleetImportModal').modal('hide')
        },
        copyTextToClipboard(text, successMessage) {
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    toastr.success(successMessage)
                }).catch(() => {
                    this.copyTextToClipboardLegacy(text, successMessage)
                })
                return
            }

            this.copyTextToClipboardLegacy(text, successMessage)
        },
        copyTextToClipboardLegacy(text, successMessage) {
            const textArea = document.createElement('textarea')
            textArea.value = text
            textArea.setAttribute('readonly', '')
            textArea.style.position = 'fixed'
            textArea.style.opacity = '0'
            document.body.appendChild(textArea)
            textArea.select()

            try {
                const successful = document.execCommand('copy')

                if (successful) {
                    toastr.success(successMessage)
                } else {
                    toastr.error('No se pudo copiar el prompt')
                }
            } catch (error) {
                toastr.error('No se pudo copiar el prompt')
            }

            document.body.removeChild(textArea)
        }
    },
    created() {
        this.getFleetClientOptions()
    }
}

</script>

<style>
.fleet-table-shell {
    overflow-x: visible;
}

.fleet-cell-strong,
.fleet-cell-wrap {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.fleet-cell-strong {
    font-weight: 600;
}

.fleet-cell-meta {
    font-size: 0.78rem;
    text-align: center;
}

.fleet-action-cell {
    display: flex;
    flex-wrap: nowrap;
    justify-content: flex-end;
    align-items: center;
    gap: 0.25rem;
    white-space: nowrap;
}

.fleet-action-cell .btn {
    margin: 0;
    width: 26px;
    height: 26px;
    min-width: 26px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
}

@media (min-width: 769px) {
    .fleet-admin .fleet-table {
        table-layout: fixed;
        width: 100%;
    }

    .fleet-admin .fleet-table th,
    .fleet-admin .fleet-table td {
        padding: 0.28rem 0.32rem !important;
        font-size: 0.74rem;
        white-space: nowrap !important;
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .fleet-admin .fleet-col-patente { width: 6rem; }
    .fleet-admin .fleet-col-interno { width: 5.5rem; }
    .fleet-admin .fleet-col-chasis { width: 9%; }
    .fleet-admin .fleet-col-tipo { width: 9%; }
    .fleet-admin .fleet-col-marca { width: 8%; }
    .fleet-admin .fleet-col-modelo { width: 9%; }
    .fleet-admin .fleet-col-anio { width: 3rem; }
    .fleet-admin .fleet-col-color { width: 4.5rem; }
    .fleet-admin .fleet-col-km { width: 4rem; }
    .fleet-admin .fleet-col-horometro { width: 4.5rem; }
    .fleet-admin .fleet-col-motor { width: 8%; }
    .fleet-admin .fleet-col-motor-model { width: 8%; }
    .fleet-admin .fleet-col-arreglo { width: 6%; }
    .fleet-admin .fleet-col-actions {
        width: 4.5rem;
        overflow: visible !important;
        text-overflow: clip !important;
    }
}
</style>
