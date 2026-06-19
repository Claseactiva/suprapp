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

export default {
    computed: {
        ...mapState(['newCompany', 'fillCompany', 'attachment', 'errorsLaravel']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions(['updateCompanyLogo', 'updateCompany', 'createCompany', 'uploadLogo'])
    },
    created() {
        loadProgressBar()
        this.$store.dispatch('getUser')
        this.$store.dispatch('getCompany')
    }
}
</script>

<style>
.logo {
    height: 200px;
}
</style>
