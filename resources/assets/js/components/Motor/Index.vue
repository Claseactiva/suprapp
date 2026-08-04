<template>

    <div class="col-lg-12">
        <div class="row">
            <div class="col-12">
                <Agregar></Agregar>
            </div>
        </div>
        <Editar></Editar>
        <Historial></Historial>
    </div>

</template>


<script>

import Agregar from './Agregar'
import Editar from './Editar'
import Historial from './Historial'

export default {
    components: { Agregar, Editar, Historial }
}

</script>
