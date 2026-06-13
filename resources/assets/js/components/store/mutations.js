import axios from 'axios'
import toastr from 'toastr'
import printJS from 'print-js'

let $ = window.jQuery = require('jquery')

let urlUser = 'users'
let urlQuotationRoles = 'quotation-roles'
let urlTotalVehi = 'total-vehi'
let urlSumaVehi = 'suma-vehi'
let urlTotalCli = 'total-cli'
let urlTotalCliAdmin = 'total-cli-admin'
let urlTotalVehiAdmin = 'total-vehi-admin'
let urlCantVehicle = 'cant-vehicle-user'
let urlCantCliVehi = 'cant-cli-vehi-user'
let urlAllUser = 'users-all'
let urlAllPago = 'pagos-all'
let urlRoles = 'roles'
let urlAllRoles = 'roles-all'
let urlUserRoles = 'usersRoles'
let urlAllPermissions = 'permissions'
let urlQuotationusers = 'quotationusers'

let urlVehicle = 'vehicles'
let urlVehicleUser = 'vehicles-user'
let urlDetailVehicle = 'detailvehicles'
let urlOrdenTrabajo = 'ordentrabajo'
let urlEliminarOrdenTrabajo = 'eliminar_ordentrabajo'
let urlActualizarOrdenTrabajo = 'actualizar_ordentrabajo'
let urlTrabajo = 'trabajos'
let urlObservacion = 'observaciones'
let urlSubirFotosOrdenTrabajo = 'subirfotosordentrabajo'
let urlEliminarObservacion = 'eliminarObservacion'
let urlEliminarEditarObservacion = 'eliminarEditarObservacion'
let urlFotosOrdenTrabajo = 'fotosordentrabajo'
let urlSubirObservacion = 'subirobservacion'
let urlUpload = 'upload'

let urlVehicleBrand = 'vehiclebrands'
let urlSelectVehiculoMarcas = 'select-marcas'

let urlVehiculoTipo = 'vehiculotipos'
let urlSelectVehiculoTipo = 'select-tipos'

let urlVehicleModel = 'vehiclemodels'
let urlAllVehicleModel = 'vehiclemodels-all'


let urlVehicleYear = 'vehicleyears'
let urlVehicleMotor = 'vehiclemotors'
let urlSelectVehiculoMotor = 'select-motor'
let urlSelectVehiculoYear = 'select-year'

let urlVBrand = 'vbrands-all'
let urlVModel = 'vmodels-all'
let urlVYear = 'vyears-all'
let urlVEngine = 'vengines-all'
let urlCiudad = 'ciudad-all'

let urlVBR = 'vbr-all'
let urlVMR = 'vmr-all'

let urlMM = 'mm-all'
let urlYM = 'ym-all'

let urlCreateQuotationUser = 'quotationuser'
let urlCreateQuotationUserExpress = 'quotationuserexpress'
let urlPendingQuotations = 'pendingquotations'

let urlCreateQuotationShipping = 'quotationshipping'
let urlCreateFacebookShipping = 'facebookshipping'

let urlNote = 'notes'

let urlQuotation = 'quotations'
let urlQuotationDetails = 'quotation-details'
let urlQuotationclient = 'quotationclients'
let urlQuotationclientform = 'quotationclientsform'
let urlQuotationclientDetails = 'quotationclient-details'
let urlQuotationclientProductSuggestions = 'quotationclients'
let urlQuotationPdf = 'quotation-pdf'
let urlQuotationforms = 'quotationforms'

let urlQuotationimport = 'quotationimports'
let urlQuotationimportPdf = 'quotationimport-pdf'

let urlDetail = 'details'
let urlDetailclient = 'detailclients'
let urlQuotationclientPdf = 'quotationclient-pdf'
let urlQuotationclientPdfIva = 'quotationclient-pdf-iva'
let urlQuotationShippingPdf = 'quotationshipping-pdf'
let urlGenerarReciboSales = 'generar-recibo-sale'
let urlAnularSale = 'anular-sale'
let urlCierreCajaZ = 'cierre-caja-z'

let urlImport = 'imports'
let urlImportDetails = 'import-details'
let urlDetailimport = 'detailimports'
let urlImportPdf = 'import-pdf'
let urlExportExcel = 'export-import'

let urlClient = 'clients'
let urlAllClient = 'clients-all'
let urlActivity = 'activities'

let urlProduct = 'products'
let urlAllProduct = 'products-all'
let urlVehicleModelCatalog = 'vehiclemodels-all'
let urlAllProductSale = 'products-all-sale'
let urlTipoDePago = 'tipodepago'
let urlDescuento = 'descuento'


let urlProductimport = 'productimports'
let urlAllProductimport = 'productimports-all'

let urlInventory = 'inventories'

let urlImages = 'images'

let urlUserId = 'user-id'
let urlCompanyLogo = 'company-logo'
let urlCompany = 'companies'
let urlUtilidad = 'utilities'
let urlDescuentoDefect = 'descuento-defect'

let urlCrearCheckList = 'crearCheckList'
let urlMostrarFormatoCheckList = 'mostrarFormatoCheckList'
let urlEditarCategoria = 'editarCategoria'
let urlEditarIntervecion = 'editarIntervencion'
let urlCrearCategoria = 'crearCategoria'
let urlCrearIntervencion = 'crearIntervencion'
let urlAgregarObservacionCheckList = 'agregarObservacionCheckList'
let urlEliminarImagenChecklist = 'eliminarImagenChecklist'
let urlEliminarObservacionChecklist = 'eliminarObservacionChecklist'
let urlGuardarCheckListVehicle = 'guardarCheckListVehicle'
let urlCheckListVehicles = 'checklistvehicles'
let urlMostrarCheckListVehicles = 'mostrarCheckListVehicles'
let urlEliminarCategoriaChecklist = 'eliminarCategoriaChecklist'
let urlEliminarIntervencionChecklist = 'eliminarIntervencionChecklist'
let urlEliminarImagenObservacion = 'eliminarImagenObservacion'

let urlMostrarCheckList = 'mostrarCheckList'

let urlMostrarCondiciones = 'mostrarCondiciones'
let urlCheckListRoles = 'roleschecklists'

let urlFlete = 'fletes'
let urlDeliveryTime = 'delivery-times'
let urlBill = 'bills'
let urlSale = 'all-sales'
let urlCreateSale = 'sale'

let urlActualizarCorrelativo = 'actualizar-correlativo'
let urlSparePart = 'spare-part'

function syncDeliveryTimesState(state, payload) {
    const deliveryTimes = payload.delivery_times || []
    const defaultDeliveryTime = payload.default_delivery_time || deliveryTimes.find(deliveryTime => deliveryTime.is_default) || null

    state.deliveryTimes = deliveryTimes
    state.defaultDeliveryTime = defaultDeliveryTime
        ? { id: defaultDeliveryTime.id, label: defaultDeliveryTime.label }
        : { id: '', label: '24 a 48 Hrs' }

    state.newDetailclient.days = state.defaultDeliveryTime.label
}

function sortProductsByModelRelations(state) {
    if (!Array.isArray(state.optionsProduct) || state.optionsProduct.length === 0) {
        return
    }

    if (!Array.isArray(state.modelProductSuggestions) || state.modelProductSuggestions.length === 0) {
        return
    }

    const scores = new Map()

    state.modelProductSuggestions.forEach((suggestion, index) => {
        if (!suggestion.product_id) {
            return
        }

        const weightedScore = ((parseInt(suggestion.uses_count, 10) || 0) * 1000) + (state.modelProductSuggestions.length - index)
        const currentScore = scores.get(suggestion.product_id) || 0
        scores.set(suggestion.product_id, Math.max(currentScore, weightedScore))
    })

    if (scores.size === 0) {
        return
    }

    state.optionsProduct = state.optionsProduct
        .map((product, index) => ({
            ...product,
            __priority: scores.get(product.value) || 0,
            __index: index
        }))
        .sort((left, right) => {
            if (left.__priority === right.__priority) {
                return left.__index - right.__index
            }

            return right.__priority - left.__priority
        })
        .map(({ __priority, __index, ...product }) => product)
}

function resolveSelectedDetailclientProductId(state) {
    const selectedProduct = state.selectedProduct

    if (!selectedProduct || !selectedProduct.value) {
        return null
    }

    const currentProduct = (state.newDetailclient.product || '').trim()
    const currentCode = (state.newDetailclient.detail || '').trim()
    const selectedLabel = (selectedProduct.label || '').trim()
    const selectedCode = (selectedProduct.codebar || '').trim()

    if (currentProduct === '' || currentProduct !== selectedLabel) {
        return null
    }

    if (selectedCode !== '' && currentCode !== selectedCode) {
        return null
    }

    return selectedProduct.value
}

function resolvePaginationRequest(request, fallbackPerPage = 20) {
    const requestObject = request && typeof request === 'object' && !request.target ? request : {}
    const pageCandidate = requestObject.page !== undefined ? requestObject.page : request
    const perPageCandidate = requestObject.per_page
    const normalizedPage = parseInt(pageCandidate, 10)
    const normalizedPerPage = parseInt(perPageCandidate, 10)

    return {
        page: Number.isFinite(normalizedPage) && normalizedPage > 0 ? normalizedPage : 1,
        perPage: Number.isFinite(normalizedPerPage) && normalizedPerPage > 0 ? normalizedPerPage : fallbackPerPage
    }
}


