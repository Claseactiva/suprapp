<template>
    <div class="col-12">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="company-tab" data-toggle="tab" href="#company" role="tab"
                    aria-controls="company" aria-selected="false">Datos Empresa</a>
            </li>
            <li class="nav-item" v-if="newCompany.logo !== ''">
                <a class="nav-link" id="user-tab" data-toggle="tab" href="#user" role="tab" aria-controls="user"
                    aria-selected="true">Logo</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="devices-tab" data-toggle="tab" href="#devices" role="tab"
                    aria-controls="devices" aria-selected="false" @click="getDeviceSessions">Mis Dispositivos</a>
            </li>

            <li class="nav-item">
                <a class="nav-link" id="appearance-tab" data-toggle="tab" href="#appearance" role="tab"
                    aria-controls="appearance" aria-selected="false">Apariencia</a>
            </li>

        </ul>
        <div class="tab-content bg-white" id="myTabContent">
            <div class="tab-pane fade p-4" id="user" role="tabpanel" aria-labelledby="user-tab">

                <div class="form-group">
                    <label for="logo">Logo Corporativo</label>
                    <input id="logo" type="file" class="form-control" @change="uploadLogo({ evt: $event })"
                        accept=".png, .jpeg, .jpg">
                </div>

                <div class="form-group" v-if="newCompany.logo !== ''">
                    <img :src="`/storage${newCompany.logo}`" class="logo" alt="...">
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-success" :disabled="attachment.length === 0" @click="updateCompanyLogo">
                        <i class="fas fa-plus-square"></i> Editar
                    </button>
                </div>

            </div>
            <div class="tab-pane fade p-4" id="devices" role="tabpanel" aria-labelledby="devices-tab">

                <p class="mb-3">Límite actual: <strong>{{ deviceLimit }}</strong> dispositivo(s).</p>

                <div class="alert alert-info" v-if="!deviceSessionsLoading && deviceSessions.length === 0">
                    No hay dispositivos activos registrados.
                </div>

                <div class="card mb-3" v-for="session in deviceSessions" :key="session.id">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <p class="font-weight-bold mb-1">
                                {{ session.deviceName }}
                                <span class="badge badge-success ml-2" v-if="session.isCurrent">Este dispositivo</span>
                            </p>
                            <p class="mb-0 text-muted" style="font-size: 0.85rem;">
                                IP: {{ session.ipAddress }} · Última actividad: {{ session.lastSeenAt | moment('DD/MM/YYYY H:mm') }}
                            </p>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm" @click="revokeDeviceSession(session)">
                            <i class="fas fa-trash-alt"></i> Revocar
                        </button>
                    </div>
                </div>

            </div>
            <div class="tab-pane fade p-4" id="appearance" role="tabpanel" aria-labelledby="appearance-tab">

                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="lightTablesSwitch"
                        v-model="lightTables" @change="toggleLightTables">
                    <label class="custom-control-label" for="lightTablesSwitch">Tablas en fondo claro</label>
                </div>
                <p class="text-muted mt-2" style="font-size: 0.85rem;">
                    Invierte el color de las tablas (fondo oscuro con letras blancas &rarr; fondo claro con letras oscuras).
                    Esta preferencia se guarda en este navegador.
                </p>

                <hr>

                <h5>Imagen de Fondo</h5>
                <p class="text-muted" style="font-size: 0.85rem;">
                    Elige la imagen de fondo que prefieras. Esta preferencia se guarda en este navegador.
                </p>

                <div class="row">
                    <div class="col-6 col-md-3 mb-3" v-for="image in backgroundImages" :key="image.id">
                        <div class="card" :class="{ 'border-success': image.path === selectedBackgroundImagePath }"
                            style="cursor: pointer;" @click="selectBackgroundImage(image)">
                            <img :src="formatImage(image.path)" class="card-img-top" style="height: 100px; object-fit: cover;" alt="...">
                            <div class="card-body p-2 text-center">
                                <span class="badge badge-success" v-if="image.path === selectedBackgroundImagePath">
                                    En uso
                                </span>
                                <span class="badge badge-secondary" v-else>
                                    {{ image.is_light ? 'Clara' : 'Oscura' }}
                                </span>
                                <a href="#" class="btn btn-danger btn-icon-sm ml-1" v-if="isAdmin"
                                    @click.stop.prevent="deleteBackgroundImage(image.id)" title="Eliminar">
                                    <i class="far fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="isAdmin">
                    <hr>
                    <h6>Subir nueva imagen de fondo (admin)</h6>

                    <div class="form-group">
                        <label for="backgroundImageFile">Archivo de imagen</label>
                        <input id="backgroundImageFile" type="file" class="form-control"
                            @change="setBackgroundImageFile($event)" accept=".png, .jpeg, .jpg">
                    </div>

                    <div class="custom-control custom-switch mb-3">
                        <input type="checkbox" class="custom-control-input" id="newBackgroundIsLight"
                            v-model="newBackgroundImage.is_light">
                        <label class="custom-control-label" for="newBackgroundIsLight">
                            Es una foto clara/luminosa
                        </label>
                    </div>

                    <div v-for="(error, index) in errorsLaravel" class="text-danger" :key="index">
                        <p>{{ error.image }}</p>
                    </div>

                    <button type="button" class="btn btn-success" :disabled="!attachmentBackgroundImage"
                        @click="uploadBackgroundImage">
                        <i class="fas fa-plus-square"></i> Agregar a la paleta
                    </button>
                </div>

            </div>
            <div class="tab-pane fade show active p-4" id="company" role="tabpanel" aria-labelledby="company-tab">

                <div class="form-group">
                    <label for="rut">Rut</label>
                    <input required v-validate="'required'" :class="{ 'input': true, 'is-invalid': errors.has('rut') }"
                        type="text" name="rut" class="form-control" v-model="newCompany.rut">
                    <p v-show="errors.has('rut')" class="text-danger">{{ errors.first('rut') }}</p>
                </div>

                <div class="form-group">
                    <label for="razonSocial">Razón Social</label>
                    <input required v-validate="'required'"
                        :class="{ 'input': true, 'is-invalid': errors.has('razonSocial') }" type="text"
                        name="razonSocial" class="form-control" v-model="newCompany.razonSocial">
                    <p v-show="errors.has('razonSocial')" class="text-danger">{{ errors.first('razonSocial') }}</p>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input required v-validate="'required'"
                        :class="{ 'input': true, 'is-invalid': errors.has('email') }" type="text" name="email"
                        class="form-control" v-model="newCompany.email">
                    <p v-show="errors.has('email')" class="text-danger">{{ errors.first('email') }}</p>
                </div>

                <div class="form-group">
                    <label for="phone">Telefono</label>
                    <input required v-validate="'required'"
                        :class="{ 'input': true, 'is-invalid': errors.has('phone') }" type="text" name="phone"
                        class="form-control" v-model="newCompany.phone">
                    <p v-show="errors.has('phone')" class="text-danger">{{ errors.first('phone') }}</p>
                </div>

                <div class="form-group">
                    <label for="address">Dirección</label>
                    <input required v-validate="'required'"
                        :class="{ 'input': true, 'is-invalid': errors.has('address') }" type="text" name="address"
                        class="form-control" v-model="newCompany.address">
                    <p v-show="errors.has('address')" class="text-danger">{{ errors.first('address') }}</p>
                </div>

                <div class="form-group">
                    <label for="comuna">Comuna</label>
                    <input required v-validate="'required'"
                        :class="{ 'input': true, 'is-invalid': errors.has('comuna') }" type="text" name="comuna"
                        class="form-control" v-model="newCompany.comuna">
                    <p v-show="errors.has('comuna')" class="text-danger">{{ errors.first('comuna') }}</p>
                </div>

                <div class="form-group">
                    <label for="giro">Giro</label>
                    <input required v-validate="'required'" :class="{ 'input': true, 'is-invalid': errors.has('giro') }"
                        type="text" name="giro" class="form-control" v-model="newCompany.giro">
                    <p v-show="errors.has('giro')" class="text-danger">{{ errors.first('giro') }}</p>
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-warning" v-on:click="updateCompany({ id: newCompany.id })"
                        v-if="newCompany.id">
                        <i class="fas fa-plus-square"></i> Editar
                    </button>

                    <button type="button" class="btn btn-success" v-on:click="createCompany" v-else>
                        <i class="fas fa-plus-square"></i> Guardar
                    </button>

                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { loadProgressBar } from 'axios-progress-bar'
