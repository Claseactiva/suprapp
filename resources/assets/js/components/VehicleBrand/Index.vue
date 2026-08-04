<template>

    <div class="col-lg-12">
        <div class="row mb-4">
            <div class="col-xl-6 col-md-12 mb-4 mb-xl-0">
                <AgregarTipo></AgregarTipo>
            </div>
            <div class="col-xl-6 col-md-12">
                <AgregarMarca></AgregarMarca>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-xl-6 col-md-12 mb-4 mb-xl-0">
                <AgregarModelo></AgregarModelo>
            </div>
            <div class="col-xl-6 col-md-12">
                <AgregarMotorSpec></AgregarMotorSpec>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-md-12">
                <AgregarMotor></AgregarMotor>
            </div>
        </div>
    </div>

</template>


<script>

import { loadProgressBar } from 'axios-progress-bar'
import AgregarMarca from './AgregarMarca'
import AgregarTipo from './AgregarTipo'
import AgregarModelo from './AgregarModelo'
import AgregarMotorSpec from './AgregarMotorSpec'
import AgregarMotor from './AgregarMotor'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: {AgregarMarca, AgregarTipo, AgregarModelo, AgregarMotorSpec, AgregarMotor}
}

</script>
