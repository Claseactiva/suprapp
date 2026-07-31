<template>
    <div id="userDevices" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Dispositivos de {{ userDeviceUserName }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <p class="mb-3">Límite actual: <strong>{{ userDeviceLimit }}</strong> dispositivo(s).</p>

                    <div class="alert alert-info" v-if="userDeviceSessions.length === 0">
                        No hay dispositivos activos registrados.
                    </div>

                    <div class="card mb-3" v-for="session in userDeviceSessions" :key="session.id">
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
                            <button type="button" class="btn btn-danger btn-sm" @click.prevent="revokeSession(session)">
                                <i class="fas fa-trash-alt"></i> Revocar
                            </button>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-secondary">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';

export default {
    computed: {
        ...mapState(['selectedUserForDevices', 'userDeviceSessions', 'userDeviceLimit', 'userDeviceUserName'])
    },
    methods: {
        ...mapActions(['revokeUserDeviceSession']),
        revokeSession(session) {
            this.revokeUserDeviceSession({
                userId: this.selectedUserForDevices.id,
                sessionId: session.id
            })
        }
    }
}
</script>
