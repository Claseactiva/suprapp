<template>
    <div v-if="lastCreatedUserId" class="alert alert-success mt-3 mb-0">
        <p class="mb-2" v-if="activationLink">Usuario creado. Se envió un correo para que configure su contraseña. Si no tiene correo o no le llega, copia este link y mándaselo por otro medio (WhatsApp, SMS, etc):</p>
        <p class="mb-2" v-else>Usuario creado. Se envió un correo para que configure su contraseña.</p>

        <div v-if="activationLink" class="input-group">
            <input type="text" class="form-control" readonly :value="activationLink" @focus="$event.target.select()">
            <div class="input-group-append">
                <button type="button" class="btn btn-info" @click="copyLink">
                    <i class="far fa-copy"></i> Copiar
                </button>
            </div>
        </div>

        <button type="button" class="btn btn-outline-success btn-sm mt-2" @click="sendPasswordReset({ id: lastCreatedUserId })">
            <i class="fas fa-key"></i> Generar link
        </button>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import toastr from 'toastr'

export default {
    computed: {
        ...mapState(['activationLink', 'lastCreatedUserId'])
    },
    methods: {
        ...mapActions(['sendPasswordReset']),
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