export default { //used for changing the state
    /******************************* */
    /****** sección vehiculos **** */
    /******************************* */
    getVehicles(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination.per_page || 20)
        let url = urlVehicle + '?page=' + page + '&patent=' + state.searchVehicle.patent + '&name=' + state.searchVehicle.name + '&year=' + state.searchVehicle.year + '&per_page=' + perPage
        axios.get(url).then(response => {

            state.vehicles = response.data.vehicles.data
            state.pagination = response.data.pagination
        });
    },
    getVehiclesUser(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination.per_page || 20)
        let url = urlVehicleUser + '?page=' + page + '&per_page=' + perPage
        axios.get(url).then(response => {
            state.vehicles = response.data.vehicles.data
            state.pagination = response.data.pagination
        });
    },
    createVehicle(state) {
        let id_user = null
        if (state.selectedUser != null) {
            id_user = state.selectedUser.value
        }
        let url = urlVehicle
        axios.post(url, {
            user_id: id_user,
            patent: state.newVehicle.patent,
            chasis: state.newVehicle.chasis,
            brand: state.selectedVBrand.label,
            model: state.selectedVModel.label,
            year: state.selectedVYear.label,
            engine: state.selectedVEngine.label,
            color: state.newVehicle.color,
            km: state.newVehicle.km,
        }).then(response => {
            state.newVehicle.patent = ''
            state.newVehicle.chasis = ''
            state.newVehicle.color = ''
            state.newVehicle.km = ''
            state.selectedVBrand.label = ''
            state.selectedVModel.label = ''
            state.selectedVYear.label = ''
            state.selectedVEngine.label = ''
            state.errorsLaravel = []
            $('#create').modal('hide')
            toastr.success('Vehículo generado con éxito')
        }).catch(error => {
            toastr.error(error.response.data)
        })
    },
    editVehicle(state, vehicle) {
        state.fillVehicle.id = vehicle.id
        state.fillVehicle.user_id = vehicle.user_id
        state.selectedUser = {
            label: vehicle.user.name,
            value: vehicle.user_id
        }
        state.fillVehicle.patent = vehicle.patent
        state.fillVehicle.chasis = vehicle.chasis
        state.fillVehicle.name = vehicle.name
        state.selectedVBrand.label = vehicle.brand
        state.selectedVModel.label = vehicle.model
        state.selectedVYear.label = vehicle.year
        state.selectedVEngine.label = vehicle.engine
        state.fillVehicle.color = vehicle.color
        state.fillVehicle.km = vehicle.km
        $("#edit").modal('show')
    },
    detailVehicle(state, vehicle) {
        state.details = []
        $("#detail").modal('show')
    },
    /***********************************
     * *******************************
     */
    modalRequestParts(state, vehicle) {
        state.formCotizacion.patentchasis = vehicle.patent + '/' + vehicle.chasis
        state.formCotizacion.brand = vehicle.brand
        state.formCotizacion.model = vehicle.model
        state.formCotizacion.year = vehicle.year
        state.formCotizacion.engine = vehicle.engine

        $('#requestParts').modal('show')
    },

    requestPartsVehicle(state) {

        axios.post('quotation-mechanic', {
            patentchasis: state.formCotizacion.patentchasis.toUpperCase(),
            brand: state.formCotizacion.brand,
            model: state.formCotizacion.model,
            year: state.formCotizacion.year,
            engine: state.formCotizacion.engine,
            description: state.formCotizacion.description
        })
            .then(response => {
                $('#requestParts').modal('hide')
                toastr.success('Solicitud ingresada con éxito')
            })
            .catch(error => {
                toastr.success('No se pudo enviar la solicitud')
            })

        //formCotizacion: { name:'', email:'', phone:'', patentchasis:'', brand:'', model:'', year:'', engine:'', description:'' },


    },
    modalOrdenTrabajo(state, vehicle) {
        state.kilometrajeActual = vehicle.km
        state.newOrdenTrabajo.vehicle_id = vehicle.id
        state.newOrdenTrabajo.tendenciaKm = parseInt(vehicle.km) + 20000
        $("#createOrdenTrabajo").modal('show')
    },


    modalFotosOrdenTrabajo(state, id) {
        state.newOrdenTrabajo.vehicle_id = id
        $("#modalFotosOrdenTrabajo").modal('show')
    },

    modalObservacion(state, id) {
        state.newOrdenTrabajo.vehicle_id = id
        $("#modalObservacion").modal('show')
    },


    guardarOrdenTrabajo(state) {
        let url = urlOrdenTrabajo

        if (state.newOrdenTrabajo.km < state.kilometrajeActual) {
            toastr.error('El kilometraje no puede ser menor actual')
        } else {
            axios.post(url, {
                vehicle_id: state.newOrdenTrabajo.vehicle_id,
                km: state.newOrdenTrabajo.km,
                descripcion: state.newOrdenTrabajo.descripcion,
            }).then(response => {
                state.newOrdenTrabajo.descripcion = ''
                state.newOrdenTrabajo.km = 0
                state.errorsLaravel = []
                toastr.success('Se creo la orden de trabajo correctamente')

                state.trabajos = response.data.trabajos
                state.kilometrajeActual = response.data.ultimo_km
                state.newOrdenTrabajo.tendenciaKm = parseInt(response.data.ultimo_km) + 20000

                axios.get('client-vehicles').then((response) => {
                    state.vehicles = response.data
                })

            }).catch(error => {
                toastr.error(error.response.data)
            })
        }
    },


    actualizarOrdenTrabajo(state) {
        let url = urlActualizarOrdenTrabajo

        if (state.newOrdenTrabajo.km < state.kilometrajeActual) {
            toastr.error('El kilometraje no puede ser menor actual')
        } else {

            axios.post(url, {
                vehicle_id: state.newOrdenTrabajo.vehicle_id,
                km: state.newOrdenTrabajo.km,
                descripcion: state.newOrdenTrabajo.descripcion,
            }).then(response => {
                state.newOrdenTrabajo.descripcion = ''
                state.newOrdenTrabajo.km = 0
                state.errorsLaravel = []
                toastr.success('Se creo la orden de trabajo correctamente')

                state.trabajos = response.data.trabajos
                state.newOrdenTrabajo.vehicle_id = response.data.vehicle_id
                state.kilometrajeActual = response.data.ultimo_km
                state.newOrdenTrabajo.tendenciaKm = parseInt(response.data.ultimo_km) + 20000

                axios.get('client-vehicles').then((response) => {
                    state.vehicles = response.data
                })

            }).catch(error => {
                toastr.error(error.response.data)
            })
        }
    },

    removeTrabajo(state, data) {
        let url = urlEliminarOrdenTrabajo
        axios.post(url, data).then(response => {
            toastr.success('Orden de trabajo eliminada con éxito')
            state.trabajos = response.data.trabajos
            state.kilometrajeActual = response.data.ultimo_km
            state.newOrdenTrabajo.km_old = response.data.ultimo_km
        })
    },

    borrarImagenObservacion(state, imagen) {
        let url = urlEliminarObservacion
        axios.post(url, imagen).then(response => {
            state.errorsLaravel = []
            toastr.success('La imagen se elimino correctamente')
            this.commit('getObservaciones', state.newOrdenTrabajo.vehicle_id)
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    getTrabajos(state, id) {
        let url = urlTrabajo + '/' + id
        axios.get(url).then(response => {
            state.trabajos = response.data

            if (state.trabajos.length > 0) {
                state.trabajos.forEach(trabajo => {
                    state.newOrdenTrabajo.km_old = trabajo.km
                })
            }
        })
    },

    getOrdenesTrabajos(state) {
        let url = urlOrdenTrabajo
        axios.get(url).then(response => {
            state.ordenestrabajos = response.data
        })
    },

    getObservaciones(state, id) {
        let url = urlObservacion + '/' + id
        axios.get(url).then(response => {
            state.observaciones = response.data
        })
    },


    getFotosOrdenTrabajo(state, id) {
        let url = urlFotosOrdenTrabajo + '/' + id
        axios.get(url).then(response => {
            state.trabajos = response.data
        })
    },

    AgregarFotosOrdenTrabajo(state) {
        for (let i = 0; i < state.attachment.length; i++) {
            state.form.append('pics[]', state.attachment[i])
        }

        const config = {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }

        //vehicle_id: state.newOrdenTrabajo.vehicle_id = id,

        let url = urlSubirFotosOrdenTrabajo
        if (state.attachment.length > 0) {

            state.form.append('id', state.newOrdenTrabajo.vehicle_id)
            $("#files").val(null)

            axios.post(url, state.form, config).then(response => {
                state.newOrdenTrabajo.vehicle_id = ''
                state.errorsLaravel = []
                $('#modalFotosOrdenTrabajo').modal('hide')
                toastr.success('Imagen(es) subida(s) con éxito')
            }).catch(error => {
                state.errorsLaravel = error.response.data
            })
        }

    },

    subirFotosOrdenTrabajo(state, evt) {
        state.form = new FormData()

        state.images = []
        state.attachment = []
        let selectedFiles = evt.target.files

        if (!selectedFiles.length) {
            return false
        }

        for (let i = 0; i < selectedFiles.length; i++) {
            state.attachment.push(selectedFiles[i])
        }
    },

    borrarImagenOrdenTrabajo(state, imagen) {
        let url = urlEliminarImagenObservacion
        axios.post(url, imagen).then(response => {
            state.errorsLaravel = []
            toastr.success('La imagen se elimino correctamente')
            this.commit('getFotosOrdenTrabajo', response.data.id)
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },


    AgregarObservacion(state) {
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }

        let url = urlSubirObservacion

        for (let i = 0; i < state.attachment.length; i++) {
            state.form.append('imagenes_observacion[]', state.attachment[i])
        }

        state.form.append('id', state.newOrdenTrabajo.vehicle_id)
        state.form.append('observacion', state.newOrdenTrabajo.observacion)
        $("#filesObservacion").val(null)

        axios.post(url, state.form, config).then(response => {
            state.newOrdenTrabajo.vehicle_id = ''
            state.newOrdenTrabajo.observacion = ''
            state.attachment.length = []
            state.errorsLaravel = []
            $('#modalObservacion').modal('hide')

            if (response.data.length > 0) {
                toastr.success('La imagen se agrego correctamente')
            }
            toastr.success('La observacion se agrego correctamente')

        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    subirFotosObservacion(state, evt) {
        state.form = new FormData()

        state.images = []
        state.attachment = []
        let selectedFiles = evt.target.files

        if (!selectedFiles.length) {
            return false
        }

        for (let i = 0; i < selectedFiles.length; i++) {
            state.attachment.push(selectedFiles[i])
        }
    },


    modalCrearFormatoCheckList(state, id) {
        // state.newOrdenTrabajo.vehicle_id = id
        $("#CrearFormatoCheckList").modal('show')
    },

    agregarCategoria(state) {
        state.checklists.push({
            categoria: state.checkListForm.categoria,
            intervenciones: []
        })
        state.checkListForm.categoria = ''
    },

    crearCheckList(state) {
        if (state.checklists.length == 0) {
            toastr.error('¡Error, Agregue una categoria!')
        } else {
            state.crearFormatoCheckList = false
            state.crearIntervencionCheckList = true
        }
    },

    modalIntervencion(state, id) {
        state.intervencionForm.id_categoria = id
        state.intervencionCheckList = true
        state.crearIntervencionCheckList = false

        for (let index = 0; index < state.checklists.length; index++) {
            if (state.checklists[index].categoria === id) {
                let matchingInterventions = state.checklists[index].intervenciones.filter(intervencion => id === intervencion.id_categoria);
                state.checklists[index].intervenciones = matchingInterventions;
            }
        }
    },

    agregarIntervencion(state) {
        for (let index = 0; index < state.checklists.length; index++) {
            state.checklists[index].intervenciones.push({
                id_categoria: state.intervencionForm.id_categoria,
                intervencion: state.intervencionForm.intervencion
            })
        }
        state.intervencionForm.intervencion = ''
    },

    agregarIntervencionEditar(state) {
        state.intervenciones.push({
            id_categoria: state.intervencionForm.id_categoria,
            intervencion: state.intervencionForm.intervencion
        })
        state.intervencionForm.intervencion = ''
    },

    modalCerrarIntervencion(state) {
        state.crearIntervencionCheckList = true
        state.intervencionCheckList = false
    },

    eliminarIntervencionFormatCheckList(state, id) {
        for (let index = 0; index < state.checklists.length; index++) {
            state.checklists[index].intervenciones.splice(state.checklists[index].intervenciones.indexOf(id))
        }
    },
    eliminarFormatCheckListCategoria(state, id) {
        state.checklists.splice(state.checklists.indexOf(id))
    },

    finalizarFormatoCheckList(state) {
        if (state.checklists[0].intervenciones.length > 0) {
            let url = urlCrearCheckList
            axios.post(url, { checklists: state.checklists }).then(response => {
                if (response.data && response.status === 200) {
                    toastr.success('¡Se creo correctamente el formato del checklist!')
                    $("#CrearFormatoCheckList").modal('hide')
                }
            }).catch(error => {
                toastr.error(error.response.data)
            })
        } else {
            toastr.error('¡Falta agregar intervenciones!')
        }

    },

    modalCerrarIntervencionCheckList(state) {
        state.crearIntervencionCheckList = false
        state.crearFormatoCheckList = true
    },

    modalMostrarFormatoCheckList(state) {
        this.commit('mostrarFormatoCheckList')
        $("#MostrarFormatoCheckList").modal('show')
    },

    mostrarFormatoCheckList(state) {
        axios.get(urlMostrarFormatoCheckList).then(response => {
            state.formatchecklists = response.data
        }).catch(error => {
            toastr.error(error.response.data)
        })
    },


    modalEditarCategoria(state, formatchecklist) {
        state.editarCategoriaForm.categoria = formatchecklist.categoria
        state.editarCategoriaForm.id_categoria = formatchecklist.id
        $('#MostrarFormatoCheckList').modal('hide')
        $('#EditarCategoriaCheckList').modal('show')
    },

    editarCategoriaCheckList(state) {
        let url = urlEditarCategoria
        axios.post(url, {
            id_categoria: state.editarCategoriaForm.id_categoria,
            categoria: state.editarCategoriaForm.categoria,
        }).then(response => {
            this.commit('mostrarFormatoCheckList')
            $('#EditarCategoriaCheckList').modal('hide')
            $("#MostrarFormatoCheckList").modal('show')
        }).catch(error => {
            toastr.error(error.response.data)
        })

    },

    modalEditarIntervencion(state, intervenciones) {
        state.editarIntervencionForm.intervencion = intervenciones.intervencion
        state.editarIntervencionForm.id_intervencion = intervenciones.id
        $("#MostrarFormatoCheckList").modal('hide')
        $('#EditarIntervencionCheckList').modal('show')
    },

    editarIntervencionCheckList(state) {
        let url = urlEditarIntervecion
        axios.post(url, {
            id_intervencion: state.editarIntervencionForm.id_intervencion,
            intervencion: state.editarIntervencionForm.intervencion,
        }).then(response => {
            this.commit('mostrarFormatoCheckList')
            $('#EditarIntervencionCheckList').modal('hide')
            $("#MostrarFormatoCheckList").modal('show')
        }).catch(error => {
            toastr.error(error.response.data)
        })

    },

    modalAgregarCategoria(state) {
        $('#MostrarFormatoCheckList').modal('hide')
        $('#AgregarCategoria').modal('show')
    },

    modalAgregarIntervencion(state, id) {
        state.intervencionForm.id_categoria = id
        $('#MostrarFormatoCheckList').modal('hide')
        $('#AgregarIntervencion').modal('show')
    },

    crearCategoria(state) {
        if (state.checklists.length == 0) {
            toastr.error('¡Error, Agregue una categoria!')
        } else {
            let url = urlCrearCategoria
            axios.post(url, {
                checklists: state.checklists,
            }).then(response => {
                state.formatchecklists = response.data
                state.checklists = []
                $('#AgregarCategoria').modal('hide')
                $('#MostrarFormatoCheckList').modal('show')
                toastr.success('Se agrego la categoria correctamente')
            }).catch(error => {
                toastr.error(error.response.data)
            })
        }
    },

    crearIntervencion(state, id) {
        if (state.intervenciones.length == 0) {
            toastr.error('¡Error, Agregue una intervención!')
        } else {
            let url = urlCrearIntervencion + '/' + id
            axios.post(url, {
                intervenciones: state.intervenciones,
            }).then(response => {
                state.intervenciones = []
                this.commit('mostrarFormatoCheckList')
                $('#AgregarIntervencion').modal('hide')
                $('#MostrarFormatoCheckList').modal('show')
                toastr.success('Se agrego la intervención correctamente')
            }).catch(error => {
                toastr.error(error.response.data)
            })
        }
    },

    cerrarCategoria(state) {
        $('#AgregarCategoria').modal('hide')
        $('#EditarCategoriaCheckList').modal('hide')
        $('#MostrarFormatoCheckList').modal('show')
    },


    cerrarIntervencion(state) {
        $('#AgregarIntervencion').modal('hide')
        $('#EditarIntervencionCheckList').modal('hide')
        $('#MostrarFormatoCheckList').modal('show')
    },

    modalCheckList(state, vehicle) {
        state.id_vehicle = vehicle.id
        state.km_old = vehicle.km
        this.commit('mostrarChecklist', state.id_vehicle)
        $('#checkListVehicle').modal('show')
    },

    mostrarChecklist(state, id) {
        let url = urlMostrarCheckList + '/' + id
        axios.get(url).then(response => {
            state.formatchecklists = response.data
        })
    },

    modalObservacionVehicleCheckList(state, data) {
        state.intervenciones = data.intervenciones.filter(intervencion => intervencion.id === data.id_intervencion)
        state.columnaObservacion.id_intervencion = data.id_intervencion
        state.columnaObservacion.id_vehicle = data.id_vehicle
        $('#agregarObservacionCheckList').modal('show')
        $('#checkListVehicle').modal('hide')
    },

    modalCerrarObservacionVehicleCheckList(state) {
        $('#agregarObservacionCheckList').modal('hide')
        $('#checkListVehicle').modal('show')
    },

    subirFotosObservacionCheckList(state, evt) {
        state.form = new FormData()

        state.attachment = []
        let selectedFiles = evt.target.files

        if (!selectedFiles.length) {
            return false
        }

        for (let i = 0; i < selectedFiles.length; i++) {
            state.attachment.push(selectedFiles[i])
        }
    },

    borrarImagenChecklist(state, imagen) {
        let url = urlEliminarImagenChecklist
        axios.post(url, imagen).then(response => {
            state.errorsLaravel = []
            $('#checkListVehicle').modal('show')
            $('#agregarObservacionCheckList').modal('hide')
            toastr.success('La imagen se elimino correctamente')
            this.commit('mostrarChecklist', state.id_vehicle)
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    botonSi(state, data) {
        switch (data.componente) {
            case 'borrarOservacionChecklist':
                axios.post(urlEliminarObservacionChecklist, { id: data.id }).then(response => {
                    state.errorsLaravel = []
                    $('#checkListVehicle').modal('show')
                    $('#agregarObservacionCheckList').modal('hide')
                    toastr.success('La Observacion se elimino correctamente')
                    this.commit('mostrarChecklist', state.id_vehicle)
                }).catch(error => {
                    state.errorsLaravel = error.response.data
                })
                break;

            case 'borrarOservacion':
                axios.post(urlEliminarEditarObservacion, { id: data.id }).then(response => {
                    state.errorsLaravel = []
                    // $('#checkListVehicle').modal('show')
                    // $('#agregarObservacionCheckList').modal('hide')
                    toastr.success('La Observacion se elimino correctamente')
                    this.commit('getObservaciones', state.newOrdenTrabajo.vehicle_id)
                }).catch(error => {
                    state.errorsLaravel = error.response.data
                })
                break;
        }

    },

    agregarObservacionCheckList(state) {
        const config = {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }

        for (let i = 0; i < state.attachment.length; i++) {
            state.form.append('imagenes_checklist[]', state.attachment[i])
        }

        let url = urlAgregarObservacionCheckList
        state.form.append('id_intervencion_checklist', state.columnaObservacion.id_intervencion)
        state.form.append('id_vehicle_checklist', state.columnaObservacion.id_vehicle)
        state.form.append('observacion_checklist', state.columnaObservacion.observacion)
        $("#filesObservacion").val(null)

        axios.post(url, state.form, config).then(response => {
            state.columnaObservacion.id_intervencion = ''
            state.columnaObservacion.observacion = ''
            state.columnaObservacion.id_vehicle = ''
            state.attachment.length = []
            state.errorsLaravel = []
            $('#agregarObservacionCheckList').modal('hide')

            if (response.data.length > 0) {
                toastr.success('La imagen se agrego correctamente')
            }
            toastr.success('La observacion se agrego correctamente')

        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },



    setCheckExisteSi(state, value) {
        state.checkExisteSi = value

        state.columnaExiste.push({
            id_intervencion: state.checkExisteSi,
            existe: 'si'
        })

        $("input[name='estado" + value + "']").prop('disabled', false);
    },

    setcheckExisteNo(state, value) {
        state.checkExisteNo = value

        state.columnaExiste.push({
            id_intervencion: state.checkExisteNo,
            existe: 'no',
        })

        $("input[name='estado" + value + "']").prop('disabled', true);

    },


    setCheckEstadoBueno(state, value) {
        state.checkEstadoBueno = value

        state.columnaEstado.push({
            id_intervencion: state.checkEstadoBueno,
            estado: 'bueno',
        })
    },
    setCheckEstadoRegular(state, value) {
        state.checkEstadoRegular = value

        state.columnaEstado.push({
            id_intervencion: state.checkEstadoRegular,
            estado: 'regular',
        })
    },
    setCheckEstadoMalo(state, value) {
        state.checkEstadoMalo = value

        state.columnaEstado.push({
            id_intervencion: state.checkEstadoMalo,
            estado: 'malo',
        })
    },

    setKilometraje(state, value) {
        state.kilometraje = value
    },


    guardarCheckList(state) {
        let url = urlGuardarCheckListVehicle

        if (state.kilometraje < state.kilometrajeActual) {
            toastr.error('El kilometraje no puede ser menor o igual al actual')
        } else {
            axios.post(url, {
                id_vehicle: state.id_vehicle,
                existe: state.columnaExiste,
                estado: state.columnaEstado,
                kilometraje: state.kilometraje
            }).then(response => {
                state.columnaExiste = [];
                state.columnaEstado = [];
                state.kilometrajeActual = state.kilometraje
                state.newOrdenTrabajo.tendenciaKm = parseInt(state.kilometraje) + 20000

                $('input[type="radio"]').prop('checked', false);
                $('#checkListVehicle').modal('hide')
                toastr.success('El check list se ingreso correctamente')
            }).catch(error => {
                state.errorsLaravel = error.response.data
            })
        }
    },

    getCheckListVehicles(state) {
        let url = urlCheckListVehicles
        axios.get(url).then(response => {
            state.checklistvehicles = response.data
        })
    },

    modalMostrarCheckListVehicle(state, id) {
        state.id_checklist = id
        let url = urlMostrarCheckListVehicles + '/' + id
        axios.get(url).then(response => {
            state.mostrarchecklistvehicles = response.data
        })
        $("#MostrarCheckListVehicle").modal('show')
    },

    mostrarCondiciones(state, data) {
        state.mostrarCheckListVehicle = true
        state.mostrarObservacion = false
        let url = urlMostrarCondiciones
        axios.get(url, {
            params: {
                id_categoria: data.id_categoria
            }
        }).then(response => {
            state.intervenciones = response.data
        })
    },

    modalMostrarObservacion(state, data) {
        if (data.imagenes.length > 0) {
            state.observaciones = {
                observacion: data.observacion,
                imagenes: data.imagenes
            }
            state.mostrarCheckListVehicle = false
            state.mostrarObservacion = true
        }

    },

    cerrarMostrarObservacion(state) {
        state.mostrarCheckListVehicle = true
        state.mostrarObservacion = false
    },

    cerrarMostrarCheckListVehicle(state) {
        $('.collapse').collapse("hide")
    },

    getCheckListRoles(state) {
        let url = urlCheckListRoles
        axios.get(url).then(response => {
            state.roleschecklists = response.data
        })
    },



    modalDetailVehicle(state, vehicle) {
        state.newDetailVehicle.vehicle_id = vehicle[0].id
        state.newDetailVehicle.rol = vehicle[1]
        state.newDetailVehicle.km = vehicle[0].km
        state.kilometrajeActual = vehicle[0].km
        $("#createDetail").modal('show')
    },

    createDetailVehicle(state) {

        const config = {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }

        for (let i = 0; i < state.attachment.length; i++) {
            state.form.append('pics[]', state.attachment[i])
        }
        state.form.append('vehicle_id', state.newDetailVehicle.vehicle_id)
        state.form.append('km', state.newDetailVehicle.km)
        state.form.append('note', state.newDetailVehicle.note)

        let url = urlDetailVehicle
        axios.post(url, state.form, config).then(response => {
            state.newDetailVehicle.vehicle_id = ''
            state.newDetailVehicle.km = ''
            state.newDetailVehicle.note = ''
            state.details = []
            state.errorsLaravel = []

            $('#createDetail').modal('hide')
            toastr.success('Detalle del vehículo generado con éxito')

        }).catch(error => {
            toastr.error(error.response.data)
        })

    },
    onFileChange(state, evt) {
        state.import_file = evt.target.files[0]
    },
    createProductsPagos(state) {
        let formData = new FormData();
        formData.append('import_file', state.import_file);
        formData.append('client', state.selectedClient.value);
        formData.append('pago', state.selectedPago.label);
        formData.append('utilidad', state.selectedPago.utilidad);

        let url = urlProduct
        axios.post(url, formData, {
            headers: { 'content-type': 'multipart/form-data' }
        }).then(response => {
            if (response.status === 200) {
                $('#createProducts').modal('hide')
                toastr.success('Los Productos se subieron correctamente!')
            }
        }).catch(error => {
            $('#createProducts').modal('hide')
            toastr.error(error.response.data)
        });
    },

    updateVehicle(state, id) {
        let url = urlVehicle + '/' + id
        axios.put(url, {
            id: state.fillVehicle.id,
            patent: state.fillVehicle.patent,
            chasis: state.fillVehicle.chasis,
            brand: state.selectedVBrand.label,
            model: state.selectedVModel.label,
            year: state.selectedVYear.label,
            engine: state.selectedVEngine.label,
            color: state.fillVehicle.color,
            km: state.fillVehicle.km
        }).then(response => {
            state.fillVehicle.id = ''
            state.fillVehicle.patent = ''
            state.fillVehicle.chasis = ''
            state.fillVehicle.color = ''
            state.fillVehicle.km = ''
            state.selectedVBrand.label = '',
                state.selectedVBrand.value = '',
                state.selectedVModel.label = '',
                state.selectedVModel.value = '',
                state.selectedVYear.label = '',
                state.selectedVYear.value = '',
                state.selectedVEngine.label = '',
                state.selectedVEngine.value = '',
                state.errorsLaravel = []
            $('#edit').modal('hide')
            toastr.success('Vehículo actualizado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    getDetails(state, detail) {
        let url = urlVehicle + '/' + detail.id
        axios.get(url).then(response => {
            state.details = response.data
        })
    },
    fileChange(state, evt) {
        state.form = new FormData()

        state.images = []
        state.attachment = []
        let selectedFiles = evt.target.files

        if (!selectedFiles.length) {
            return false
        }

        for (let i = 0; i < selectedFiles.length; i++) {
            state.attachment.push(selectedFiles[i])
        }
    },
    deleteImage(state, id) {
        let url = urlImages + '/' + id
        axios.delete(url).then(response => {
            toastr.success('Imagen eliminada con éxito')
            $('#photo').modal('hide')
        })
    },
    getVehiculoTipos(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination_tipo.per_page || 20)
        let url = 'vehiculotipos-all?page=' + page + '&per_page=' + perPage
        axios.get(url).then(response => {
            state.vehiculotipos = response.data.vehiculotipos.data
            state.pagination_tipo = response.data.pagination_tipo
        })
    },
    createVehiculoTipo(state) {
        let url = 'newvehiculotipo'
        axios.post(url, {
            tipo_vehiculo: state.newVehiculoTipo.tipo_vehiculo.toUpperCase()
        }).then(response => {
            state.newVehiculoTipo = {
                tipo_vehiculo: ''
            },
                state.errorsLaravel = []
            $('#create').modal('hide')
            toastr.success('Tipo de vehiculo creado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    editVehiculoTipo(state, vehiculotipo) {
        state.fillVehiculoTipo.id = vehiculotipo.id
        state.fillVehiculoTipo.tipo_vehiculo = vehiculotipo.tipo_vehiculo.toUpperCase()
        $("#edit_tipo").modal('show')
    },
    updateVehiculoTipo(state, id) {
        let url = urlVehiculoTipo + '/' + id
        axios.put(url, state.fillVehiculoTipo).then(response => {
            state.fillVehicle = {
                id: '',
                tipo_vehiculo: '',
            }
            state.errorsLaravel = []
            $('#edit_tipo').modal('hide')
            toastr.success('Tipo de vehiculo actualizada con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    getVehicleBrands(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination_marca.per_page || 20)
        let url = 'vehiclebrands-all?page=' + page + '&per_page=' + perPage

        axios.get(url).then(response => {
            state.vehiclebrands = response.data.vehiclebrands.data
            state.pagination_marca = response.data.pagination_marca

        })
    },
    createVehicleBrand(state) {
        let url = 'newvehiclebrand'
        axios.post(url, {
            brand: state.newVehicleBrand.brand.toUpperCase()
            //model: state.newVehicleBrand.model.toUpperCase(),
            //tipo_id: state.selectedVehiculoTipo.value
        }).then(response => {
            state.newVehicleBrand = {
                brand: ''
                //model: '',
                //tipo_id: ''
            },
                state.errorsLaravel = []
            $('#create').modal('hide')
            toastr.success('Marca y Modelo generado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    editVehicleBrand(state, vehiclebrand) {
        state.fillVehicleBrand.id = vehiclebrand.id
        state.fillVehicleBrand.brand = vehiclebrand.brand.toUpperCase()
        $("#edit").modal('show')
    },
    updateVehicleBrand(state, id) {
        let url = urlVehicleBrand + '/' + id
        axios.put(url, state.fillVehicleBrand).then(response => {
            state.fillVehicle = {
                id: '',
                brand: '',
                model: ''
            }
            state.errorsLaravel = []
            $('#edit').modal('hide')
            toastr.success('Marca actualizada con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    getVehicleModels(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination_modelo.per_page || 20)
        let url = urlVehicleModel + '?page=' + page + '&per_page=' + perPage //+ '&model=' + state.searchVehicleBrand.model
        axios.get(url).then(response => {
            state.vehiclemodels = response.data.vehiclemodels.data
            state.pagination_modelo = response.data.pagination_modelo
        });
    },
    createVehicleModel(state) {
        let url = 'newvehiclemodelo'
        axios.post(url, {
            //brand_id: state.selectedVehicleBrand.value,
            model: state.newVehicleModelo.model.toUpperCase(),
            brand_id: state.selectedVehicleBrand.value,
            tipo_id: state.selectedVehiculoTipo.value
        }).then(response => {
            state.newVehicleModelo = {
                model: '',
                brand_id: '',
                tipo_id: ''
            },
                state.errorsLaravel = []
            $('#create').modal('hide')
            toastr.success('Modelo generado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    editVehicleModel(state, vehiclemodel) {
        state.optionsVehicleBrand.forEach(brand => {
            if (brand.label == vehiclemodel.brand) {
                state.selectedVehicleBrand = brand
            }
        })
        state.optionsTiposVehiculo.forEach(tipo_vehiculo => {
            if (tipo_vehiculo.label == vehiclemodel.tipo) {
                state.selectedVehiculoTipo = tipo_vehiculo
            }
        })
        state.fillVehicleModel.id = vehiclemodel.id
        state.fillVehicleModel.model = vehiclemodel.model.toUpperCase()
        $("#edit_modelo").modal('show')
    },
    updateVehicleModel(state, id) {
        let url = urlVehicleModel + '/' + id
        state.fillVehicleModel.brand_id = state.selectedVehicleBrand.value
        state.fillVehicleModel.tipo_id = state.selectedVehiculoTipo.value
        axios.put(url, state.fillVehicleModel).then(response => {
            state.fillVehicleModel = {
                id: '',
                model: '',
                brand_id: '',
                tipo_id: ''
            },
                state.errorsLaravel = []
            $('#edit_modelo').modal('hide')
            toastr.success('Modelo actualizado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    createVehicleYear(state) {
        let url = 'newvehicleyear'
        axios.post(url, {
            v_id: state.selectedVMR.value,
            v_year: state.newVehicleYear.v_year.toUpperCase()
        }).then(response => {
            state.newVehicleYear = {
                v_id: '',
                v_year: ''
            },
                state.errorsLaravel = []
            $('#create').modal('hide')
            toastr.success('Modelo generado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    editVehicleYear(state, vehicleyear) {
        /*state.optionsVehicleModel.forEach(model => {
            if (model.label == vehicleyear.model) {
                state.selectedVehicleModel = model
            }
        })*/
        state.fillVehicleYear.id = vehicleyear.id
        state.fillVehicleYear.v_year = vehicleyear.year
        $("#edit_year").modal('show')
    },
    updateVehicleYear(state, id) {
        let url = urlVehicleYear + '/' + id
        //state.fillVehicleYear.model = state.selectedVehicleModel.value
        axios.put(url, state.fillVehicleYear).then(response => {
            state.fillVehicleYear = {
                id: '',
                v_year: ''
                //model: ''
            },
                state.errorsLaravel = []
            $('#edit_year').modal('hide')
            toastr.success('Modelo actualizado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    getVehicleYears(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination_year.per_page || 20)
        let url = 'vehicleyears-all?page=' + page + '&per_page=' + perPage
        axios.get(url).then(response => {
            state.vehicleyears = response.data.vehicleyears.data
            state.pagination_year = response.data.pagination_year
        });
    },

    createVehicleMotor(state) {
        let url = 'newvehiclemotor'
        axios.post(url, {
            v_engine: state.newVehicleMotor.v_engine.toUpperCase(),
            year_id: state.selectedYM.value
        }).then(response => {
            state.newVehicleMotor = {
                year_id: '',
                v_engine: ''
            },
                state.errorsLaravel = []
            $('#create').modal('hide')
            toastr.success('Motor agregado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    updateVehicleMotor(state, id) {
        let url = urlVehicleMotor + '/' + id
        //state.fillVehicleMotor.year_id = state.selectedVehicleYear.value
        axios.put(url, state.fillVehicleMotor).then(response => {
            state.fillVehicleMotor = {
                id: '',
                //year_id: '',
                v_engine: ''
            },
                state.errorsLaravel = []
            $('#edit_motor').modal('hide')
            toastr.success('Motor actualizado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    editVehiculoMotor(state, vehiclemotor) {
        /*state.optionsYear.forEach(year => {
            if (year.label == vehiclemotor.year) {
                state.selectedVehicleYear = year
            }
        })*/
        state.fillVehicleMotor.id = vehiclemotor.id
        state.fillVehicleMotor.v_engine = vehiclemotor.motor
        $("#edit_motor").modal('show')
    },

    getVehiculoMotors(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination_motor.per_page || 20)
        let url = 'vehiclemotors-all?page=' + page + '&per_page=' + perPage
        axios.get(url).then(response => {
            state.vehiclemotors = response.data.vehiclemotors.data
            state.pagination_motor = response.data.pagination_motor
        });
    },


    /******************************* */
    /****** sección notas **** */
    /******************************* */
    getNotes(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination.per_page || 20)
        let url = urlNote + '?page=' + page + '&per_page=' + perPage
        axios.get(url).then(response => {
            state.notes = response.data.notes.data
            state.pagination = response.data.pagination
        });
    },
    createNote(state) {
        let url = urlNote
        axios.post(url, {
            price: state.newNote.price,
            detail: state.newNote.detail,
        }).then(response => {
            state.newNote.price = ''
            state.newNote.detail = ''
            state.errorsLaravel = []
            $('#create').modal('hide')
            toastr.success('Nota generada con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    editNote(state, note) {
        state.fillNote.id = note.id
        state.fillNote.price = note.price
        state.fillNote.detail = note.detail
        $("#edit").modal('show')
    },
    updateNote(state, id) {
        let url = urlNote + '/' + id
        axios.put(url, state.fillNote).then(response => {
            state.fillNote = {
                id: '',
                price: '',
                detail: ''
            }
            state.errorsLaravel = []
            $('#edit').modal('hide')
            toastr.success('Nota actualizada con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    deleteNote(state, id) {
        let url = urlNote + '/' + id
        axios.delete(url).then(response => {
            toastr.success('Nota eliminada con éxito')
        })
    },
    /******************************* */
    /****** sección cotizaciones **** */
    /******************************* */
    getQuotations(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination.per_page || 20)
        let url = urlQuotation + '?page=' + page + '&per_page=' + perPage
        axios.get(url).then(response => {
            state.quotations = response.data.quotations.data
            state.pagination = response.data.pagination
        });
    },
    getQuotationDetails(state) {
        let url = urlQuotationDetails + '/' + state.idQuotation
        axios.get(url).then(response => {
            state.details = response.data
            let total = 0
            state.details.forEach(detail => {
                total += parseInt(detail.price)
            })
            state.totalQuotation = total
            state.totalQuotationIVA = Math.round(total * 1.19)
        });
    },
    createQuotation(state) {
        let url = urlQuotation
        axios.post(url, {
            client: state.newQuotation.client,
            vehicle: state.newQuotation.vehicle,
            patent: state.newQuotation.patent,
            state: 'Pendiente',
        }).then(response => {
            state.newQuotation = {
                client: '',
                vehicle: '',
                patent: '',
                state: ''
            }
            state.errorsLaravel = []
            $('#create').modal('hide')
            $('#btn-quotation-card').click()
            toastr.success('Cotización generada con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    editQuotation(state, quotation) {
        state.fillQuotation.id = quotation.id
        state.fillQuotation.client = quotation.client
        state.fillQuotation.vehicle = quotation.vehicle
        state.fillQuotation.patent = quotation.patent
        state.fillQuotation.state = quotation.state
        $("#edit").modal('show')
    },
    updateQuotation(state, id) {
        let url = urlQuotation + '/' + id
        axios.put(url, state.fillQuotation).then(response => {
            state.fillQuotation = {
                id: '',
                client: '',
                vehicle: '',
                patent: '',
                state: ''
            }
            state.errorsLaravel = []
            $('#edit').modal('hide')
            toastr.success('cotización actualizada con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    deleteQuotation(state, id) {
        let url = urlQuotation + '/' + id
        axios.delete(url).then(response => {
            toastr.success('cotización eliminada con éxito')
        })
    },
    pdfQuotation(state) {
        let url = urlQuotationPdf + '/' + state.idQuotation
        window.location.href = url;
    },
    /******************************* */
    /****** sección detalles **** */
    /******************************* */

    createDetail(state) {
        let url = urlDetail
        let priceSet = state.newDetail.price
        priceSet = priceSet.replace('.', '')
        axios.post(url, {
            quotation_id: state.idQuotation,
            product: state.newDetail.product,
            price: priceSet,
        }).then(response => {
            state.newDetail = {
                quotation_id: '',
                detail: '',
                price: 1
            }
            state.errorsLaravel = []
            toastr.success('Detalle generado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    editDetail(state, detail) {
        state.fillDetail.id = detail.id
        state.fillDetail.quotation_id = detail.quotation_id
        state.fillDetail.detail = detail.detail
        state.fillDetail.price = detail.price
        $("#edit").modal('show')
    },
    updateDetail(state, id) {
        let url = urlDetail + '/' + id
        axios.put(url, state.fillDetail).then(response => {
            state.fillDetail = {
                id: '',
                quotation_id: '',
                detail: '',
                price: ''
            }
            state.errorsLaravel = []
            $('#edit').modal('hide')
            toastr.success('Detalle actualizado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    deleteDetail(state, id) {
        let url = urlDetail + '/' + id
        axios.delete(url).then(response => {
            toastr.success('Detalle eliminado con éxito')
        })
    },
    /******************************* */
    /****** sección clientes **** */
    /******************************* */
    getClients(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination.per_page || 20)
        let url = urlClient + '?page=' + page + '&per_page=' + perPage
        axios.get(url).then(response => {
            state.clients = response.data.clients.data
            state.pagination = response.data.pagination
        });
    },
    detailClient(state, client) {
        state.client.id = client.id
        state.client.name = client.name
        state.client.rut = client.rut
        state.client.razonSocial = client.razonSocial
        state.client.email = client.email
        state.client.phone = client.phone
        state.client.address = client.address
        state.client.comuna = client.comuna
        state.client.giro = client.giro
        state.client.type = client.type
        state.client.activities = client.activities
        $("#detail").modal('show')
    },
    createClient(state) {
        let url = urlClient
        axios.post(url, {
            user_id: state.newClient.type,
            name: state.newClient.name,
            rut: state.newClient.rut,
            razonSocial: state.newClient.razonSocial,
            email: state.newClient.email,
            phone: state.newClient.phone,
            address: state.newClient.address,
            comuna: state.newClient.comuna,
            giro: state.newClient.giro,
            type: state.newClient.type,
        }).then(response => {

            let url = urlActivity
            let idClient = response.data

            if (state.newClient.activity != null) {
                state.newClient.activity.forEach(actividad => {
                    axios.post(url, {
                        client_id: idClient,
                        name: actividad.actividadEconomica,
                    }).then(response => {
                        toastr.success('Giro Ingresado con éxito')
                    })
                })
            }

            state.newClient = {
                rut: '',
                razonSocial: '',
                email: '',
                phone: '',
                address: '',
                comuna: '',
                giro: '',
                type: '',
                activities: {}
            }
            state.errorsLaravel = []
            $('#create').modal('hide')
            $('#btn-client-card').click()
            toastr.success('Cliente generado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    editClient(state, client) {
        state.fillClient.id = client.id
        state.fillClient.user_id = client.user_id
        state.fillClient.name = client.name
        state.fillClient.rut = client.rut
        state.fillClient.razonSocial = client.razonSocial
        state.fillClient.email = client.email
        state.fillClient.phone = client.phone
        state.fillClient.address = client.address
        state.fillClient.comuna = client.comuna
        state.fillClient.giro = client.giro
        state.fillClient.type = client.type
        $("#edit").modal('show')
    },
    updateClient(state, id) {
        let url = urlClient + '/' + id
        axios.put(url, state.fillClient).then(response => {
            state.fillUser = {
                id: '',
                user_id: '',
                name: '',
                rut: '',
                razonSocial: '',
                email: '',
                phone: '',
                address: '',
                comuna: '',
                type: '',
                activities: {}
            }
            state.errorsLaravel = []
            $('#edit').modal('hide')
            toastr.success('Cliente actualizado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    modalDeleteClient(state, id) {
        state.fillClient.id = id
        $('#deleteClient').modal('show')
    },
    deleteClient(state) {
        let url = urlClient + '/' + state.fillClient.id
        axios.delete(url).then(response => {
            toastr.success('cliente eliminado con éxito')
            $('#deleteClient').modal('hide')
        })
    },
    /******************************* */
    /****** sección cotizaciones clientes**** */
    /******************************* */


    getQuotationclients(state, request) {
        const { page } = resolvePaginationRequest(request, state.searchQuotationClient.per_page || 20)
        let id = state.searchQuotationClient.id
        let razonSocial = state.searchQuotationClient.razonSocial
        let client = state.searchQuotationClient.client
        let vehicle = state.searchQuotationClient.vehicle
        let day = state.searchQuotationClient.day
        let month = state.searchQuotationClient.month
        let year = state.searchQuotationClient.year
        let perPage = state.searchQuotationClient.per_page

        let url = urlQuotationclient + '?page=' + page + '&id=' + id + '&razonSocial=' + razonSocial + '&client=' + client + '&vehicle=' + vehicle + '&day=' + day + '&month=' + month + '&year=' + year + '&per_page=' + perPage

        axios.get(url).then(response => {
            state.quotationclients = response.data.quotationclients.data
            state.pagination = response.data.pagination
        });
    },

    getQuotationclientsform(state, request) {
        const { page } = resolvePaginationRequest(request, state.searchQuotationClientForm.per_page || 20)
        let id = state.searchQuotationClientForm.id
        let razonSocial = state.searchQuotationClientForm.razonSocial
        let client = state.searchQuotationClientForm.client
        let vehicle = state.searchQuotationClientForm.vehicle
        let day = state.searchQuotationClientForm.day
        let month = state.searchQuotationClientForm.month
        let year = state.searchQuotationClientForm.year
        let perPage = state.searchQuotationClientForm.per_page

        let url = urlQuotationclientform + '?page=' + page + '&id=' + id + '&razonSocial=' + razonSocial + '&client=' + client + '&vehicle=' + vehicle + '&day=' + day + '&month=' + month + '&year=' + year + '&per_page=' + perPage


        axios.get(url).then(response => {
            state.quotationclientsform = response.data.quotationclientsform.data
            state.pagination_form = response.data.pagination_form
        });
    },

    getQuotationlinkenvio(state) {
        let url = 'quotationlinkenvio'
        axios.get(url).then(response => {
            state.linkenvio.url = window.location.host + "/cotizar-envio/" + response.data
        });
    },


    getQuotationShipping(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination_shipping.per_page || 20)
        let id = state.searchShipping.id
        let url = 'quotationshipping?page=' + page + '&id=' + id + '&per_page=' + perPage
        axios.get(url).then(response => {
            state.quotationshipping = response.data.quotationshipping.data
            state.pagination_shipping = response.data.pagination_shipping
        });
    },


    setcheckRealizado(state, value) {
        $('#modalAlertaInformacion').modal('show')
        this.commit('getObservaciones', value[0])
        this.commit('getFotosOrdenTrabajo', value[0])
        state.id_trabajo = value[0]
        state.checkRealizado = value
    },

    cerrarRealizado(state) {
        let url = 'checkRealizado'
        axios.post(url, {
            check: state.checkRealizado
        }).then(response => {
            state.checkRealizado = [];
            state.errorsLaravel = [];
            toastr.success('El trabajo se realizo correctamente')
            this.commit('getOrdenesTrabajos')
            $('#modalAlertaInformacion').modal('hide')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    deleteRealizado(state, id) {
        let indice = state.checkRealizado.findIndex(element => element === id);
        if (indice !== -1) {
            state.checkRealizado.splice(indice, 1);
        }
    },

    setCheckEnviado(state, value) {
        state.checkEnviado = value

        let url = 'checkEnviado'
        axios.post(url, {
            check: state.checkEnviado
        }).then(response => {
            state.checkEnviado = [];
            state.errorsLaravel = [];
            toastr.success('El producto se envio correctamente')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    deleteEnviado(state, id) {
        let url = 'deleteEnviado/' + id
        axios.post(url, {
            check: id
        }).then(response => {
            toastr.error('El producto no se a enviado')
        })
    },



    showModalDetail(state, id) {
        state.idforms = id
        $('#detalleCliente').modal('show')
    },
    showModalDetailMechanic(state, id) {
        state.idforms = id
        $('#detalleClienteMechanic').modal('show')
    },

    modalCreateUserMechanicFromQuotation(state, id) {
        state.idforms = id
        $('#modalCreateUserMechanic').modal('show')
    },
    getQuotationforms(state) {
        let url = urlQuotationforms + '/' + state.idforms
        axios.get(url).then(response => {
            state.quotationforms = response.data
        });
    },
    showModalDetailclient(state, id) {
        state.idQuotationclient = id
        state.checkedSpareParts = false
        $('#modalQuotationclient').modal('show')
    },
    showModalDetailclientMechanic(state, id) {
        state.idQuotationclient = id
        $('#modalQuotationclientMechanic').modal('show')
    },
    getQuotationclientDetails(state) {
        let url = urlQuotationclientDetails + '/' + state.idQuotationclient
        axios.get(url).then(response => {
            state.detailclients = response.data
            let totalAdicional = 0
            let totalTransporte = 0
            let totalUtilidad = 0
            let tota_iva = 0
            let total_neto = 0

            state.detailclients.forEach(detailclient => {
                let percentage = (parseInt(detailclient.percentage) / 100) + 1
                let flete = parseInt(detailclient.transport)
                let aditional = parseInt(detailclient.aditional)
                let quantity = parseInt(detailclient.quantity)
                let price_neto = Math.round(parseInt((detailclient.price * percentage) + aditional + flete / 1.19) * quantity)

                detailclient['price_neto'] = price_neto
                total_neto += price_neto
                tota_iva += detailclient.total
                totalUtilidad += parseInt(detailclient.utility)
                totalTransporte += parseInt(detailclient.transport)
                totalAdicional += parseInt(detailclient.aditional)
            })

            state.totalAdicional = totalAdicional
            state.totalTransporte = totalTransporte
            state.totalUtilidad = totalUtilidad
            state.totalQuotationclient = total_neto
            state.totalQuotationclientIVA = tota_iva
        });
    },
    getModelProductSuggestions(state) {
        if (!state.idQuotationclient) {
            state.modelProductSuggestions = []
            return
        }

        let url = urlQuotationclientProductSuggestions + '/' + state.idQuotationclient + '/product-suggestions'
        axios.get(url).then(response => {
            state.modelProductSuggestions = response.data.suggestions || []
            sortProductsByModelRelations(state)
        }).catch(() => {
            state.modelProductSuggestions = []
        })
    },
    getDeliveryTimes(state) {
        axios.get(urlDeliveryTime).then(response => {
            syncDeliveryTimesState(state, response.data)
        });
    },
    createDeliveryTime(state) {
        axios.post(urlDeliveryTime, state.newDeliveryTime).then(response => {
            syncDeliveryTimesState(state, response.data)
            state.newDeliveryTime = {
                label: '',
                is_default: false
            }
            state.errorsLaravel = []
            toastr.success('Plazo de entrega guardado con exito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    setDefaultDeliveryTime(state, deliveryTime) {
        axios.put(urlDeliveryTime + '/' + deliveryTime.id, {
            label: deliveryTime.label,
            is_default: true
        }).then(response => {
            syncDeliveryTimesState(state, response.data)
            state.errorsLaravel = []
            toastr.success('Plazo por defecto actualizado con exito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    deleteDeliveryTime(state, id) {
        axios.delete(urlDeliveryTime + '/' + id).then(response => {
            syncDeliveryTimesState(state, response.data)
            state.errorsLaravel = []
            toastr.success('Plazo eliminado con exito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    createQuotationclient(state) {
        let url = urlQuotationclient

        let clientId = state.selectedClient && state.selectedClient.value !== '' ? state.selectedClient.value : 1
        let vehicleParts = [
            state.selectedVBrand.label,
            state.selectedVModel.label,
            state.selectedVYear.label,
            state.selectedVEngine.label
        ].filter(part => part && part.trim() !== '')

        if (state.newQuotationclient.cliente_part == true) {
            clientId = 1
        }

        axios.post(url, {
            client_id: clientId,
            state: 'Pendiente',
            payment: state.selectedPago.label,
            client_text: state.newQuotationclient.client_text,
            cliente_part: state.newQuotationclient.cliente_part,
            url: state.newQuotationclient.url,
            telefono: state.newQuotationclient.telefono,
            vehicle: vehicleParts.join(' '),
            vehicle_model_id: state.selectedVModel.value || null,
            ppu: state.newQuotationclient.ppu,
            internal_number: state.newQuotationclient.internal_number,
            chasis: state.newQuotationclient.chasis,
            motor_number: state.newQuotationclient.motor_number
        }).then(response => {
            state.newQuotationclient = {
                client_id: '',
                client_text: '',
                state: '',
                payment: '',
                cliente_part: false,
                url: '',
                telefono: '',
                vehicle: '',
                generado: '',
                generado_client: '',
                ppu: '',
                internal_number: '',
                chasis: '',
                motor_number: ''
            }
            state.selectedClient = { label: '', value: '' }
            state.selectedPago = { label: '', value: '' }
            state.selectedVBrand = { label: '', value: '' }
            state.selectedVModel = { label: '', value: '' }
            state.selectedVYear = { label: '', value: '' }
            state.selectedVEngine = { label: '', value: '' }
            state.errorsLaravel = []
            toastr.success('Cotización formal generada con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    editQuotationclient(state, quotationclient) {
        state.fillQuotationclient.id = quotationclient.id
        state.fillQuotationclient.client_id = quotationclient.client_id
        state.fillQuotationclient.state = quotationclient.state
        state.fillQuotationclient.payment = quotationclient.payment || ''
        state.fillQuotationclient.client_text = quotationclient.client_text || ''
        state.fillQuotationclient.vehicle = quotationclient.vehicle || ''
        state.fillQuotationclient.url = quotationclient.url || ''
        state.fillQuotationclient.telefono = quotationclient.telefono || ''
        state.fillQuotationclient.ppu = quotationclient.ppu || ''
        state.fillQuotationclient.internal_number = quotationclient.internal_number || ''
        state.fillQuotationclient.chasis = quotationclient.chasis || ''
        state.fillQuotationclient.motor_number = quotationclient.motor_number || ''
        $("#editQuotationclient").modal('show')
    },
    updateQuotationclient(state, id) {
        let url = urlQuotationclient + '/' + id
        axios.put(url, state.fillQuotationclient).then(response => {
            state.fillQuotationclient = {
                id: '',
                client_id: '',
                state: '',
                payment: '',
                client_text: '',
                vehicle: '',
                url: '',
                telefono: '',
                ppu: '',
                internal_number: '',
                chasis: '',
                motor_number: ''
            }
            state.errorsLaravel = []
            $('#editQuotationclient').modal('hide')
            toastr.success('Cotización formal actualizada con éxito')
        }).catch(error => {
        })
    },
    replicateQuotationclient(state, id) {
        axios.post(urlQuotationclient + '/' + id + '/replicate').then(response => {
            toastr.success('CotizaciÃ³n formal duplicada con Ã©xito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    showModalDeleteQuotationclient(state, id) {
        state.idQuotationclient = id
        $('#modalDeleteQuotationClient').modal('show')
    },
    showdeleteQuotationShipping(state, id) {
        state.idQuotationShipping = id
        $('#modaldeleteQuotationShipping').modal('show')
    },
    showQuotationShipping(state, id) {
        state.fillQuotationShipping.id = id
        $('#modalQuotationShipping').modal('show')
    },
    editFacebook(state, facebookshipping) {
        state.fillFacebookShipping.id = facebookshipping.id
        state.fillFacebookShipping.url = facebookshipping.url
        $('#modalEditFacebook').modal('show')
    },
    deleteQuotationclient(state, id) {
        let url = urlQuotationclient + '/' + state.idQuotationclient
        axios.delete(url).then(response => {
            toastr.success('Cotización formal eliminada con éxito')
            $('#modalDeleteQuotationClient').modal('hide')
            state.idQuotationclient = null
        })
    },
    deleteQuotationShipping(state, id) {
        let url = urlCreateQuotationShipping + '/' + state.idQuotationShipping
        axios.delete(url).then(response => {
            toastr.success('Envio eliminado con éxito')
            $('#modaldeleteQuotationShipping').modal('hide')
            state.idQuotationShipping = null
        })
    },
    pdfQuotationclient(state) {
        let url = urlQuotationclientPdf + '/' + state.idQuotationclient
        window.location.href = url;
    },
    pdfQuotationShipping(state, id) {
        let url = urlQuotationShippingPdf + '/' + id
        window.location.href = url;
    },
    pdfIvaQuotationclient(state) {
        let url = urlQuotationclientPdfIva + '/' + state.idQuotationclient
        window.location.href = url;
    },

    /******************************* */
    /****** sección detalles de cotizaciones de clientes**** */
    /******************************* */

    createDetailclient(state) {
        let url = urlDetailclient

        let priceSet = "" + state.newDetailclient.price
        priceSet = priceSet.split('.').join('')

        let aditionalSet = "" + state.newDetailclient.aditional
        aditionalSet = aditionalSet.split('.').join('')

        let transportSet = "" + state.newDetailclient.transport
        transportSet = transportSet.split('.').join('')

        axios.post(url, {
            quotationclient_id: state.idQuotationclient,
            product_id: resolveSelectedDetailclientProductId(state),
            product: state.newDetailclient.product,
            detail: state.newDetailclient.detail,
            days: state.newDetailclient.days,
            price: priceSet,
            quantity: state.newDetailclient.quantity,
            percentage: state.newDetailclient.percentage,
            aditional: aditionalSet,
            transport: transportSet,
            utility: state.newDetailclient.utility,
            total: state.newDetailclient.total,
        }).then(response => {
            state.selectedProduct = []
            const spareParts = state.newDetailclient.spare_parts
            state.newDetailclient = {
                quotationclient_id: '',
                product: '',
                detail: '',
                price: 0,
                quantity: 1,
                percentage: state.newUtilidad.utilidad,
                aditional: 0,
                transport: state.newFlete.flete,
                utility: 0,
                total: 0,
                days: state.defaultDeliveryTime.label || '24 a 48 Hrs',
                spare_parts: spareParts
            }
            state.errorsLaravel = []
            toastr.success('Detalle generado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    editDetailclient(state, detailclient) {
        state.fillDetailclient.id = detailclient.id
        state.fillDetailclient.quotationclient_id = detailclient.quotationclient_id
        state.fillDetailclient.product = detailclient.product
        state.fillDetailclient.detail = detailclient.detail
        state.fillDetailclient.price = detailclient.price
        state.fillDetailclient.quantity = detailclient.quantity
        state.fillDetailclient.percentage = detailclient.percentage
        state.fillDetailclient.aditional = detailclient.aditional
        state.fillDetailclient.transport = detailclient.transport
        state.fillDetailclient.utility = detailclient.utility
        state.fillDetailclient.total = detailclient.price_neto
        state.fillDetailclient.totalIVA = detailclient.total
        state.fillDetailclient.days = detailclient.days

        $("#editDetailClient").modal('show')
    },
    editDetailclientMechanic(state, detailclient) {
        state.fillDetailclient.id = detailclient.id
        state.fillDetailclient.quotationclient_id = detailclient.quotationclient_id
        state.fillDetailclient.product = detailclient.product
        state.fillDetailclient.detail = detailclient.detail
        state.fillDetailclient.price = detailclient.price
        // state.fillDetailclient.quantity = detailclient.quantity
        // state.fillDetailclient.percentage = detailclient.percentage
        // state.fillDetailclient.aditional = detailclient.aditional
        // state.fillDetailclient.transport = detailclient.transport
        // state.fillDetailclient.utility = detailclient.utility
        state.fillDetailclient.total = detailclient.total
        state.fillDetailclient.totalIVA = Math.round(detailclient.total * 1.19)
        state.fillDetailclient.days = detailclient.days

        $("#editDetailClientMechanic").modal('show')
    },
    updateDetailclient(state, id) {
        let url = urlDetailclient + '/' + id
        //detailclient es similar a fillDetailclient pero sin el totalIva
        //  totalIva es utilizado para mostrarlo en la tabla solamente, mas no para insertarlo en la bd
        let detailclient = {
            id: state.fillDetailclient.id,
            quotationclient_id: state.fillDetailclient.quotationclient_id,
            product: state.fillDetailclient.product,
            detail: state.fillDetailclient.detail,
            price: state.fillDetailclient.price,
            quantity: state.fillDetailclient.quantity,
            percentage: state.fillDetailclient.percentage,
            aditional: state.fillDetailclient.aditional,
            transport: state.fillDetailclient.transport,
            utility: state.fillDetailclient.utility,
            days: state.fillDetailclient.days,
            total: state.fillDetailclient.totalIVA
        }

        axios.put(url, detailclient).then(response => {
            state.fillDetailclient = {
                id: '',
                quotationclient_id: '',
                product: '',
                detail: '',
                price: 1,
                quantity: 1,
                percentage: 35,
                aditional: 0,
                transport: 0,
                utility: 0,
                total: 1,
                totalIVA: 1.19,
                days: '',
                spare_parts: ''
            }
            state.errorsLaravel = []
            $('#editDetailClient').modal('hide')
            toastr.success('Detalle actualizado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    deleteDetailclient(state, id) {
        let url = urlDetailclient + '/' + id
        axios.delete(url).then(response => {
            toastr.success('Detalle eliminado con éxito')
        })
    },
    /******************************* */
    /****** sección importaciones **** */
    /******************************* */
    getImports(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination.per_page || 20)
        let url = urlImport + '?page=' + page + '&per_page=' + perPage
        axios.get(url).then(response => {
            state.imports = response.data.imports.data
            state.pagination = response.data.pagination
        });
    },
    getImportDetails(state) {
        let url = urlImportDetails + '/' + state.idImport
        axios.get(url).then(response => {
            state.detailimports = response.data
            let total = 0
            let price = 0
            let totalValue = 0
            let totalNacional = 0
            let totalInternacional = 0
            let totalNeto = 0
            let totalCosto = 0
            state.detailimports.forEach(detailimport => {
                totalValue += parseFloat(detailimport.total)
                totalNeto += parseFloat(detailimport.price)
                totalNacional += parseFloat(detailimport.nacional)
                totalInternacional += parseFloat(detailimport.internacional)
                totalCosto += parseFloat(detailimport.nacional) + parseFloat(detailimport.internacional)
                total += parseFloat(detailimport.utility)
                price += parseFloat(detailimport.price) * parseInt(detailimport.quantity)
            })
            state.totalPriceImport = price
            state.totalValue = totalValue
            state.totalNacional = totalNacional
            state.totalInternacional = totalInternacional
            state.totalNeto = totalNeto
            state.totalCosto = totalCosto
            state.totalImport = total
            state.totalImportIVA = Math.round(parseFloat(total * 1.19))


        });
    },
    getTotalImport(state) {
        let price = 0
        let total = 0

        state.detailimports.forEach(detailimport => {
            total += parseFloat(detailimport.utility)
            price += parseFloat(detailimport.price) * parseFloat(detailimport.quantity)
        })
        state.totalPriceImport = price
        state.totalImport = total
        state.totalImportIVA = Math.round(parseFloat(total * 1.19))
    },
    createImport(state) {
        let url = urlImport
        axios.post(url, {
            name: state.newImport.name,
        }).then(response => {
            state.newImport = {
                name: '',
                dolar: '',
                safe: '',
                transport: '',
                internment: '',
                other1: '',
                other2: '',
                total: ''
            }
            state.errorsLaravel = []
            $('#btn-import-card').click()
            $('#import').modal('show')
            toastr.success('Importación generada con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    showImport(state) {
        let url = urlImport + '/' + state.idImport
        axios.get(url).then(response => {
            state.import = response.data
            state.detailImport.dolar = state.import.dolar
            state.detailImport.embarque = state.import.embarque
            state.detailImport.fee = state.import.fee
            state.detailImport.fleteUsa = state.import.fleteUsa
            state.detailImport.bankusa = state.import.bankusa
            state.detailImport.bankchile = state.import.bankchile
            state.detailImport.transferencia = state.import.transferencia
            state.detailImport.otro = state.import.otro
            state.detailImportNacional.aduana1 = state.import.aduana1
            state.detailImportNacional.aduana2 = state.import.aduana2
            state.detailImportNacional.manipuleo = state.import.manipuleo
            state.detailImportNacional.bodega = state.import.bodega
            state.detailImportNacional.guia = state.import.guia
            state.detailImportNacional.retiro = state.import.retiro
            state.detailImportNacional.fleteChile = state.import.fleteChile

            if (state.detailImport.dolar == 0)
                state.detailImport.dolar = 700
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    editImport(state, localImport) {
        state.fillImport.id = localImport.id
        state.fillImport.client = localImport.client
        state.fillImport.vehicle = localImport.vehicle
        state.fillImport.patent = localImport.patent
        state.fillImport.state = localImport.state
        //$("#edit").modal('show')
    },
    updateImport(state, id) {
        let url = urlImport + '/' + id
        axios.put(url, state.fillImport).then(response => {
            state.fillImport = {
                id: '',
                name: '',
                dolar: '',
                safe: '',
                transport: '',
                internment: '',
                other1: '',
                other2: '',
                total: ''
            }
            state.errorsLaravel = []
            $('#edit').modal('hide')
            toastr.success('Importación actualizada con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    showModalDeleteImport(state, id) {
        state.idImport = id
        $('#modalDeleteImport').modal('show')
    },
    deleteImport(state) {
        let url = urlImport + '/' + state.idImport
        axios.delete(url).then(response => {
            toastr.success('Importación eliminada con éxito')
            //state.idImport = null
            $('#modalDeleteImport').modal('hide')
        })
    },
    pdfImport(state) {
        let url = urlImportPdf + '/' + state.idImport
        window.location.href = url
    },
    excelImport(state, id) {
        let url = urlExportExcel + '/' + id
        window.location.href = url
    },
    /******************************* */
    /****** sección detalles de importaciones**** */
    /******************************* */
    showModalDetailimport(state, id) {
        state.detailimports = []
        state.idImport = id
        $('#modalImport').modal('show')
    },
    createDetailimport(state) {

        //state.newDetailimport.price = state.newDetailimport.price.replace('.', ',')

        let url = urlDetailimport

        let adicional = parseFloat(state.newDetailimport.aditional) * parseFloat(state.detailImport.dolar)

        // let usa = parseFloat(state.newDetailimport.usa / 100) /* + 1*/

        // let seguro = parseFloat(state.newDetailimport.seguro / 100) /* + 1*/

        let usa = (parseFloat(state.newDetailimport.price) * parseFloat(state.detailImport.dolar)) * parseFloat(state.newDetailimport.usa / 100)

        let seguro = ((parseFloat(state.newDetailimport.price) * parseFloat(state.detailImport.dolar)) + usa) * parseFloat(state.newDetailimport.usa / 100)

        let precio = parseFloat(state.newDetailimport.price) /** parseFloat(state.detailImport.dolar)*/ /* usa * seguro*/
        /* + adicional*/

        let precio_dolar = parseFloat(state.newDetailimport.price) * parseFloat(state.detailImport.dolar)
        //let dolar = parseFloat(state.detailImport.dolar)

        axios.put(urlImport + '/' + state.idImport, {
            dolar: state.detailImport.dolar,
        }).then(response => {
            toastr.success('Importación actualizada')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })

        axios.post(url, {
            import_id: state.idImport,
            product: state.newDetailimport.product,
            detail: state.newDetailimport.detail,
            price: precio,
            price_dolar: precio_dolar,
            quantity: state.newDetailimport.quantity,
            //usa: state.newDetailimport.usa,
            //dolar: dolar,
            usa: usa,
            seguro: seguro,
            valorem: state.newDetailimport.valorem,
            aditional: adicional,
        }).then(response => {

            state.errorsLaravel = []


            let url = urlProductimport

            axios.post(url, {
                name: state.newDetailimport.product,
                detail: state.newDetailimport.detail,
                //type: 0,
            }).then(response => {
                state.selectedProductimport = []

                state.newDetailimport = {
                    import_id: '',
                    product: '',
                    detail: '',
                    price: 1,
                    quantity: 1,
                    usa: 0,
                    seguro: 1,
                    valorem: 0,
                    aditional: 0,
                    embarque: 0,
                    fee: 0,
                    fleteUsa: 0,
                    bankusa: 0,
                    bankchile: 0,
                    transferencia: 0,
                    otro: 0,
                    aduana1: 0,
                    aduana2: 0,
                    manipuleo: 0,
                    bodega: 0,
                    guia: 0,
                    retiro: 0,
                    fleteChile: 0,
                    percentage: 0,
                    internacional: 0,
                    nacional: 0,
                    costoTotal: 0,
                    valueChile: 0,
                    unitario: 0,
                    utility: 0,
                    total: 0
                }

                toastr.success('Producto agregado con éxito')
            }).catch(error => {
                state.errorsLaravel = error.response.data
            })

        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    editDetailimport(state, detailimport) {
        state.fillDetailimport.id = detailimport.id
        state.fillDetailimport.import_id = detailimport.import_id
        state.fillDetailimport.product = detailimport.product
        state.fillDetailimport.detail = detailimport.detail
        state.fillDetailimport.price = detailimport.price
        state.fillDetailimport.quantity = detailimport.quantity
        state.fillDetailimport.valorem = detailimport.valorem
        state.fillDetailimport.aditional = detailimport.aditional

        state.fillDetailimport.embarque = detailimport.embarque
        state.fillDetailimport.seguro = detailimport.seguro
        state.fillDetailimport.fee = detailimport.fee
        state.fillDetailimport.fleteUsa = detailimport.fleteUsa
        state.fillDetailimport.bankusa = detailimport.bankusa
        state.fillDetailimport.bankchile = detailimport.bankchile
        state.fillDetailimport.transferencia = detailimport.transferencia

        state.fillDetailimport.aduana1 = detailimport.aduana1
        state.fillDetailimport.aduana2 = detailimport.aduana2
        state.fillDetailimport.manipuleo = detailimport.manipuleo
        state.fillDetailimport.bodega = detailimport.bodega
        state.fillDetailimport.guia = detailimport.guia
        state.fillDetailimport.retiro = detailimport.retiro
        state.fillDetailimport.fleteChile = detailimport.fleteChile

        state.fillDetailimport.percentage = detailimport.percentage
        state.fillDetailimport.internacional = detailimport.internacional
        state.fillDetailimport.nacional = detailimport.nacional
        state.fillDetailimport.costoTotal = detailimport.costoTotal
        state.fillDetailimport.valueChile = detailimport.valueChile
        state.fillDetailimport.utility = detailimport.utility
        state.fillDetailimport.total = detailimport.total

        $("#editDetailImport").modal('show')
    },
    updateDetailimport(state, id) {
        let url = urlDetailimport + '/' + id
        axios.put(url, state.fillDetailimport).then(response => {
            state.fillDetailimport = {
                id: '',
                import_id: '',
                product: '',
                detail: '',
                price: 1,
                quantity: 1,
                usa: 0,
                seguro: 1,
                valorem: 0,
                aditional: 0,
                embarque: 0,
                fee: 0,
                fleteUsa: 0,
                bankusa: 0,
                bankchile: 0,
                transferencia: 0,
                otro: 0,
                aduana1: 0,
                aduana2: 0,
                manipuleo: 0,
                bodega: 0,
                guia: 0,
                retiro: 0,
                fleteChile: 0,
                percentage: 0,
                internacional: 0,
                nacional: 0,
                costoTotal: 0,
                valueChile: 0,
                unitario: 0,
                utility: 0,
                total: 0
            },
                state.errorsLaravel = []
            $('#editDetailImport').modal('hide')
            toastr.success('Detalle actualizado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    deleteDetailimport(state, id) {
        let url = urlDetailimport + '/' + id
        axios.delete(url).then(response => {
            toastr.success('Detalle eliminado con éxito')
        })
    },
    finishDetailimport(state) {

        let idImport = state.idImport
        state.detailimports.forEach(localImport => {
            let url = urlDetailimport + '/' + localImport.id
            idImport = localImport.import_id
            axios.put(url, localImport).then(response => {
                //toastr.success('Detalle actualizado con éxito')
            }).catch(error => {
                state.errorsLaravel = error.response.data
            })
        })

        let url = urlImport + '/' + idImport
        axios.put(url, {
            dolar: state.detailImport.dolar,
            embarque: state.detailImport.embarque,
            fee: state.detailImport.fee,
            fleteUsa: state.detailImport.fleteUsa,
            bankusa: state.detailImport.bankusa,
            bankchile: state.detailImport.bankchile,
            transferencia: state.detailImport.transferencia,
            otro: state.detailImport.otro,
            aduana1: state.detailImportNacional.aduana1,
            aduana2: state.detailImportNacional.aduana2,
            manipuleo: state.detailImportNacional.manipuleo,
            bodega: state.detailImportNacional.bodega,
            guia: state.detailImportNacional.guia,
            retiro: state.detailImportNacional.retiro,
            fleteChile: state.detailImportNacional.fleteChile,
        }).then(response => {
            toastr.success('Importación actualizada con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })

    },
    /******************************* */
    /****** sección cotización de importaciones**** */
    /******************************* */
    showQuotationimport(state, id) {
        state.detailimports = []
        state.idImport = id
        $('#modalQuotationImport').modal('show')
    },
    createQuotationimport(state) {
        let url = urlQuotationimport
        axios.post(url, {
            import_id: state.idImport,
            client_id: state.selectedClient.value,
            payment: state.newQuotationimport.payment,
            state: 'Pendiente',
        }).then(response => {
            state.newQuotationimport = {
                import_id: '',
                user_id: '',
                client_id: '',
                payment: '',
                state: ''
            }
            state.errorsLaravel = []
            $('#btn-quotationimport-card').click()
            $('#modalQuotationImport').modal('hide')
            toastr.success('Cotización generada con éxito')
            let url = urlQuotationimportPdf + '/' + response.data
            window.location.href = url
            //state.idImport = null
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    /******************************* */
    /****** sección productos **** */
    /******************************* */

    updateProductsUtilidad(state) {
        let url = urlProduct
        //state.newAllUtilidad.select = state.checkedSelect2
        axios.post(url, {
            check: state.checkedSelect2,
            pago: state.selectedPago.label,
            utilidad: state.selectedPago.utilidad
        }).then(response => {
            state.newAllUtilidad = {
                check: [],
                pago: '',
                utilidad: ''
            }
            state.errorsLaravel = [];
            toastr.success('Se actualizo la forma de pago')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    updateRole(state, data) {
        let url = urlRoles + '/' + data.id

        state.fillRole.special = state.checkedSpecialRole
        state.fillRole.permissions = state.checkedPermissions

        axios.put(url, state.fillRole).then(response => {
            state.fillRole = {
                id: '',
                name: '',
                description: ''
            }
            state.errorsLaravel = [];
            $('#edit').modal('hide')
            toastr.success('Rol actualizado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    createDescuento(state) {
        let url = urlDescuento
        axios.post(url, {
            descuento: parseInt(state.newDescuento.descuento)
        }).then(response => {
            state.newDescuento = {
                descuento: 0
            }
            state.errorsLaravel = []
            toastr.success('El Descuento se creo correctamente')
            $('#createDescuento').modal('hide')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    descuento(state, detailimport) {
        state.fillDetailimport.id = detailimport.id
    },

    createTipoPago(state) {
        let url = urlTipoDePago
        axios.post(url, {
            pago: state.newTipoPago.pago,
            utilidad: state.newTipoPago.utilidad
        }).then(response => {
            state.newTipoPago = {
                pago: '',
                utilidad: ''
            }
            state.errorsLaravel = []
            toastr.success('El tipo de pago se creo correctamente')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    getTiposPagos(state) {
        let url = urlTipoDePago
        axios.get(url).then(response => {
            state.tipospagos = response.data
        });
    },

    editTiposPagos(state, tipospagos) {
        state.fillTipoPago.id = tipospagos.id
        state.fillTipoPago.pago = tipospagos.pago
        state.fillTipoPago.utilidad = tipospagos.utilidad
        $("#edit").modal('show')
    },

    updateTiposPagos(state, id) {
        let url = urlUtilidad + '/' + id
        axios.put(url, state.fillTipoPago).then(response => {
            state.fillTipoPago = {
                id: '',
                pago: '',
                utilidad: ''
            }
            state.errorsLaravel = []
            $('#edit').modal('hide')
            toastr.success('El Forma de Pago se a actualizado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    updateUtilidad(state, id) {
        let url = urlTipoDePago + '/' + id
        axios.put(url, {
            id: state.fillTipoPago.id,
            pago: state.selectedPago.label,
            utilidad: state.selectedPago.utilidad
        }).then(response => {
            state.fillTipoPago = {
                id: '',
                pago: '',
                utilidad: ''
            }
            state.errorsLaravel = []
            $('#editUtilidad').modal('hide')
            toastr.success('El producto se a actualizado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },




    getProducts(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination.per_page || 20)
        let url = urlProduct + '?page=' + page + '&name=' + state.searchProduct.name + '&per_page=' + perPage
        axios.get(url).then(response => {
            state.products = response.data.products.data
            state.pagination = response.data.pagination
        });
    },
    getProductVehicleModelOptions(state) {
        axios.get(urlVehicleModelCatalog).then(response => {
            state.productVehicleModelOptions = response.data.map((model) => ({
                id: model.id,
                brand: model.brand,
                model: model.model
            }))
        })
    },

    createProduct(state) {
        let url = urlProduct
        axios.post(url, {
            name: state.newProduct.name,
            detail: state.newProduct.detail,
            client_id: state.selectedClient.value,
            codebar: state.newProduct.codebar
        }).then(response => {
            state.newProduct = {
                name: '',
                detail: '',
                codebar: ''
            }
            state.errorsLaravel = []
            $('#create').modal('hide')
            $('#btn-product-card').click()

            toastr.success('Producto generado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    editProduct(state, data) {
        state.fillProduct.id = data.product.id
        state.fillProduct.codebar = data.product.codebar
        state.fillProduct.name = data.product.name
        state.fillProduct.utilidad = data.product.utilidad
        state.fillProduct.flete = data.product.flete
        state.fillProduct.detail = data.product.detail
        state.current_page = data.page
    },
    openProductVehicleModels(state, product) {
        state.productVehicleModelModal = {
            productId: product.id,
            productName: product.name
        }
        state.productVehicleModelSearch = ''
        state.selectedProductVehicleModelIds = []
    },
    getProductVehicleModelRelations(state, productId) {
        axios.get(urlProduct + '/' + productId + '/vehicle-model-relations').then(response => {
            state.selectedProductVehicleModelIds = (response.data.vehicle_model_ids || []).map((id) => parseInt(id, 10))
        })
    },
    toggleProductVehicleModel(state, modelId) {
        const normalizedId = parseInt(modelId, 10)
        const index = state.selectedProductVehicleModelIds.indexOf(normalizedId)

        if (index >= 0) {
            state.selectedProductVehicleModelIds.splice(index, 1)
            return
        }

        state.selectedProductVehicleModelIds.push(normalizedId)
    },
    clearProductVehicleModels(state) {
        state.selectedProductVehicleModelIds = []
    },
    selectVisibleProductVehicleModels(state) {
        const term = (state.productVehicleModelSearch || '').trim().toLowerCase()
        const visibleIds = state.productVehicleModelOptions
            .filter((model) => {
                if (term === '') {
                    return true
                }

                return `${model.brand} ${model.model}`.toLowerCase().includes(term)
            })
            .map((model) => model.id)

        state.selectedProductVehicleModelIds = Array.from(new Set([
            ...state.selectedProductVehicleModelIds,
            ...visibleIds
        ])).sort((left, right) => left - right)
    },
    saveProductVehicleModels(state) {
        if (!state.productVehicleModelModal.productId) {
            return
        }

        axios.put(
            urlProduct + '/' + state.productVehicleModelModal.productId + '/vehicle-model-relations',
            { vehicle_model_ids: state.selectedProductVehicleModelIds }
        ).then(response => {
            const productIndex = state.products.findIndex((product) => product.id === response.data.product_id)

            if (productIndex >= 0) {
                state.products[productIndex].related_vehicle_models_count = response.data.related_vehicle_models_count
            }

            state.errorsLaravel = []
            $('#product_vehicle_models').modal('hide')
            toastr.success('Modelos relacionados guardados con exito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    updateProduct(state, id) {
        let url = urlProduct + '/' + id
        axios.put(url, state.fillProduct).then(response => {
            state.fillProduct = { id: 0, name: '', codebar: '', client_id: '', detail: '', atributo: 0, utilidad: 0, flete: 0, folio: 0 }
            state.errorsLaravel = []
            $('#edit_product').modal('hide')
            toastr.success('Producto actualizado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    modalDeleteProduct(state, id) {
        state.deleteProductId = id
    },
    deleteProduct(state, id) {
        let url = urlProduct + '/' + id
        axios.delete(url).then(response => {
            if (response.data) {
                state.products = state.products.filter(product => product.id !== response.data.id)
                $('#delete_product').modal('hide')
                toastr.success('Producto eliminado con éxito')
            }
        })
    },
    /******************************* */
    /****** sección productos de importacion **** */
    /******************************* */
    getProductimports(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination.per_page || 20)
        let url = urlProductimport + '?page=' + page + '&per_page=' + perPage
        axios.get(url).then(response => {
            state.products = response.data.products.data
            state.pagination = response.data.pagination
        });
    },

    updateUtilidadDefect(state) {
        let url = urlUtilidad
        axios.post(url, state.newUtilidad).then(response => {
            if (response.status === 200 && response.data > 0) {
                state.newUtilidad.utilidad = response.data
                state.errorsLaravel = []
                toastr.success('La utilidad se actualizo éxito')
            }
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    getUtilities(state) {
        let url = urlUtilidad
        axios.get(url).then(response => {
            state.newUtilidad.utilidad = response.data.utilidad
            state.newDetailclient.percentage = response.data.utilidad
        });
    },
    /******************************* */
    /****** sección inventariado **** */
    /******************************* */
    getInventories(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination.per_page || 20)
        let url = urlInventory + '?page=' + page + '&name=' + state.searchInventory.name + '&per_page=' + perPage
        axios.get(url).then(response => {
            state.inventories = response.data.inventories.data
            state.pagination = response.data.pagination
        });
    },
    createInventory(state) {
        let url = urlInventory
        axios.post(url, state.newInventory).then(response => {
            if (response.data) {
                state.inventories.push(response.data)
                state.newInventory = { product_id: state.newInventory.product_id, quantity: 1, price: 0, discount: 0 }
                state.errorsLaravel = []
                toastr.success('Inventario actualizado con éxito')
            }
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    editInventory(state, data) {
        state.newInventory.product_id = data.id
        state.inventories = data.inventories
    },
    deleteInventory(state, id) {
        let url = urlInventory + '/' + id
        axios.delete(url).then(response => {
            if (response.data) {
                state.inventories = state.inventories.filter(inventory => inventory.id !== response.data.id)
                toastr.success('Inventario eliminado con éxito')
            }
        })
    },
    async uploadInvoice(state) {
        let formData = new FormData()
        let url = urlBill
        formData.append('file', state.fileInvoice)
        formData.append('utility', state.newUtilidad.utilidad)
        formData.append('flete', state.newFlete.flete)

        try {
            const response = await axios.post(url, formData, { headers: { 'Content-Type': 'multipart/form-data' } });
            if (response.data.message === "Factura ingresada correctamente") {
                $('#upload_invoice').modal('hide')
                toastr.success('Factura ingresada con éxito!')
                // Ejecutar la mutación getInventories
                this.commit('getInventories', 1);
            }
        } catch (error) {
            $('#upload_invoice').modal('hide')
            toastr.error("Error subiendo la factura, quizás ya la ingreso previamente o no se pudo leer el formato")
            throw error; // Rechazar la promesa si hay un error
        }
    },
    /******************************* */
    /****** sección usuarios **** */
    /******************************* */
    getUsers(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination.per_page || 20)
        let url = urlUser + '?page=' + page + '&per_page=' + perPage
        axios.get(url).then(response => {
            state.users = response.data.users.data
            state.pagination = response.data.pagination
        });
    },

    getRolesQuotation(state) {
        let url = urlQuotationRoles
        axios.get(url).then(response => {
            state.quotationRoles = response.data
        });
    },


    getCompany(state) {
        let url = urlCompany
        axios.get(url).then(response => {
            if (response.data !== '') {
                state.newCompany.rut = response.data.rut
                state.newCompany.razonSocial = response.data.razonSocial
                state.newCompany.email = response.data.email
                state.newCompany.phone = response.data.phone
                state.newCompany.address = response.data.address
                state.newCompany.comuna = response.data.comuna
                state.newCompany.giro = response.data.giro
                state.newCompany.logo = response.data.url
                state.newCompany.id = response.data.id
            }

        })
    },
    getUser(state) {
        let url = urlUserId
        axios.get(url).then(response => {
            state.fillUser.id = response.data.id
            state.fillUser.name = response.data.name
            state.fillUser.email = response.data.email
            state.newCompany.user_id = response.data.id
        })
    },
    createUser(state) {
        let url = urlUser
        axios.post(url, {
            id: state.idUser,
            name: state.newUser.name,
            email: state.newUser.email,
            password: state.newUser.password,
            //cant_client: state.newUser.cant_client,
            cant_vehicle: state.newUser.cant_vehicle
            //url: window.location.host + "/acceso/" + md5(state.newUser.password)
        }).then(response => {
            state.newUser = {
                id: '',
                name: '',
                email: '',
                password: '',
                url: ''
            }
            state.errorsLaravel = []
            toastr.success('Usuario generado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    editUser(state, user) {
        state.fillUser.id = user.id
        state.fillUser.name = user.name
        state.fillUser.email = user.email
        state.fillUser.password = user.password
        state.fillUser.url = window.location.host + "/acceso/" + user.url
        state.fillUser.logo = user.logo
        state.fillUser.cantidad = user.cantidad
        $("#edit").modal('show')
    },

    uploadLogo(state, evt) {
        state.form = new FormData()

        state.images = []
        state.attachment = []
        let selectedFiles = evt.target.files

        if (!selectedFiles.length) {
            return false
        }

        for (let i = 0; i < selectedFiles.length; i++) {
            state.attachment.push(selectedFiles[i])
        }
    },

    updateCompanyLogo(state) {
        let url = urlCompanyLogo

        const config = {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        }

        for (let i = 0; i < state.attachment.length; i++) {
            state.form.append('logo[]', state.attachment[i])
        }

        axios.post(url, state.form, config).then(response => {
            state.attachment.length = []
            state.errorsLaravel = []
            $("#logo").val(null)
            toastr.success('logo actualizado con éxito')
            this.commit('getCompany')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    updateUser(state, id) {
        let url = urlUser + '/' + id
        axios.put(url, state.fillUser).then(response => {
            state.fillUser = {
                id: '',
                name: '',
                email: '',
                password: '',
                logo: ''
            }
            state.errorsLaravel = []
            $('#edit').modal('hide')
            toastr.success('Usuario actualizado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    updateCantCliVehi(state, id) {
        let url = urlCantCliVehi + '/' + id
        axios.put(url, state.fillCantCliVehi).then(response => {
            state.fillCantCliVehi = {
                id: '',
                cant_client: 0,
                cant_vehicle: 0
            }
            state.errorsLaravel = []
            $('#editCantCliVehi').modal('hide')
            toastr.success('Se han actualizado las cantidades con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    updateCantVehicle(state, id) {
        let url = urlCantVehicle + '/' + id
        axios.put(url, state.fillCantVehicle).then(response => {
            state.fillCantVehicle = {
                id: '',
                cant_vehicle: 0
            }
            state.errorsLaravel = []
            $('#editCantVehicle').modal('hide')
            toastr.success('La cantidad se actualizado con éxito')
        }).catch(error => {
            toastr.error(error.response.data)
        })
    },
    modalDeleteUser(state, id) {
        state.fillUser.id = id
        $('#deleteUser').modal('show')
    },
    deleteUser(state) {
        let url = urlUser + '/' + state.fillUser.id
        axios.delete(url).then(response => {
            toastr.success('usuario eliminada con éxito')
            $('#deleteUser').modal('hide')
            this.commit('getUsers', 1)
        })
    },
    /******************************* */
    /****** sección empresas **** */
    /******************************* */
    updateCompany(state, id) {
        let url = urlCompany + '/' + id
        axios.put(url, state.newCompany).then(response => {
            state.errorsLaravel = []
            toastr.success('Empresa actualizado con éxito')
            this.commit('getCompany')
        }).catch(error => {
            toastr.error(error.response.data.error)
        })
    },

    createCompany(state) {
        let url = urlCompany
        axios.post(url, state.newCompany).then(response => {
            state.errorsLaravel = []
            toastr.success('Empresa se creo con éxito')
            this.commit('getCompany')
        }).catch(error => {
            toastr.error(error.response.data.error)
        })
    },

    /**************************** */
    /******************************* */
    /****** sección de control de roles **** */
    /******************************* */
    getRoles(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination.per_page || 20)
        let url = urlRoles + '?page=' + page + '&per_page=' + perPage
        axios.get(url).then(response => {
            state.roles = response.data.roles.data
            state.pagination = response.data.pagination
        });
    },
    createRole(state) {
        let url = urlRoles
        axios.post(url, {
            name: state.newRole.name,
            description: state.newRole.description,
        }).then(response => {
            state.newRole.name = ''
            state.newRole.description = ''
            state.errorsLaravel = []
            $('#create').modal('hide')
            toastr.success('Rol generado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    editRole(state, role) {
        state.checkedSpecialRole = ''
        state.checkedPermissions = []
        state.fillRole.id = role.id
        state.fillRole.name = role.name
        state.fillRole.description = role.description
        state.checkedSpecialRole = role.special

        role.permissions.forEach(permission => {
            state.checkedPermissions.push(permission.id)
        })


        $("#edit").modal('show')
    },
    deleteRole(state, id) {
        let url = urlRoles + '/' + id
        axios.delete(url).then(response => {
            toastr.success('Rol eliminado con éxito');
        })
    },
    getAllRoles(state) {
        let url = urlAllRoles
        axios.get(url).then(response => {
            state.roles = response.data
        });
    },

    editCantCliVehi(state, user) {

        if (user.roles[0] === undefined) {
            toastr.error('Debe asignar un rol a este usuario')
        } else {
            state.fillCantCliVehi.id = user.id
            state.fillCantCliVehi.cant_client = user.cant_client
            state.fillCantCliVehi.cant_vehicle = user.cant_vehicle
            state.fillCantCliVehi.rol = user.roles[0].name
            $("#editCantCliVehi").modal('show')
        }
    },
    editCantVehicle(state, user) {
        state.fillCantVehicle.id = user.id
        state.fillCantVehicle.cant_vehicle = user.cant_vehicle

        let url = urlTotalVehi + '/' + user.id
        axios.get(url).then(response => {
            state.totalvehi = response.data
        });

        $("#editCantVehicle").modal('show')
    },
    editarUtilidad(state, user) {
        state.fillTipoPago.id = user.id
        if (user.productpagos != null) {
            state.selectedPago.label = user.productpagos.forma_pago
        } else {
            state.selectedPago.label = ""
        }

        $("#editUtilidad").modal('show')
    },
    editUserRoles(state, user) {
        //let roles = [user.role_id]
        let roles = []
        state.checkedRoles = []
        state.fillUserRoles.id = user.id
        state.fillUserRoles.name = user.name
        user.roles.forEach(role => {
            roles.push(role.id)
        })
        state.checkedRoles = roles
        $("#editRoles").modal('show')
    },
    updateUserRoles(state, id) {
        let url = urlUserRoles + '/' + id;
        axios.put(url, state.checkedRoles).then(response => {
            state.checkedRoles = []
            $('#editRoles').modal('hide')
            toastr.success('Roles asignados con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    updateQuotationShipping(state, id) {
        let url = urlCreateQuotationShipping + '/' + id
        axios.put(url, state.fillQuotationShipping).then(response => {
            state.fillQuotationShipping = {
                direccion: ''
            }
            state.errorsLaravel = [];
            $('#modalQuotationShipping').modal('hide')
            toastr.success('Se agrego la dirección correctamente')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    updateFacebookShipping(state, id) {
        let url = urlCreateFacebookShipping + '/' + id
        axios.put(url, state.fillFacebookShipping).then(response => {
            state.fillFacebookShipping = {
                url: ''
            }
            state.errorsLaravel = [];
            $('#modalEditFacebook').modal('hide')
            toastr.success('Se agrego la url correctamente')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    getAllPermissions(state) {
        let url = urlAllPermissions
        axios.get(url).then(response => {
            state.permissions = response.data
        });
    },
    setRoles(state, arr) {
        state.checkedRoles = arr
    },
    setCheckedSelect(state, value) {
        state.checkedSelect2 = [];
        if (value) {
            state.products.forEach(function (product) {
                state.checkedSelect2.push(product.id);
            });
        }

        state.checkedSelect2 = state.checkedSelect2;
    },
    setCheckedSelect2(state, value) {
        state.checkedSelect2 = value;
    },
    setSpecialRole(state, value) {
        if (value === 'no-access') {
            state.checkedPermissions = []
        } else if (value === 'all-access') {
            state.checkedPermissions = []
            state.permissions.forEach(permission => {
                state.checkedPermissions.push(permission.id)
            })
        } else {
            state.checkedPermissions = state.checkedPermissions
        }
        state.checkedSpecialRole = value
    },
    setPermissions(state, arr) {
        state.checkedSpecialRole = ''
        state.checkedPermissions = arr
    },
    /****** sección select **** */
    /******************************* */
    allPagos(state) {
        let url = urlAllPago
        axios.get(url).then(response => {
            state.optionsPago = []
            response.data.forEach((pago) => {
                state.optionsPago.push({
                    label: pago.pago,
                    value: pago.id,
                    utilidad: pago.utilidad
                })
            });
        });
    },
    setPagos(state, pago) {
        state.selectedPago = pago
    },

    descuentoDefect(state) {
        let url = urlDescuentoDefect
        axios.get(url).then(response => {
            response.data.forEach((descuento) => {
                state.newDescuento.descuento = descuento.descuento
            });
        });
    },

    allUsers(state) {
        let url = urlAllUser
        axios.get(url).then(response => {
            state.optionsUser = []
            response.data.forEach((user) => {
                state.optionsUser.push({
                    label: user.name,
                    value: user.id
                })
            });
        });
    },
    setUser(state, user) {
        state.selectedUser = user
    },
    allClients(state, type) {
        let url = urlAllClient + '?type=' + type
        axios.get(url).then(response => {
            state.optionsClient = []
            response.data.forEach((client) => {
                state.optionsClient.push({
                    label: client.razonSocial,
                    value: client.id
                })
            });
        });
    },
    setClient(state, client) {
        state.selectedClient = client
        state.newQuotationclient.client_text = state.selectedClient.label
    },
    allVehicleBrands(state) {
        let url = urlSelectVehiculoMarcas
        axios.get(url).then(response => {
            state.optionsVehicleBrand = []
            response.data.forEach((vehiclebrand) => {
                state.optionsVehicleBrand.push({
                    label: vehiclebrand.brand,
                    value: vehiclebrand.id
                })
            });
        });
    },
    setVehicleBrand(state, vehiclebrand) {
        state.selectedVehicleBrand = vehiclebrand
    },
    allVehicleModels(state) {
        let url = urlAllVehicleModel + '?brand_id=' + state.selectedVehicleBrand.value
        axios.get(url).then(response => {
            state.optionsVehicleModel = []
            if (response.data != null) {
                response.data.forEach((vehiclemodel) => {
                    state.optionsVehicleModel.push({
                        label: vehiclemodel.model,
                        value: vehiclemodel.id
                    })
                });
            }
        });
    },
    setVehicleModel(state, vehiclemodel) {
        state.selectedVehicleModel = vehiclemodel
    },

    allTiposVehiculos(state) {
        let url = urlSelectVehiculoTipo
        axios.get(url).then(response => {
            state.optionsTiposVehiculo = []
            response.data.forEach((vehiculotipo) => {
                state.optionsTiposVehiculo.push({
                    label: vehiculotipo.tipo_vehiculo,
                    value: vehiculotipo.id
                })
            });
        });
    },

    setVehiculoTipo(state, vehiculotipo) {
        state.selectedVehiculoTipo = vehiculotipo
    },


    allVehicleMotors(state) {
        let url = urlSelectVehiculoMotor
        axios.get(url).then(response => {
            state.optionsMotores = []
            response.data.forEach((vehiclemotor) => {
                state.optionsMotores.push({
                    label: vehiclemotor.v_engine,
                    value: vehiclemotor.id
                })
            });
        });
    },

    setVehicleMotor(state, vehiclemotor) {
        state.selectedVehicleMotor = vehiclemotor
    },

    allVehicleYears(state) {
        let url = urlSelectVehiculoYear
        axios.get(url).then(response => {
            state.optionsYear = []
            response.data.forEach((vehicleyear) => {
                state.optionsYear.push({
                    label: vehicleyear.v_year,
                    value: vehicleyear.id
                })
            });
        });
    },

    setVehicleYear(state, vehicleyear) {
        state.selectedVehicleYear = vehicleyear
    },
    /****************SELECT RELACIONADOS ****************************************/
    allVBR(state) {
        let url = urlVBR
        axios.get(url).then(response => {
            state.optionsVBR = []
            response.data.forEach((vbr) => {
                state.optionsVBR.push({
                    label: vbr.brand,
                    value: vbr.id
                })
            });
        });
    },
    setVBR(state, brand) {
        state.selectedVBR = brand
    },
    allVMR(state) {
        if (state.selectedVBR.label != '') {
            let url = urlVMR + '/' + state.selectedVBR.value
            axios.get(url).then(response => {
                state.optionsVMR = []
                if (response.data != null) {
                    response.data.forEach((vmr) => {
                        state.optionsVMR.push({
                            label: vmr.model,
                            value: vmr.id
                        })
                    });
                }
            }).catch(error => {

            })
        }
    },
    setVMR(state, model) {
        state.selectedVMR = model
    },

    allMM(state) {
        let url = urlMM
        axios.get(url).then(response => {
            state.optionsMM = []
            response.data.forEach((mm) => {
                state.optionsMM.push({
                    label: mm.model,
                    value: mm.id
                })
            });
        });
    },
    setMM(state, model) {
        state.selectedMM = model
    },
    allYM(state) {
        if (state.selectedMM.label != '') {
            let url = urlYM + '/' + state.selectedMM.value
            axios.get(url).then(response => {
                state.optionsYM = []
                if (response.data != null) {
                    response.data.forEach((ym) => {
                        state.optionsYM.push({
                            label: ym.v_year,
                            value: ym.id
                        })
                    });
                }
            }).catch(error => {

            })
        }
    },
    setYM(state, v_year) {
        state.selectedYM = v_year
    },




    /****************formulario de cotizacion ****************************************/

    allCiudad(state) {
        let url = urlCiudad
        axios.get(url).then(response => {
            state.optionsCiudad = []
            response.data.forEach((ciudad) => {
                state.optionsCiudad.push({
                    label: ciudad.nombre,
                    value: ciudad.id
                })
            });
        });
    },
    setCiudad(state, ciudad) {
        state.selectedCiudad = ciudad
    },

    allVBrands(state) {
        let url = urlVBrand
        axios.get(url).then(response => {
            state.optionsVBrand = []
            response.data.forEach((vbrand) => {
                state.optionsVBrand.push({
                    label: vbrand.brand,
                    value: vbrand.id
                })
            });
        });
    },
    setVBrand(state, brand) {
        state.selectedVBrand = brand
    },
    allVModels(state) {
        if (state.selectedVBrand.label != '') {
            let url = urlVModel + '/' + state.selectedVBrand.value
            axios.get(url).then(response => {
                state.optionsVModel = []
                if (response.data != null) {
                    response.data.forEach((vmodel) => {
                        state.optionsVModel.push({
                            label: vmodel.model,
                            value: vmodel.id
                        })
                    });
                }
            }).catch(error => {

            })
        }
    },
    setVModel(state, model) {
        state.selectedVModel = model
    },
    allVYears(state) {
        if (state.selectedVModel.label != '') {
            let url = urlVYear + '/' + state.selectedVModel.value
            axios.get(url).then(response => {
                state.optionsVYear = []
                if (response.data != null) {
                    response.data.forEach((vyear) => {
                        state.optionsVYear.push({
                            label: vyear.year,
                            value: vyear.id
                        })
                    });
                }
            }).catch(error => {

            })
        }
    },
    setVYear(state, year) {
        state.selectedVYear = year
    },
    allVEngines(state) {
        if (state.selectedVYear.label != '') {
            let url = urlVEngine + '/' + state.selectedVYear.value
            axios.get(url).then(response => {
                state.optionsVEngine = []
                if (response.data != null) {
                    response.data.forEach((vengine) => {
                        state.optionsVEngine.push({
                            label: vengine.engine,
                            value: vengine.id
                        })
                    });
                }
            })
        }
    },
    setVEngine(state, engine) {
        state.selectedVEngine = engine
    },
    createQuotationUser(state) {
        let url = urlCreateQuotationUser
        axios.post(url, {
            name: state.formCotizacion.name,
            email: state.formCotizacion.email,
            phone: state.formCotizacion.phone,
            patentchasis: state.formCotizacion.patentchasis.toUpperCase(),
            brand: state.selectedVBrand.label,
            model: state.selectedVModel.label,
            year: state.selectedVYear.label,
            engine: state.selectedVEngine.label,
            description: state.formCotizacion.description
        }).then(response => {
            state.formCotizacion = {
                name: '',
                email: '',
                phone: '',
                patentchasis: '',
                brand: '',
                model: '',
                year: '',
                engine: '',
                description: ''
            },
                state.errorsLaravel = []
            alert('Solicitud ingresada con éxito')
            return true
        }).catch(error => {
            state.errorsLaravel = []
            if (error.response.status === 422) {
                if (error.response.data.errors) {
                    for (let key in error.response.data.errors) {
                        state.errorsLaravel.push({
                            field: key,
                            msg: String(error.response.data.errors[key])
                        })
                    }
                }
            }
            return false
        })

    },

    createQuotationUserExpress(state) {
        let url = urlCreateQuotationUserExpress
        axios.post(url, {
            patentchasis: state.formCotizacionExpress.patentchasis.toUpperCase(),
            brand: state.selectedVBrand.label,
            model: state.selectedVModel.label,
            year: state.selectedVYear.label,
            engine: state.selectedVEngine.label,
            description: state.formCotizacionExpress.description
        }).then(response => {
            state.formCotizacionExpress = {
                patentchasis: '',
                brand: '',
                model: '',
                year: '',
                engine: '',
                description: ''
            },
                state.errorsLaravel = []
            toastr.success('Solicitud ingresada con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },


    createQuotationEnvio(state) {
        let url = urlCreateQuotationShipping
        let cadena = window.location.href
        let id = cadena.split("/")



        axios.post(url, {
            id: id[4],
            nombre: state.formQuotationShipping.nombre,
            rut: state.formQuotationShipping.rut,
            telefono: state.formQuotationShipping.telefono,
            ciudad: state.selectedCiudad.value,
            // direccion: state.formQuotationShipping.direccion,
            sucursal: state.formQuotationShipping.sucursal
        }).then(response => {
            state.formQuotationShipping = {
                id: '',
                nombre: '',
                rut: '',
                telefono: '',
                ciudad: '',
                // direccion: '',
                sucursal: ''
            }
            state.errorsLaravel = []
            window.location.href = 'enviado/' + response.data.numero_envio
        }).catch(error => {
            state.errorsLaravel = []
            if (error.response.status === 422) {
                if (error.response.data.errors) {
                    for (let key in error.response.data.errors) {
                        state.errorsLaravel.push({
                            field: key,
                            msg: String(error.response.data.errors[key])
                        })
                    }
                }
            }
            return false
        })

    },

    getPendingQuotations(state, request) {
        const { page, perPage } = resolvePaginationRequest(request, state.pagination.per_page || 20)
        let day = state.searchQuotationClient.day
        let month = state.searchQuotationClient.month
        let year = state.searchQuotationClient.year

        let url = urlPendingQuotations + '?page=' + page + '&id=' + state.searchQuotationClient.id + '&client=' + state.searchQuotationClient.client_text + '&day=' + day + '&month=' + month + '&year=' + year + '&per_page=' + perPage

        axios.get(url).then(response => {
            state.pendingQuotations = response.data.quotations.data
            state.pagination = response.data.pagination
        });
    },
    /******************************************************************************** */


    allProductsSale(state) {
        let url = urlAllProductSale;
        axios.get(url)
            .then(response => {
                state.optionsProductSale = [];
                if (response && response.data && Array.isArray(response.data)) {
                    response.data.forEach((productsale) => {
                        const inventoriesWithStock = productsale.inventories.filter(inventory => inventory.quantity > 0);
                        if (inventoriesWithStock.length > 0) {
                            state.optionsProductSale.push({
                                label: productsale.name,
                                value: productsale.id,
                                inventories: inventoriesWithStock,
                                utilidad: productsale.utilidad,
                                flete: productsale.flete,
                                codebar: productsale.codebar,
                            });
                        }
                    });
                }
            })
            .catch(error => {
                console.error("Error fetching data:", error);
            });
    },

    setProductSale(state, productsale) {
        state.selectedProductSale = productsale
        state.newSale.totalSumPrice = 0;
        state.newSale.totalSumQuantity = 0;
        state.newSale.average_price = 0;

        if (state.selectedProductSale != null) {
            state.newSale.priceMax = Math.max(...productsale.inventories.map(inventory => inventory.price));
            state.newSale.utilidad = parseFloat((productsale.utilidad / 100) + 1)
            state.newSale.flete = productsale.flete
            state.newSale.product = productsale.label
            state.newSale.codebar = productsale.codebar
            state.newSale.id = productsale.value

            productsale.inventories.forEach((inventory) => {
                state.newSale.totalSumPrice += inventory.price * inventory.quantity;
                state.newSale.totalSumQuantity += inventory.quantity;
                state.newSale.price = inventory.price
            })

            state.newSale.totalNeto = Math.round(((state.newSale.priceMax * parseInt(state.newSale.quantity)) * (state.newSale.utilidad - 1)) + (state.newSale.priceMax * state.newSale.quantity))

            state.newSale.average_price = ((((state.newSale.totalSumPrice / state.newSale.totalSumQuantity) * 1.19) * state.newSale.utilidad) + state.newSale.flete)
            const high_price = (((state.newSale.priceMax * 1.19) * state.newSale.utilidad) + state.newSale.flete)
            const average_price_rounded = Math.ceil(state.newSale.average_price / 10) * 10;
            const high_price_rounded = Math.ceil(high_price / 10) * 10;

            if (high_price_rounded !== average_price_rounded) {
                state.newSale.price_sale = high_price_rounded * state.newSale.quantity
            } else {
                state.newSale.price_sale = average_price_rounded * state.newSale.quantity
            }
        }
    },

    sumTotalProductSale(state) {
        state.newSale.totalNeto = Math.round(((state.newSale.priceMax * parseInt(state.newSale.quantity)) * (state.newSale.utilidad - 1)) + (state.newSale.priceMax * parseInt(state.newSale.quantity)))

        const high_price = (((state.newSale.priceMax * 1.19) * state.newSale.utilidad) + state.newSale.flete)
        const average_price_rounded = Math.ceil(state.newSale.average_price / 10) * 10;
        const high_price_rounded = Math.ceil(high_price / 10) * 10;

        if (high_price_rounded !== average_price_rounded) {
            state.newSale.price_sale = high_price_rounded * parseInt(state.newSale.quantity)
        } else {
            state.newSale.price_sale = average_price_rounded * parseInt(state.newSale.quantity)
        }
    },

    allProducts(state) {
        let url = urlAllProduct
        axios.get(url).then(response => {
            state.optionsProduct = []
            response.data.forEach((product) => {
                const inventory = product.inventories.reduce((mayor, product) => {
                    if (product.quantity > mayor.quantity && product.quantity > 0) {
                        return product;
                    } else {
                        return mayor;
                    }
                }, { quantity: 0 });

                if (inventory.quantity !== 0) {
                    state.optionsProduct.push({
                        label: product.name,
                        value: product.id,
                        inventories: inventory,
                        utilidad: product.utilidad,
                        flete: product.flete,
                        codebar: product.codebar,
                    })
                }

            });

            sortProductsByModelRelations(state)
        });
    },
    setProduct(state, product) {
        state.selectedProduct = product
        if (state.selectedProduct != null) {
            state.newDetailclient.product = state.selectedProduct.label
            state.newDetailclient.detail = state.selectedProduct.codebar
            state.newDetailclient.percentage = state.selectedProduct.utilidad
            state.newDetailclient.transport = state.selectedProduct.flete
            state.newDetailclient.price = state.selectedProduct.inventories.price

            state.newDetailclient.utility = Math.round(parseFloat((
                parseFloat(state.newDetailclient.price) *
                ((parseFloat(state.newDetailclient.percentage) / 100) + 1) +
                parseFloat(state.newDetailclient.aditional) -
                parseFloat(state.newDetailclient.price)) *
                parseFloat(state.newDetailclient.quantity)))



            let percentage = (parseInt(state.newDetailclient.percentage) / 100) + 1
            let flete = parseInt(state.newDetailclient.transport)
            let aditional = parseFloat(state.newDetailclient.aditional)
            let quantity = parseFloat(state.newDetailclient.quantity)
            let price = parseFloat(state.newDetailclient.price)

            state.newDetailclient.total = Math.round(((price * percentage * 1.19) + aditional + flete) * quantity);

        } else {
            state.newDetailclient.product = ''
            state.newDetailclient.product = ''
            state.newDetailclient.price = 0
            state.newDetailclient.utility = 0
            state.newDetailclient.total = 0
        }
    },
    allProductimports(state) {
        let url = urlAllProductimport
        axios.get(url).then(response => {
            state.optionsProductimport = []
            response.data.forEach((product) => {
                state.optionsProductimport.push({
                    label: product.name + ' - ' + product.detail,
                    value: product.id,
                    name: product.name,
                    detail: product.detail
                })
            })
        });
    },
    setProductimport(state, productimport) {
        state.selectedProductimport = productimport
        if (state.selectedProductimport != null) {
            state.newDetailimport.product = state.selectedProductimport.name
            state.newDetailimport.detail = state.selectedProductimport.detail
        } else {
            state.newDetailimport.product = ''
            state.newDetailimport.detail = ''
        }
    },


    /****** sección paginacion **** */
    /******************************* */
    paginate(state, page) {
        state.pagination.current_page = page
    },
    searchSii(state) {
        let rutSii = state.newClient.rut
        rutSii = rutSii.split('.').join('')
        let settings = {
            "async": true,
            "crossDomain": true,
            "url": "https://dev-api.haulmer.com/v2/dte/taxpayer/" + rutSii,
            "method": "GET",
            "headers": {
                "apikey": "928e15a2d14d4a6292345f04960f4bd3"
            }
        }

        $.ajax(settings).done(function (response) {
            state.newClient.razonSocial = response.razonSocial
            state.newClient.email = response.email
            state.newClient.phone = response.telefono
            state.newClient.address = response.direccion
            state.newClient.comuna = response.comuna
            state.newClient.giro = response.actividades[0].giro
            state.newClient.activity = response.actividades
        });
    },
    sumTotalProductMechanic(state) {
        state.newDetailclient.total = state.newDetailclient.price
    },
    sumTotalEditProductMechanic(state) {
        state.fillDetailclient.total = state.fillDetailclient.price
        state.fillDetailclient.totalIVA = Math.round(state.fillDetailclient.total * 1.19)
    },
    sumTotalProduct(state) {
        let percentage = (parseInt(state.newDetailclient.percentage) / 100) + 1
        let flete = parseInt(state.newDetailclient.transport)
        let aditional = parseFloat(state.newDetailclient.aditional)
        let quantity = parseFloat(state.newDetailclient.quantity)
        let price = parseFloat(state.newDetailclient.price)

        if (state.newDetailclient.quantity != '') {
            state.newDetailclient.utility = Math.round((price * parseInt(state.newDetailclient.percentage) / 100) * quantity)
            state.newDetailclient.total = Math.round(((price * percentage * 1.19) + aditional + flete) * quantity)
        }
    },
    sumTotalEditProduct(state) {
        let percentage = (parseInt(state.fillDetailclient.percentage) / 100) + 1
        let flete = parseInt(state.fillDetailclient.transport)
        let aditional = parseFloat(state.fillDetailclient.aditional)
        let quantity = parseFloat(state.fillDetailclient.quantity)
        let price = parseFloat(state.fillDetailclient.price)

        if (state.fillDetailclient.quantity != '') {
            state.fillDetailclient.utility = ((price * parseInt(state.fillDetailclient.percentage) / 100) * quantity)
            state.fillDetailclient.total = Math.round(parseInt((price * percentage) + aditional + flete / 1.19) * quantity)
            state.fillDetailclient.totalIVA = Math.round(((price * percentage * 1.19) + aditional + flete) * quantity)
        }
    },
    distributionImport(state) {
        state.detailimports.forEach(detailImport => {

            let embarque = parseFloat(state.detailImport.embarque)
            let seguro = parseFloat(detailImport.seguro) + 1
            let usa = parseFloat(detailImport.usa) + 1
            let fee = parseFloat(state.detailImport.fee)
            let fleteUsa = parseFloat(state.detailImport.fleteUsa)
            let bankusa = parseFloat(state.detailImport.bankusa)
            let bankchile = parseFloat(state.detailImport.bankchile)
            let transferencia = parseFloat(state.detailImport.transferencia)
            let otro = parseFloat(state.detailImport.otro)
            let utilidad = "" + detailImport.valueChile

            let percentage = parseFloat((parseFloat(detailImport.price) * parseInt(detailImport.quantity) * 100) / state.totalPriceImport)

            detailImport.percentage = percentage

            detailImport.embarque = parseFloat(percentage / 100 * embarque) * parseFloat(state.detailImport.dolar)

            detailImport.seguro = parseFloat(percentage / 100 * seguro) * parseFloat(state.detailImport.dolar)

            detailImport.fee = parseFloat(percentage / 100 * fee) * parseFloat(state.detailImport.dolar)

            detailImport.fleteUsa = parseFloat(percentage / 100 * fleteUsa) * parseFloat(state.detailImport.dolar)

            detailImport.bankusa = parseFloat(percentage / 100 * bankusa) * parseFloat(state.detailImport.dolar)

            detailImport.bankchile = parseFloat(percentage / 100 * bankchile) * parseFloat(state.detailImport.dolar)

            detailImport.transferencia = parseInt(percentage / 100 * transferencia) * parseFloat(state.detailImport.dolar)

            detailImport.otro = parseInt(percentage / 100 * otro) * parseFloat(state.detailImport.dolar)


            detailImport.aduana1 = parseFloat(percentage / 100 * state.detailImportNacional.aduana1)
            detailImport.aduana2 = parseFloat(percentage / 100 * state.detailImportNacional.aduana2)
            detailImport.manipuleo = parseFloat(percentage / 100 * state.detailImportNacional.manipuleo)
            detailImport.bodega = parseFloat(percentage / 100 * state.detailImportNacional.bodega)
            detailImport.guia = parseFloat(percentage / 100 * state.detailImportNacional.guia)
            detailImport.retiro = parseFloat(percentage / 100 * state.detailImportNacional.retiro)
            detailImport.fleteChile = parseFloat(percentage / 100 * state.detailImportNacional.fleteChile)

            detailImport.price_dolar = parseFloat(detailImport.price) * parseFloat(state.detailImport.dolar)

            if (detailImport.valorem == 1) {

                detailImport.total = parseFloat(parseFloat(detailImport.price_dolar) *
                    parseFloat(seguro) *
                    parseFloat(usa) *
                    parseFloat(detailImport.quantity)
                ) +
                    parseFloat(detailImport.embarque) +
                    //parseFloat( detailImport.seguro ) +
                    parseFloat(detailImport.fee) +
                    parseFloat(detailImport.fleteUsa) +
                    parseFloat(detailImport.bankusa) +
                    parseFloat(detailImport.bankchile) +
                    parseFloat(detailImport.transferencia) +
                    parseFloat(detailImport.otro)

                let advalorem = parseFloat(detailImport.total * 0.06)

                detailImport.total = detailImport.total + advalorem

            }

            if (detailImport.valorem == 0) {

                detailImport.total = parseFloat(parseFloat(detailImport.price_dolar) *
                    parseFloat(seguro) *
                    parseFloat(usa) *
                    parseFloat(detailImport.quantity)
                ) +
                    parseFloat(detailImport.embarque) +
                    parseFloat(detailImport.fee) +
                    parseFloat(detailImport.fleteUsa) +
                    parseFloat(detailImport.bankusa) +
                    parseFloat(detailImport.bankchile) +
                    parseFloat(detailImport.transferencia) +
                    parseFloat(detailImport.otro)
            }

            let totalInternacional =
                parseFloat(detailImport.embarque) +
                parseFloat(detailImport.fee) +
                parseFloat(detailImport.fleteUsa) +
                parseFloat(detailImport.bankusa) +
                parseFloat(detailImport.bankchile) +
                parseFloat(detailImport.transferencia) +
                parseFloat(detailImport.otro)

            let totalNacional =
                parseFloat(state.detailImportNacional.aduana1) +
                parseFloat(state.detailImportNacional.aduana2) +
                parseFloat(state.detailImportNacional.manipuleo) +
                parseFloat(state.detailImportNacional.bodega) +
                parseFloat(state.detailImportNacional.guia) +
                parseFloat(state.detailImportNacional.retiro) +
                parseFloat(state.detailImportNacional.fleteChile)


            detailImport.internacional = totalInternacional
            detailImport.nacional = parseFloat(percentage / 100 * totalNacional)

            detailImport.costTotal = parseFloat(detailImport.internacional) + parseFloat(detailImport.nacional)

            detailImport.total = parseFloat(detailImport.total) + parseFloat(detailImport.nacional)

            detailImport.utility = parseFloat(utilidad) - parseFloat(detailImport.total / detailImport.quantity)

            detailImport.unitario = parseFloat(detailImport.total / detailImport.quantity)

        })
    },
    sumUtility(state) {
        state.detailimports.forEach(detailImport => {
            detailImport.utility = parseFloat(detailImport.valueChile) -
                parseFloat(detailImport.total / detailImport.quantity)
        })
    },

    addToCart(state) {
        const newSale = state.newSale;
        const cart = state.cart;

        if (newSale.quantity > newSale.totalSumQuantity) {
            toastr.error('¡Error, Supera la cantidad disponibles!');
            return;
        }

        const existingCartItem = cart.find(cartItem => cartItem.id === newSale.id);

        if (existingCartItem) {
            existingCartItem.quantity += parseInt(newSale.quantity);
            existingCartItem.totalNeto += newSale.totalNeto;
            existingCartItem.total += newSale.price_sale;
        } else {
            cart.push({
                id: newSale.id,
                name: newSale.product,
                price: newSale.price,
                utilidad: newSale.utilidad,
                quantity: parseInt(newSale.quantity),
                neto: newSale.price_sale,
                totalNeto: newSale.totalNeto,
                total: newSale.price_sale
            });
        }

        state.cartNeto += newSale.totalNeto;
        state.cartTotal += newSale.price_sale;
        newSale.totalSumQuantity -= parseInt(newSale.quantity);
        state.aplicardescuento = 0
    },


    agregarFormaPago(state) {
        state.formapago = state.selectedPago.label
        $('#formaPago').modal('hide')
    },

    aplicarDescuento(state) {
        state.aplicardescuento = state.newDescuento.descuento
    },

    updateUtility(state, data) {
        state.productForm.utility = data.target.value
    },

    updateQuantity(state, data) {
        state.productForm.quantity = data.target.value
    },

    updateTotal(state) {
        state.productForm.value = Math.round(parseFloat(state.selectedPrice.label) *
            parseFloat((state.productForm.utility / 100) + 1) *
            parseFloat(state.productForm.quantity))
        state.productForm.total = Math.round(state.productForm.value * 1.19)
    },

    createSale(state) {
        let url = urlCreateSale
        if (state.formapago == '') {
            toastr.error('¡Error, Selecione la forma de pago!')
        } else {
            let sale = {
                total: state.cartTotal,
                formapago: state.formapago,
                descuento: state.aplicardescuento,
                cart: state.cart
            }

            if (state.cartTotal > 0) {
                axios.post(url, sale)
                    .then(response => {
                        state.cart = []
                        state.cartTotal = 0
                        state.cartNeto = 0
                        state.aplicardescuento = 0
                        state.formapago = 'CONTADO'
                        state.selectedProductSale = []

                        state.newSale = {
                            id: 0,
                            product: '',
                            code: '',
                            quantity: 1,
                            price: 0,
                            price_sale: 0,
                            utility: 0,
                            flete: 0,
                            priceMax: 0,
                            average_price: 0,
                            totalSumPrice: 0,
                            totalSumQuantity: 0,
                            neto: 0
                        }

                        toastr.success('Venta generada con exito!')
                        $('#create').modal('hide')
                        this.commit('allSales', { page: 1 })
                        this.commit('allProductsSale');
                    })
                    .catch(error => {
                        toastr.error(error.response.data)
                    })
            }
        }
    },

    cierreCajaZ(state) {
        if (state.calendar.search) {
            let url = urlCierreCajaZ + '/' + state.calendar.search
            axios.get(url)
                .then(response => {
                    if (response.data.error !== 0) {
                        window.location.href = url;
                    } else {
                        toastr.error('No hay ventas en esta fecha')
                    }
                }).catch(error => {
                    toastr.error(error.response.data)
                })

        } else {
            toastr.warning('Seleccione un fecha')
        }
    },


    allSales(state, data) {
        const { page, perPage } = resolvePaginationRequest(data, state.pagination.per_page || 20)
        const calendar = data && data.calendar ? data.calendar : ''

        axios.get(urlSale + '?page=' + page + '&calendar=' + calendar + '&per_page=' + perPage)
            .then(response => {
                state.sales = response.data.sales.data
                state.pagination = response.data.pagination
            })
            .catch(error => {
                toastr.error(error.response.data)
            })

    },

    generarRecibo(state, id) {
        let url = urlGenerarReciboSales + '/' + id
        window.location.href = url;
    },

    async eliminarVenta(state, id) {
        let url = urlAnularSale + '/' + id
        try {
            const response = await axios.delete(url);
            if (response.data.status === true) {
                toastr.success('La Venta se anulo correctamente');
                this.commit('allSales', { page: 1 })
            }
        } catch (error) {
            toastr.error("La venta no se anulo")
            throw error; // Rechazar la promesa si hay un error
        }
    },

    updateCodeFields(state) {

        if (state.selectedCode.label !== '' || state.productForm.code !== '') {
            state.productForm.value = Math.round(parseFloat(state.productForm.price) *
                parseFloat((state.productForm.utility / 100) + 1) *
                parseFloat(state.productForm.quantity))
            state.productForm.total = Math.round(state.productForm.value * 1.19)
        }


    },

    removeFromCart(state, data) {
        let product = state.cart.find(p => p.id == data.id)
        state.newSale.totalSumQuantity += product.quantity
        state.cartNeto -= product.totalNeto
        state.cartTotal -= product.total
        state.cart.splice(state.cart.indexOf(data.id))
    },

    removeDescuento(state) {
        state.aplicardescuento = 0
    },

    getMechanicClients(state) {
        axios.get('mechanic-clients')
            .then((response) => {
                state.users = response.data
                state.optionsMechanicClient = []
                response.data.forEach((user) => {
                    state.optionsMechanicClient.push({
                        label: user.name,
                        value: user.id
                    })
                });
            })
    },

    getTotalVehi(state) {
        let url = urlTotalVehi + '/' + state.fillCantVehicle.id
        //let url = urlTotalVehi
        axios.get(url).then(response => {
            state.totalvehi = response.data
        });
    },

    getSumaVehi(state) {
        let url = urlSumaVehi
        axios.get(url).then(response => {
            state.sumavehi = response.data
        });
    },

    getTotalCli(state, user) {
        let url = urlTotalCli
        axios.get(url).then(response => {
            state.totalcli = response.data
        });
    },

    getTotalCliAdmin(state, user) {
        let url = urlTotalCliAdmin + '/' + user.id
        axios.get(url).then(response => {
            state.totalcliadmin = response.data
        });
    },

    getTotalVehiAdmin(state, user) {
        let url = urlTotalVehiAdmin + '/' + user.id
        axios.get(url).then(response => {
            state.totalvehiadmin = response.data
        });
    },

    createMechanicClient(state) {

        axios.post('mechanic-client/' + state.idforms, {
            // axios.post('mechanic-client',{
            name: state.newUser.name,
            email: state.newUser.email,
            password: state.newUser.password,
        }).then(response => {
            state.newUser = {
                id: '',
                name: '',
                email: '',
                password: ''
            }
            state.errorsLaravel = []
            $('#modalCreateUserMechanic').modal('hide')
            toastr.success('Usuario generado con éxito')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    createMechanicClient2(state) {

        axios.post('mechanic-client2', {
            name: state.newUser.name,
            email: state.newUser.email,
            password: state.newUser.password,
            //cant_vehicle: state.newUser.cant_vehicle
        }).then(response => {
            state.newUser = {
                id: '',
                name: '',
                email: '',
                password: ''
                //cant_vehicle: ''
            }
            state.errorsLaravel = []
            toastr.success('Usuario generado con éxito')
        }).catch(error => {
            toastr.error(error.response.data)
        })
    },

    getClientVehicles(state) {
        axios.get('client-vehicles')
            .then((response) => {
                state.vehicles = response.data
            })
    },

    setMechanicClient(state, client) {
        state.selectedMechanicClient = client
    },

    createVehicleMechanicClient(state) {
        let id_user = null
        if (state.selectedMechanicClient != null) {
            id_user = state.selectedMechanicClient.value

            // if (!state.selectedVYear.label) {
            //     state.selectedVYear.label = '1'
            //     state.selectedVEngine.label = 'INDEFINIDO'
            // }
            axios.post('vehicles-mechanic', {
                user_id: id_user,
                patent: state.newVehicle.patent,
                chasis: state.newVehicle.chasis,
                brand: state.selectedVBrand.label,
                model: state.selectedVModel.label,
                year: state.selectedVYear.label,
                engine: state.selectedVEngine.label,
                color: state.newVehicle.color,
                km: state.newVehicle.km,
            }).then(response => {
                state.newVehicle.patent = ''
                state.newVehicle.chasis = ''
                state.newVehicle.color = ''
                state.newVehicle.km = ''
                state.errorsLaravel = []
                $('#createVehicleMechanic').modal('hide')
                toastr.success('Vehículo generado con éxito')
            }).catch(error => {
                toastr.error(error.response.data)
            })
        }
    },

    getQuotationUsers(state) {
        let url = urlQuotationusers + '/' + state.idUser
        axios.get(url).then(response => {
            state.quotationusers = response.data
        });
    },

    getQuotationUsersMechanic(state) {
        let url = 'quotationUserMechanic/' + state.idforms
        axios.get(url).then(response => {
            state.quotationUserMechanic = response.data
        });
    },

    modalCreateUserFromQuotation(state, id) {
        state.idUser = id
        $('#modalCreateUser').modal('show')
    },



    //AQUI COMENZAR EL EVENTO:

    juntarDatos(state) {
        let prueba
        let contador = 1
        let sumaTotal
        let sumaTotalBoleta = 0
        let lineaCompleta = ''

        if (state.arrayBoleta.length != 0) {
            state.arrayBoleta.map(function (bar) {
                if (contador == state.arrayBoleta.length) {
                    sumaTotal = 1 * bar.precio
                    contador = contador + 1
                    sumaTotalBoleta = sumaTotalBoleta + sumaTotal
                    sumaTotal = 0
                } else {
                    sumaTotal = 1 * bar.precio
                    contador = contador + 1
                    sumaTotalBoleta = sumaTotalBoleta + sumaTotal
                    sumaTotal = 0
                }
                lineaCompleta = lineaCompleta + prueba
            })

            axios.post('/Invoice/Generator', {
                resultado: state.resultado,
                sumaTotalBoleta: sumaTotalBoleta,
                lineaCompleta: lineaCompleta,
                abrirPDF: state.abrirPDF,
                fecha: state.data1.fecha,
                giroEmisor: state.data1.giroEmisor,
                dirOrigen: state.data1.dirOrigen,
                rutReceptor: state.data1.rutReceptor,
                producto: state.data1.producto,
                cantidad: state.data1.cantidad,
                precio: state.data1.precio
            }).then((response) => {
                state.resultado = response.data
                state.arrayBoleta = []
                printJS({
                    printable: state.resultado.replace(/app\/public\//g, 'storage/'),
                    type: 'pdf'
                });
            })
            state.enlace = "Archivo Generado"

        } else {
            state.resultado = "Falta agregar producto"
        }
    },

    crearArreglo(state) {
        let sumaTotal = 0
        let sumaTotalBoleta = 0
        if (state.data2.precio >= 180) {
            state.arrayBoleta.push({
                precio: state.data2.precio,
                total: sumaTotalBoleta == 0 ? state.data2.precio : sumaTotalBoleta
            })
            state.arrayBoleta.map(function (bar) {
                sumaTotal = 1 * bar.precio
                sumaTotalBoleta = sumaTotalBoleta + sumaTotal
                sumaTotal = 0
            })
            sumaTotalBoleta: state.sumaTotalBoleta
            state.data2.precio = ''
        }
    },

    updateFleteDefect(state) {
        let url = urlFlete
        axios.post(url, state.newFlete).then(response => {
            if (response.status === 200 && response.data > 0) {
                state.newFlete.flete = response.data
                state.errorsLaravel = []
                toastr.success('El flete se actualizo éxito')
            }
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },
    getFletes(state) {
        let url = urlFlete
        axios.get(url).then(response => {
            state.newFlete.flete = response.data.flete
            state.newDetailclient.transport = response.data.flete
        });
    },

    actualizarCorrelativo(state) {
        let url = urlActualizarCorrelativo
        axios.post(url).then(response => {
            this.commit('getQuotationclients', { page: 1 });
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    setCheckedSpareParts(state, value) {
        state.checkedSpareParts = value;
        state.newDetailclient.spare_parts = ''
    },

    saveSparePart(state) {
        let url = urlSparePart
        const data = {
            id_quotation: state.idQuotationclient,
            spare_parts: state.newDetailclient.spare_parts
        }

        axios.post(url, data).then(response => {
            if (response.status === 200) {
                // state.newFlete.flete = response.data
                state.errorsLaravel = []
                toastr.success('La solicitud de repuesto se agrego con éxito')
            }
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    eliminarCategoria(state, id) {
        let url = urlEliminarCategoriaChecklist + '/' + id
        axios.delete(url).then(response => {
            toastr.success(response.data.message)
            this.commit('mostrarFormatoCheckList')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    eliminarIntervencion(state, id) {
        let url = urlEliminarIntervencionChecklist + '/' + id
        axios.delete(url).then(response => {
            toastr.success(response.data.message)
            this.commit('mostrarFormatoCheckList')
        }).catch(error => {
            state.errorsLaravel = error.response.data
        })
    },

    editarTrabajo(state, data) {
        state.verBotonActualizar = true
        state.newOrdenTrabajo.vehicle_id = data.id
        state.newOrdenTrabajo.km = data.km
        state.newOrdenTrabajo.descripcion = data.descripcion
    },

    volverAgregar(state) {
        state.verBotonActualizar = false;
        state.newOrdenTrabajo.km = 0
        state.newOrdenTrabajo.descripcion = ''
    }

}
