export default { //computed propeties
    completeVehicleCreate(state, getters) {
        return state.selectedVBrand.label !== '' &&
            state.selectedVModel.label !== '' &&
            state.selectedVYear.label !== '' &&
            state.selectedVEngine.label !== ''
    },
    completeDetailVehicleCreate(state, getters) {
        return state.newDetailVehicle.km >= 1 &&
            state.newDetailVehicle.note.length >= 4
    },

    completeGuardarOrdenTrabajo(state, getters) {
        return state.newOrdenTrabajo.km >= 1 &&
            state.newOrdenTrabajo.descripcion.length >= 4
    },

    completeActualizarOrdenTrabajo(state, getters) {
        return state.newOrdenTrabajo.km >= 1 &&
            state.newOrdenTrabajo.descripcion.length >= 4
    },
    completeClientCreate(state, getters) {
        return state.newClient.rut !== '' &&
            state.newClient.name !== '' &&
            state.newClient.razonSocial !== '' &&
            state.newClient.giro !== '' &&
            state.newClient.email !== '' &&
            state.newClient.phone !== '' &&
            state.newClient.comuna !== '' &&
            state.newClient.type !== ''
    },
    isActived(state, getters) {
        return state.pagination.current_page
    },
    pagesNumber(state, getters) {
        if (!state.pagination.to) {
            return [];
        }

        var from = state.pagination.current_page - state.offset
        if (from < 1) {
            from = 1;
        }

        var to = from + (state.offset * 2);
        if (to >= state.pagination.last_page) {
            to = state.pagination.last_page;
        }

        var pagesArray = [];
        while (from <= to) {
            pagesArray.push(from);
            from++;
        }

        return pagesArray;
    },

    isActived_marca(state, getters) {
        return state.pagination_marca.current_page
    },
    pagesNumber_marca(state, getters) {
        if (!state.pagination_marca.to) {
            return [];
        }

        var from = state.pagination_marca.current_page - state.offset_marca
        if (from < 1) {
            from = 1;
        }

        var to = from + (state.offset_marca * 2);
        if (to >= state.pagination_marca.last_page) {
            to = state.pagination_marca.last_page;
        }

        var pagesArray = [];
        while (from <= to) {
            pagesArray.push(from);
            from++;
        }

        return pagesArray;
    },

    isActived_tipo(state, getters) {
        return state.pagination_tipo.current_page
    },
    pagesNumber_tipo(state, getters) {
        if (!state.pagination_tipo.to) {
            return [];
        }

        var from = state.pagination_tipo.current_page - state.offset_tipo
        if (from < 1) {
            from = 1;
        }

        var to = from + (state.offset_tipo * 2);
        if (to >= state.pagination_tipo.last_page) {
            to = state.pagination_tipo.last_page;
        }

        var pagesArray = [];
        while (from <= to) {
            pagesArray.push(from);
            from++;
        }

        return pagesArray;
    },

    isActived_modelo(state, getters) {
        return state.pagination_modelo.current_page
    },
    pagesNumber_modelo(state, getters) {
        if (!state.pagination_modelo.to) {
            return [];
        }

        var from = state.pagination_modelo.current_page - state.offset_modelo
        if (from < 1) {
            from = 1;
        }

        var to = from + (state.offset_modelo * 2);
        if (to >= state.pagination_modelo.last_page) {
            to = state.pagination_modelo.last_page;
        }

        var pagesArray = [];
        while (from <= to) {
            pagesArray.push(from);
            from++;
        }

        return pagesArray;
    },

    isActived_year(state, getters) {
        return state.pagination_year.current_page
    },
    pagesNumber_year(state, getters) {
        if (!state.pagination_year.to) {
            return [];
        }

        var from = state.pagination_year.current_page - state.offset_year
        if (from < 1) {
            from = 1;
        }

        var to = from + (state.offset_year * 2);
        if (to >= state.pagination_year.last_page) {
            to = state.pagination_year.last_page;
        }

        var pagesArray = [];
        while (from <= to) {
            pagesArray.push(from);
            from++;
        }

        return pagesArray;
    },
    isActived_motor(state, getters) {
        return state.pagination_motor.current_page
    },
    pagesNumber_motor(state, getters) {
        if (!state.pagination_motor.to) {
            return [];
        }

        var from = state.pagination_motor.current_page - state.offset_motor
        if (from < 1) {
            from = 1;
        }

        var to = from + (state.offset_motor * 2);
        if (to >= state.pagination_motor.last_page) {
            to = state.pagination_motor.last_page;
        }

        var pagesArray = [];
        while (from <= to) {
            pagesArray.push(from);
            from++;
        }

        return pagesArray;
    },


    isActived_form(state, getters) {
        return state.pagination_form.current_page
    },
    pagesNumber_form(state, getters) {
        if (!state.pagination_form.to) {
            return [];
        }

        var from = state.pagination_form.current_page - state.offset_form
        if (from < 1) {
            from = 1;
        }

        var to = from + (state.offset_form * 2);
        if (to >= state.pagination_form.last_page) {
            to = state.pagination_form.last_page;
        }

        var pagesArray = [];
        while (from <= to) {
            pagesArray.push(from);
            from++;
        }

        return pagesArray;
    },


    isActived_shipping(state, getters) {
        return state.pagination_shipping.current_page
    },
    pagesNumber_shipping(state, getters) {
        if (!state.pagination_shipping.to) {
            return [];
        }

        var from = state.pagination_shipping.current_page - state.offset_shipping
        if (from < 1) {
            from = 1;
        }

        var to = from + (state.offset_shipping * 2);
        if (to >= state.pagination_shipping.last_page) {
            to = state.pagination_shipping.last_page;
        }

        var pagesArray = [];
        while (from <= to) {
            pagesArray.push(from);
            from++;
        }

        return pagesArray;
    },

    getVehicle(state, getters) {
        return state.vehicle
    },
    getVehicleBrand(state, getters) {
        return state.vehiclebrand
    },
    getVehiculoTipo(state, getters) {
        return state.vehiculotipo
    },
    getVehicleModel(state, getters) {
        return state.vehiclebrand
    },
    getVehicleYear(state, getters) {
        return state.vehicleyear
    },
    getVehicleMotor(state, getters) {
        return state.vehiclemotor
    },

    getUser(state, getters) {
        return state.user
    },
    getPago(state, getters) {
        return state.pago
    },
    getClient(state, getters) {
        return state.client
    },
    getProduct(state, getters) {
        return state.product
    },
    getProductSale(state, getters) {
        return state.productsale
    },
    /*******seccion de roles ************/
    /************************************ */
    completeRoleCreate(state, getters) {
        return state.newRole.name.length >= 3
    },
    completeRoleEdit(state, getters) {
        return state.fillRole.name.length >= 3
    },

    completeObservacionCheckList(state, getters) {
        return state.columnaObservacion.observacion !== '' ||
        state.attachment.length > 0
    },

    completeObservacion(state, getters) {
        return state.newOrdenTrabajo.observacion !== '' ||
        state.attachment.length > 0
    },
}
