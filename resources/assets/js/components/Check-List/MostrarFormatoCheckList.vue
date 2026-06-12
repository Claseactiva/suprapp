<template>
    <div>
        <div id="MostrarFormatoCheckList" class="modal fade" style="overflow-y: scroll;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">

                        <ul class="list-group mb-3">
                            <template v-for="(formatchecklist, index) in formatchecklists">
                                <li class="list-group-item bg-secondary">
                                    <div class="row">
                                        <div class="col">
                                            <strong>{{ index + 1 }}.- {{ formatchecklist.categoria }}</strong>
                                        </div>
                                        <div class="col-auto">
                                            <a class="btn btn-success btn-sm text-white"
                                                @click.prevent="modalAgregarIntervencion({ id: formatchecklist.id })">
                                                <i class="fas fa-plus"></i> Intervencion
                                            </a>

                                            <a class="btn btn-danger btn-sm"
                                                @click.prevent="eliminarCategoria({ id: formatchecklist.id })">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>

                                            <a class="btn btn-warning btn-sm"
                                                @click.prevent="modalEditarCategoria({ formatchecklist: formatchecklist })"
                                                title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item" v-for="intervencion in formatchecklist.intervenciones">
                                    <div class="row">
                                        <div class="col">
                                            {{ intervencion.intervencion }}
                                        </div>
                                        <div class="col-auto">
                                            <a class="btn btn-danger btn-sm"
                                                @click.prevent="eliminarIntervencion({ id: intervencion.id })"
                                                title="Eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                            <a class="btn btn-warning btn-sm"
                                                @click.prevent="modalEditarIntervencion({ intervenciones: intervencion })"
                                                title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </ul>
                        <div class="row justify-content-between">
                            <div class="col-6">
                                <button type="button" class="btn btn-success" @click.prevent="modalAgregarCategoria">
                                    <i class="fas fa-plus"></i> Agregar Categoria
                                </button>

                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
                                    Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <EditarCategoriaCheckList></EditarCategoriaCheckList>
        <EditarIntervencionCheckList></EditarIntervencionCheckList>
        <AgregarCategoria></AgregarCategoria>
        <AgregarIntervencion></AgregarIntervencion>
    </div>
</template>

<script>
import EditarCategoriaCheckList from './EditarCategoriaCheckList'
import EditarIntervencionCheckList from './EditarIntervencionCheckList'
import AgregarCategoria from './AgregarCategoria'
import AgregarIntervencion from './AgregarIntervencion'
import { mapState, mapGetters, mapActions } from 'vuex';


export default {
    components: { EditarCategoriaCheckList, EditarIntervencionCheckList, AgregarCategoria, AgregarIntervencion },
    computed: {
        ...mapState(['formatchecklists']),
        ...mapGetters([])
    },
    methods: {
        ...mapActions(['modalEditarCategoria', 'modalEditarIntervencion', 'modalAgregarCategoria', 'modalAgregarIntervencion', 'eliminarIntervencion', 'eliminarCategoria'])
    },
}
</script>
