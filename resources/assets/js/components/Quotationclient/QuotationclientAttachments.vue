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

                    <div class="form-group">
                        <label for="quotationclientAttachmentsInput">Agregar archivos</label>
                        <input id="quotationclientAttachmentsInput" type="file" class="form-control" multiple
                            @change="onFileInput($event)">
                    </div>

                    <div class="attachments-paste-zone">
                        <i class="fas fa-paste"></i>
                        También puedes pegar un pantallazo o imagen copiada con Ctrl+V
                    </div>

                    <ul class="list-group mt-3" v-if="pendingFiles.length">
                        <li class="list-group-item d-flex justify-content-between align-items-center py-1"
                            v-for="(file, index) in pendingFiles" :key="index">
                            <small class="text-truncate mr-2">
                                <i class="fas fa-clock text-muted"></i> {{ file.name }}
                                <span class="text-muted">({{ formatBytes(file.size) }})</span>
                            </small>
                            <button type="button" class="btn btn-outline-danger btn-icon-sm"
                                @click="removeQuotationclientAttachmentPending(index)" title="Quitar">
                                <i class="fas fa-times"></i>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success"
                        :disabled="uploadingQuotationclientAttachments || !pendingFiles.length"
                        @click="uploadQuotationclientAttachments">
                        <i class="fas fa-upload"></i>
                        {{ uploadingQuotationclientAttachments ? 'Subiendo...' : ('Subir' + (pendingFiles.length ? ' (' + pendingFiles.length + ')' : '')) }}
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
        ...mapState(['activeQuotationclientAttachments', 'uploadingQuotationclientAttachments', 'attachmentQuotationclientFiles']),
        attachments() {
            return this.activeQuotationclientAttachments.files || []
        },
        pendingFiles() {
            return this.attachmentQuotationclientFiles || []
        }
    },
    methods: {
        ...mapActions(['addQuotationclientAttachmentsFiles', 'removeQuotationclientAttachmentPending',
            'uploadQuotationclientAttachments', 'deleteQuotationclientAttachment']),
        onFileInput(evt) {
            this.addQuotationclientAttachmentsFiles(Array.from(evt.target.files || []))
            evt.target.value = null
        },
        onPaste(evt) {
            const items = (evt.clipboardData || window.clipboardData || {}).items || []
            const pasted = []

            for (let i = 0; i < items.length; i++) {
                if (items[i].kind === 'file') {
                    const file = items[i].getAsFile()
                    if (file) {
                        pasted.push(this.namePastedFile(file))
                    }
                }
            }

            if (pasted.length) {
                evt.preventDefault()
                this.addQuotationclientAttachmentsFiles(pasted)
            }
        },
        namePastedFile(file) {
            if (file.name && file.name !== 'image.png' && file.name !== 'blob') {
                return file
            }
            const ext = (file.type && file.type.split('/')[1]) || 'png'
            const stamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19)
            return new File([file], 'captura-' + stamp + '.' + ext, { type: file.type || 'image/png' })
        },
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

        // Tambien aceptar pegar (Ctrl+V) en cualquier parte del modal, no solo la zona.
        $('#quotationclientAttachmentsModal').on('paste', (evt) => {
            this.onPaste(evt.originalEvent || evt)
        })
    }
}
</script>

<style scoped>
.attachments-paste-zone {
    border: 2px dashed #b8b8b8;
    border-radius: 6px;
    padding: 12px;
    text-align: center;
    color: #6c757d;
    font-size: 0.82rem;
}
</style>
