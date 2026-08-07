<template>
    <div class="col-lg-12 config-admin-view">
        <h5 class="text-white mb-3">Configuración</h5>
        <ul class="nav nav-pills mb-3 config-admin-tabs" id="pills-tab-config" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-utility-tab" data-toggle="pill" href="#pills-utility"
                    role="tab" aria-controls="pills-utility" aria-selected="true">Utilidad Por Defecto</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-freight-tab" data-toggle="pill" href="#pills-freight"
                    role="tab" aria-controls="pills-freight" aria-selected="false">Flete Por Defecto</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-delivery-time-tab" data-toggle="pill" href="#pills-delivery-time"
                    role="tab" aria-controls="pills-delivery-time" aria-selected="false">Plazos de Entrega</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-payment-methods-tab" data-toggle="pill" href="#pills-payment-methods"
                    role="tab" aria-controls="pills-payment-methods" aria-selected="false">Formas de Pago</a>
            </li>
        </ul>
        <div class="tab-content config-admin-content" id="pills-tabContent-config">
            <div class="tab-pane fade show active" id="pills-utility" role="tabpanel" aria-labelledby="pills-utility-tab">
                <UtilidadDefect></UtilidadDefect>
            </div>
            <div class="tab-pane fade" id="pills-freight" role="tabpanel" aria-labelledby="pills-freight-tab">
                <FleteDefect></FleteDefect>
            </div>
            <div class="tab-pane fade" id="pills-delivery-time" role="tabpanel" aria-labelledby="pills-delivery-time-tab">
                <DeliveryTimeIndex></DeliveryTimeIndex>
            </div>
            <div class="tab-pane fade" id="pills-payment-methods" role="tabpanel" aria-labelledby="pills-payment-methods-tab">
                <FormasPago></FormasPago>
            </div>
        </div>
    </div>
</template>

<script>
import UtilidadDefect from '../Utilidad/UtilidadDefect'
import FleteDefect from '../Flete/FleteDefect'
import DeliveryTimeIndex from '../DeliveryTime/Index'
import FormasPago from '../Utilidad/Index'

export default {
    components: { UtilidadDefect, FleteDefect, DeliveryTimeIndex, FormasPago }
}
</script>

<style>
.config-admin-tabs .nav-item {
    margin: 0 0.55rem 0.55rem 0;
}

.config-admin-tabs .nav-link {
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 999px;
    background: rgba(15, 23, 42, 0.88);
    color: #dbeafe;
    font-weight: 600;
    letter-spacing: 0.01em;
    padding: 0.55rem 1rem;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.22);
    transition: background 0.2s ease, color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
}

.config-admin-tabs .nav-link:hover,
.config-admin-tabs .nav-link:focus {
    background: rgba(30, 41, 59, 0.96);
    color: #ffffff;
    transform: translateY(-1px);
}

.config-admin-tabs .nav-link.active,
.config-admin-tabs .show > .nav-link {
    background: linear-gradient(135deg, #0f766e, #0891b2);
    color: #ffffff;
    border-color: rgba(125, 211, 252, 0.65);
    box-shadow: 0 14px 30px rgba(8, 145, 178, 0.28);
}

.config-admin-content {
    background: #ffffff;
    border-radius: 0.75rem;
    padding: 1.25rem;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
}

.config-admin-content .form-group label {
    font-weight: 600;
    color: #334155;
}

.config-admin-content .btn-success {
    background: linear-gradient(135deg, #0f766e, #0891b2);
    border: none;
}

.config-admin-content .btn-success:hover {
    background: linear-gradient(135deg, #0d5f59, #0778a0);
}

.config-admin-content table {
    margin-bottom: 0;
}

.config-admin-content table.table-dark {
    color: #1e293b;
    background: transparent;
}

.config-admin-content table.table-dark th,
.config-admin-content table.table-dark td {
    background: transparent;
    border-color: #e2e8f0;
    color: #1e293b;
}
</style>