import { mapState, mapGetters, mapActions } from 'vuex';
import axios from 'axios'
import toastr from 'toastr'
import { formatImage } from '../../utils/imageUtils'

export default {
    data() {
        return {
            deviceSessions: [],
            deviceLimit: 0,
            deviceSessionsLoading: false,
            lightTables: localStorage.getItem('theme-light-tables') === '1'
        }
    },
    computed: {
        ...mapState(['newCompany', 'fillCompany', 'attachment', 'errorsLaravel', 'fillUser', 'backgroundImages', 'newBackgroundImage', 'attachmentBackgroundImage', 'selectedBackgroundImagePath']),
        ...mapGetters([]),
        isAdmin() {
            return this.fillUser.roles.some(role => role.name === 'admin')
        }
    },
    methods: {
        ...mapActions(['updateCompanyLogo', 'updateCompany', 'createCompany', 'uploadLogo', 'getBackgroundImages', 'setBackgroundImageFile', 'uploadBackgroundImage', 'deleteBackgroundImage', 'selectBackgroundImage']),
        formatImage,
        getDeviceSessions() {
            this.deviceSessionsLoading = true
            axios.get('user-sessions').then(response => {
                this.deviceSessions = response.data.sessions
                this.deviceLimit = response.data.limit
            }).finally(() => {
                this.deviceSessionsLoading = false
            })
        },
        revokeDeviceSession(session) {
            axios.post('user-sessions/' + session.id + '/revoke').then(response => {
                if (response.data.loggedOut) {
                    toastr.success('Sesion cerrada en este dispositivo')
                    window.location.href = '/login'
                    return
                }
                toastr.success('Dispositivo revocado correctamente')
                this.getDeviceSessions()
            }).catch(() => {
                toastr.error('No se pudo revocar el dispositivo')
            })
        },
        toggleLightTables() {
            document.body.classList.toggle('theme-light-tables', this.lightTables)
            localStorage.setItem('theme-light-tables', this.lightTables ? '1' : '0')
        }
    },
    created() {
        loadProgressBar()
        this.$store.dispatch('getUser')
        this.$store.dispatch('getCompany')
        this.$store.dispatch('getBackgroundImages')
    }
}
</script>

<style>
.logo {
    height: 200px;
}
</style>
