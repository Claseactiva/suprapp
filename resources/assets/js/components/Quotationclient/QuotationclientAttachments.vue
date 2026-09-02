<template>
    <div id="quotationclientAttachmentsModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h4>Archivos adjuntos de la cotización</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small">
                        Estos archivos quedan asociados a la cotización completa (no a un producto). Puedes subir
                        PDF, imágenes, planillas u otros documentos. Máximo 10 MB por archivo.
                    </p>

                    <ul class="list-group mb-3" v-if="attachments.length">
                        <li class="list-group-item d-flex justify-content-between align-items-center"
                            v-for="file in attachments" :key="file.id">
                            <a :href="file.path" target="_blank" rel="noopener" :download="file.original_name"
                                class="text-truncate mr-2">
                                <i :class="iconFor(file)"></i> {{ file.original_name }}
                            </a>
                            <span class="d-flex align-items-center">
                                <small class="text-muted mr-2">{{ formatBytes(file.size) }}</small>
                                <button type="button" class="btn btn-danger btn-icon-sm"
                                    @click="deleteQuotationclientAttachment(file.id)" title="Eliminar">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </span>
                        </li>
                    </ul>
                    <p class="text-muted mb-3" v-else>Aún no hay archivos adjuntos para esta cotización.</p>

                    <div class="form-group mb-0">
                        <label for="quotationclientAttachmentsInput">Agregar archivos</label>
                        <input id="quotationclientAttachmentsInput" type="file" class="form-control" multiple
                            @change="setQuotationclientAttachmentsFiles($event)">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" :disabled="uploadingQuotationclientAttachments"
                        @click="uploadQuotationclientAttachments">
                        <i class="fas fa-upload"></i>
                        {{ uploadingQuotationclientAttachments ? 'Subiendo...' : 'Subir' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import { mapState, mapActions } from 'vuex'

export default {
    computed: {
        ...mapState(['activeQuotationclientAttachments', 'uploadingQuotationclientAttachments']),
        attachments() {
            return this.activeQuotationclientAttachments.files || []
        }
    },
    methods: {
        ...mapActions(['setQuotationclientAttachmentsFiles', 'uploadQuotationclientAttachments', 'deleteQuotationclientAttachment']),
        formatBytes(bytes) {
            const value = Number(bytes) || 0
            if (value < 1024) return value + ' B'
            if (value < 1024 * 1024) return (value / 1024).toFixed(0) + ' KB'
            return (value / (1024 * 1024)).toFixed(1) + ' MB'
        },
        iconFor(file) {
            const mime = (file.mime_type || '').toLowerCase()
            const name = (file.original_name || '').toLowerCase()

            if (mime.startsWith('image/')) return 'far fa-file-image'
            if (mime === 'application/pdf' || name.endsWith('.pdf')) return 'far fa-file-pdf'
            if (mime.includes('word') || name.endsWith('.doc') || name.endsWith('.docx')) return 'far fa-file-word'
            if (mime.includes('sheet') || mime.includes('excel') || name.endsWith('.xls') || name.endsWith('.xlsx') || name.endsWith('.csv')) return 'far fa-file-excel'
            if (mime.includes('zip') || mime.includes('compressed') || name.endsWith('.zip') || name.endsWith('.rar')) return 'far fa-file-archive'
            return 'far fa-file'
        }
    },
    mounted() {
        // Este modal se abre encima de #modalQuotationclient (modal anidado).
        // Bootstrap 4 borra 'modal-open'/backdrop al cerrar el hijo aunque el padre
        // siga abierto, dejando un backdrop invisible que bloquea el scroll/clicks.
        $('#quotationclientAttachmentsModal').on('hidden.bs.modal', function () {
            if ($('.modal.show').length > 0) {
                $('body').addClass('modal-open')
            }
            if ($('.modal-backdrop').length > 1) {
                $('.modal-backdrop').not(':last').remove()
            }
        })
    }
}
</script>
