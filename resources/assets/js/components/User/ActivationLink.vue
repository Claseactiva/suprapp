<template>

    <div id="activationLinkModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Usuario creado</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Se envió un correo para que configure su contraseña.</p>
                    <p class="text-muted">Si no tiene correo o no le llega, copia este link y mándaselo por otro medio (WhatsApp, SMS, etc):</p>
                    <div class="input-group">
                        <input type="text" class="form-control" readonly :value="activationLink" @focus="$event.target.select()">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-info" @click="copyLink">
                                <i class="far fa-copy"></i> Copiar
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import { mapState } from 'vuex'
import toastr from 'toastr'

export default {
    computed: {
        ...mapState(['activationLink'])
    },
    methods: {
        copyLink() {
            const link = this.activationLink

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(link).then(() => {
                    toastr.success('Link copiado')
                }).catch(() => {
                    this.copyLinkLegacy(link)
                })
                return
            }

            this.copyLinkLegacy(link)
        },
        copyLinkLegacy(link) {
            const textArea = document.createElement('textarea')
            textArea.value = link
            textArea.setAttribute('readonly', '')
            textArea.style.position = 'fixed'
            textArea.style.opacity = '0'
            document.body.appendChild(textArea)
            textArea.select()

            try {
                document.execCommand('copy')
                toastr.success('Link copiado')
            } catch (err) {
                toastr.error('No se pudo copiar el link')
            }

            document.body.removeChild(textArea)
        }
    }
}
</script>
