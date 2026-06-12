<template>

    <div class="col-lg-12">
        <div class="row">
            <div class="col-xl-6 col-md-12">
                <AgregarMarca></AgregarMarca>
            </div>
            <div class="col-xl-6 col-md-12">
                <AgregarTipo></AgregarTipo>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6 col-md-12">
                <AgregarModelo></AgregarModelo>
            </div>
            <div class="col-xl-6 col-md-12">
                <AgregarYear></AgregarYear>
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
import AgregarYear from './AgregarYear'
import AgregarMotor from './AgregarMotor'
import { mapState, mapActions, mapGetters } from 'vuex'

export default {
    components: {AgregarMarca, AgregarTipo, AgregarModelo, AgregarYear, AgregarMotor}
}

</script>
