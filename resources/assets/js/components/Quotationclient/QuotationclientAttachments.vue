<template>
    <div>
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

                        <div class="row mb-2" v-if="imageAttachments.length">
                            <div class="col-6 col-md-4 mb-3" v-for="file in imageAttachments" :key="file.id">
                                <div class="card attachment-thumb-card">
                                    <img :src="file.path" class="card-img-top attachment-thumb" alt="..."
                                        @click="openPreview(file)">
                                    <div class="card-body p-2 d-flex justify-content-between align-items-center">
                                        <small class="text-muted text-truncate" :title="file.original_name">
                                            {{ file.original_name }}
                                        </small>
                                        <button type="button" class="btn btn-danger btn-icon-sm ml-1"
                                            @click="deleteQuotationclientAttachment(file.id)" title="Eliminar">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <ul class="list-group mb-3" v-if="otherAttachments.length">
                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                v-for="file in otherAttachments" :key="file.id">
                                <span class="text-truncate mr-2">
                                    <i :class="iconFor(file)"></i> {{ file.original_name }}
                                </span>
                                <span class="d-flex align-items-center flex-shrink-0">
                                    <small class="text-muted mr-2">{{ formatBytes(file.size) }}</small>
                                    <button v-if="isPdf(file)" type="button" class="btn btn-info btn-icon-sm mr-1"
                                        @click="openPreview(file)" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <a :href="file.path" target="_blank" rel="noopener" :download="file.original_name"
                                        class="btn btn-secondary btn-icon-sm mr-1" title="Descargar">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-icon-sm"
                                        @click="deleteQuotationclientAttachment(file.id)" title="Eliminar">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </span>
                            </li>
                        </ul>

                        <p class="text-muted mb-3" v-if="!attachments.length">
                            Aún no hay archivos adjuntos para esta cotización.
                        </p>

                        <div class="form-group">
                            <label for="quotationclientAttachmentsInput">Agregar archivos</label>
                            <input id="quotationclientAttachmentsInput" type="file" class="form-control" multiple
                                @change="onFileInput($event)">
                        </div>

                        <div class="attachments-paste-zone" :class="{ 'is-dragging': dragging }"
                            @dragenter.prevent="dragging = true" @dragover.prevent="dragging = true"
                            @dragleave.prevent="dragging = false" @drop.prevent="onDrop">
                            <i class="fas fa-cloud-upload-alt"></i>
                            Arrastra archivos aquí, o pega un pantallazo con Ctrl+V
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

        <div v-if="previewFile" class="attachment-preview" @click.self="closePreview">
            <div class="attachment-preview__dialog">
                <div class="attachment-preview__header bg-dark text-white">
                    <h5 class="mb-0 text-truncate">{{ previewFile.original_name }}</h5>
                    <span class="attachment-preview__actions">
                        <a :href="previewFile.path" target="_blank" rel="noopener" :download="previewFile.original_name"
                            class="btn btn-sm btn-outline-light mr-2" title="Descargar">
                            <i class="fas fa-download"></i>
                        </a>
                        <button type="button" class="close text-white" aria-label="Close" @click="closePreview">
                            <span>&times;</span>
                        </button>
                    </span>
                </div>
                <div class="attachment-preview__body">
                    <iframe v-if="isPdf(previewFile)" :src="previewFile.path" class="attachment-preview__frame"
                        title="Vista previa"></iframe>
                    <img v-else :src="previewFile.path" class="attachment-preview__image" alt="...">
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
            dragging: false,
            previewFile: null
        }
    },
    computed: {
        ...mapState(['activeQuotationclientAttachments', 'uploadingQuotationclientAttachments', 'attachmentQuotationclientFiles']),
        attachments() {
            return this.activeQuotationclientAttachments.files || []
        },
        imageAttachments() {
            return this.attachments.filter(file => this.isImage(file))
        },
        otherAttachments() {
            return this.attachments.filter(file => !this.isImage(file))
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
        onDrop(evt) {
            this.dragging = false
            const files = Array.from((evt.dataTransfer && evt.dataTransfer.files) || [])
            if (files.length) {
                this.addQuotationclientAttachmentsFiles(files)
            }
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
        openPreview(file) {
            this.previewFile = file
        },
        closePreview() {
            this.previewFile = null
        },
        formatBytes(bytes) {
            const value = Number(bytes) || 0
            if (value < 1024) return value + ' B'
            if (value < 1024 * 1024) return (value / 1024).toFixed(0) + ' KB'
            return (value / (1024 * 1024)).toFixed(1) + ' MB'
        },
        isImage(file) {
            const mime = (file.mime_type || '').toLowerCase()
            const name = (file.original_name || '').toLowerCase()
            return mime.startsWith('image/') || /\.(png|jpe?g|gif|webp|bmp|svg)$/.test(name)
        },
        isPdf(file) {
            const mime = (file.mime_type || '').toLowerCase()
            const name = (file.original_name || '').toLowerCase()
            return mime === 'application/pdf' || name.endsWith('.pdf')
        },
        iconFor(file) {
            const mime = (file.mime_type || '').toLowerCase()
            const name = (file.original_name || '').toLowerCase()

            if (this.isPdf(file)) return 'far fa-file-pdf'
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
        $('#quotationclientAttachmentsModal').on('hidden.bs.modal', () => {
            this.closePreview()
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
    transition: border-color 0.15s, background-color 0.15s;
}

.attachments-paste-zone.is-dragging {
    border-color: #17a2b8;
    background-color: rgba(23, 162, 184, 0.08);
    color: #17a2b8;
}

.attachment-thumb {
    height: 110px;
    object-fit: cover;
    cursor: zoom-in;
}

.attachment-thumb-card .card-body small {
    max-width: 100%;
}

.attachment-preview {
    position: fixed;
    inset: 0;
    z-index: 1085;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem;
}

.attachment-preview__dialog {
    width: min(98vw, 1400px);
    height: calc(100vh - 1.5rem);
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
    display: flex;
    flex-direction: column;
}

.attachment-preview__header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.6rem 1rem;
}

.attachment-preview__actions {
    display: flex;
    align-items: center;
    flex-shrink: 0;
}

.attachment-preview__body {
    flex: 1;
    background: #d9d9d9;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: auto;
}

.attachment-preview__frame {
    width: 100%;
    height: 100%;
    border: 0;
    background: #fff;
}

.attachment-preview__image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
</style>
