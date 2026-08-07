<template>
    <div class="alert alert-info mb-3">
        <p class="mb-2" v-if="quotationId">¿No tienes el correo a mano? Comparte este link y que el cliente se registre solo (nombre, correo y contraseña). Queda identificado con esta cotización:</p>
        <p class="mb-2" v-else>¿No tienes el correo a mano? Comparte este link y que el cliente se registre solo (nombre, correo y contraseña):</p>
        <div class="input-group">
            <input type="text" class="form-control" readonly :value="registroLink" @focus="$event.target.select()">
            <div class="input-group-append">
                <button type="button" class="btn btn-info" @click="copyLink">
                    <i class="far fa-copy"></i> Copiar
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import toastr from 'toastr'

export default {
    props: {
        quotationId: {
            type: [String, Number],
            default: null
        }
    },
    computed: {
        ...mapState(['currentUserRegistroId']),
        registroLink() {
            if (this.quotationId) {
                return window.location.origin + '/registro-cotizacion/' + this.quotationId
            }
            return window.location.origin + '/registro/' + this.currentUserRegistroId
        }
    },
    methods: {
        ...mapActions(['getCurrentUserRegistroId']),
        copyLink() {
            const link = this.registroLink

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
    },
    created() {
        this.getCurrentUserRegistroId()
    }
}
</script>
