<template>

    <div id="editUserCompanies" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Empresas asignadas - {{ fillUserCompanies.name }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="empresa">Agregar empresa</label>
                    <div class="d-flex" style="gap: .5rem">
                        <div class="flex-grow-1">
                            <v-select name="empresa" placeholder="Buscar empresa por razón social"
                                @input="setSelectedCompany" :options="fleetClientOptions"
                                :value="selectedCompanyToAdd">
                            </v-select>
                        </div>
                        <button type="button" class="btn btn-success" :disabled="!selectedCompanyToAdd"
                            @click.prevent="addCompany">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>

                    <table class="table table-dark table-sm mt-3 mb-0">
                        <thead>
                            <tr>
                                <th>Empresa</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="userCompanies.length === 0">
                                <td colspan="2">Este usuario no tiene empresas asignadas.</td>
                            </tr>
                            <tr v-for="companyLocal in userCompanies" :key="companyLocal.id">
                                <td>{{ companyLocal.razonSocial }}</td>
                                <td class="text-right">
                                    <a href="#" class="btn btn-danger btn-icon-sm"
                                        @click.prevent="detachUserCompany(companyLocal.id)"
                                        data-toggle="tooltip" data-placement="top" title="Quitar">
                                        <i class="fas fa-ban"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

import { mapState, mapActions } from 'vuex'

export default {
    data() {
        return {
            selectedCompanyToAdd: null
        }
    },
    computed: {
        ...mapState(['fillUserCompanies', 'userCompanies', 'fleetClientOptions'])
    },
    methods: {
        ...mapActions(['getFleetClientOptions', 'attachUserCompany', 'detachUserCompany']),
        setSelectedCompany(option) {
            this.selectedCompanyToAdd = option
        },
        addCompany() {
            if (!this.selectedCompanyToAdd) {
                return
            }
            this.attachUserCompany(this.selectedCompanyToAdd.value)
            this.selectedCompanyToAdd = null
        }
    },
    created() {
        this.getFleetClientOptions()
    }
}

</script>
