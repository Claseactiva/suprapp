export default { //data
    options: [], //arreglo para ser utilizado en el componente v-select para volcar los objetos
    optionsUser: [],
    optionsPago: [],
    optionsPersonal: [],
    optionsClient: [],
    optionsProduct: [],
    optionsProductSale: [],
    optionsProductimport: [],
    optionsVehicleBrand: [],
    optionsVehicleModel: [],
    optionsTiposVehiculo: [],
    optionsMotores: [],
    optionsYear: [],

    optionsVBrand: [],
    optionsVModel: [],
    optionsVYear: [],
    optionsVEngine: [],
    optionsCiudad: [],
    optionsVehicleClient: [],

    linkenvio: {
        url: ''
    },

    selectedVBrand: {
        label: '',
        value: ''
    },
    selectedVModel: {
        label: '',
        value: ''
    },
    selectedVYear: {
        label: '',
        value: ''
    },
    selectedVEngine: {
        label: '',
        value: ''
    },

    selectedCiudad: {
        label: '',
        value: ''
    },

    optionsVBR: [],
    optionsVMR: [],
    optionsMM: [],
    optionsYM: [],
    
    selectedVBR: {
        label: '',
        value: ''
    },
    selectedVMR: {
        label: '',
        value: ''
    },
    selectedMM: {
        label: '',
        value: ''
    },
    selectedYM: {
        label: '',
        value: ''
    },
    
    selectedVehiculoTipo: {
        label: '',
        value: ''
    },
    selectedVehicleMotor: {
        label: '',
        value: ''
    },

    selectedVehicleYear: {
        label: '',
        value: ''
    },


    pendingQuotations: [],

    selectedItem: null, //captura el elemento seleccionado

    selectedPago: {
        label: '',
        value: ''
    },
    selectedUser: {
        label: '',
        value: ''
    },
    selectedClient: {
        label: '',
        value: ''
    },
    selectedProduct: {
        label: '',
        value: ''
    },
    selectedProductSale: {
        label: '',
        value: ''
    },
    selectedProductCode: {
        label: '',
        value: ''
    },
    selectedClientCode: {
        label: '',
        value: ''
    },
    selectedProductimport: {
        label: '',
        value: ''
    },
    selectedProductImports: {
        label: '',
        value: ''
    },
    selectedPersonal: null, //captura el elemento seleccionado
    selectedVehicleBrand: {
        label: '',
        value: ''
    },
    selectedVehicleModel: {
        label: '',
        value: ''
    },
    /*** sección componentes de vehiculos */
    vehicles: [],
    rol: '',
    rol: '',
    vehicle: {
        id: '',
        user_id: '',
        patent: '',
        chasis: '',
        name: '',
        year: '',
        color: '',
        km: ''
    },
    newVehicle: {
        user_id: '',
        patent: '',
        chasis: '',
        brand: '',
        model: '',
        year: '',
        color: '',
        km: ''
    },
    fillVehicle: {
        id: '',
        user_id: '',
        patent: '',
        chasis: '',
        brand: '',
        model: '',
        year: '',
        engine: '',
        color: '',
        km: ''
    },
    searchVehicle: {
        patent: '',
        name: '',
        year: ''
    },
    newDetailVehicle: {
        vehicle_id: '',
        km: '',
        note: '',
        rol: ''
    },
    newOrdenTrabajo: {
        vehicle_id: 0,
        km: 0,
        km_old: 0,
        tendenciaKm: 0,
        descripcion: '',
        observacion: ''
    },
    checkListForm:{
        categoria: '',
    },
    intervencionForm:{
        id_categoria: 0,
        intervencion: '',
    },
    editarCategoriaForm:{
        id_categoria: 0,
        categoria: ''
    },
    editarIntervencionForm:{
        id_intervencion: 0,
        intervencion: ''
    },
    columnaExiste:[],
    columnaEstado:[],
    columnaObservacion:{
        id_intervencion: 0,
        id_vehicle: 0,
        observacion: '',
        imagenes: []
    },
    mostrarchecklistvehicles:[],
    checklistvehicles:[],
    checklists:[],
    formatchecklists:[],
    editarIntervenciones:[],
    categorias:[],
    ordenestrabajos:[],
    intervenciones:[],
    condiciones: [],
    id_checklist: 0,
    id_vehicle: 0,
    trabajos:[],
    observaciones:[],
    roleschecklists:[],
    km_old: 0,
    /**********************************/
    vehiculotipos: [],
    vehiculotipo: {
        id: '',
        tipo_vehiculo: ''
    },
    vehiclebrands: [],
    vehiclebrand: {
        id: '',
        brand: '',
        model: ''
    },
    vehiclemodels: [],
    vehiclemodel: {
        id: '',
        brand: '',
        model: '',
        tipo: ''
    },
    vehiclemotors: [],
    vehiclemotor: {
        id: '',
        v_engine: ''
    },
    newVehicleModelo:{
        model: '',
        brand_id: '',
        tipo_id: ''
    },
    newVehiculoTipo: {
        tipo_vehiculo: ''
    },

    newVehicleYear:{
        v_id: '',
        v_year: ''
    },
    vehicleyears: [],
    vehicleyear: {
        id: '',
        v_year: '',
        model: ''
    },
    newVehicleMotor: {
        year_id: '',
        v_engine: ''
    },
    newVehicleBrand: {
        brand: ''
        //model: '',
        //tipo_id: ''
    },
    fillVehicleBrand: {
        id: '',
        brand_id: '',
        brand: '',
        model: ''
    },
    fillVehiculoTipo: {
        id: '',
        tipo_vehiculo: '',
    },
    fillVehicleModel: {
        id: '',
        model: '',
        brand_id: '',
        tipo_id:''
    },
    fillVehicleYear: {
        id: '',
        v_year: '',
        //model: ''
    },
    fillVehicleMotor: {
        id: '',
        //year_id: '',
        v_engine: ''
    },
    searchVehicleBrand: {
        brand: '',
        model: ''
    },

    /**********************************/
    vehicleDetails: [],
    details: [],
    detail: {
        id: '',
        km: '',
        note: ''
    },
    idDetailvehicle: null,
    /********************************* */
    /************************************ */
    /*** sección componentes de notas */
    notes: [],
    note: {
        id: '',
        price: '',
        detail: ''
    },
    newNote: {
        price: '',
        detail: ''
    },
    fillNote: {
        id: '',
        price: '',
        detail: ''
    },
    searchNote: {
        price: '',
        detail: ''
    },
    /********************************* */
    /************************************ */
    /*** sección componentes de cotizaciones */
    idforms: null,
    quotationforms: [],
    selectedVehicleClient: {
        label: '',
        value: ''
    },
    quotations: [],
    newQuotation: {
        client: '',
        vehicle: '',
        patent: '',
        state: ''
    },
    fillQuotation: {
        id: '',
        client: '',
        vehicle: '',
        patent: '',
        state: ''
    },
    searchQuotation: {
        client: '',
        patent: ''
    },
    idQuotation: null,
    totalQuotation: 0,
    totalQuotationIVA: 0,
    /********************************* */
    /************************************ */
    /*** sección componentes de cotizaciones para clientes*/
    quotationclients: [],
    quotationclientsform: [],
    quotationshipping: [],
    checkEnviado: [],
    checkRealizado: [],
    cerrarObservacion: [],
    
    newQuotationclient: {
        client_id: '',
        state: '',
        payment: '',
        client_text: '',
        cliente_part: false,
        url: '',
        vehicle: '',
        generado: '',
        generado_client: '',
        ppu: ''
    },
    fillQuotationclient: {
        id: '',
        client_id: '',
        state: '',
        payment: '',
        client_text: '',
        vehicle: '',
        url: '',
        ppu: ''
    },
    searchQuotationClient: {
        id: '',
        razonSocial: '',
        client: '',
        vehicle: '',
        day: '',
        month: '',
        year: ''
    },
    searchQuotationClientForm: {
        id: '',
        razonSocial: '',
        client: '',
        vehicle: '',
        day: '',
        month: '',
        year: ''
    },
    searchShipping: {
        id: '',
    },
    idQuotationclient: null,
    idQuotationShipping: null,
    totalUtilidad: 0,
    totalTransporte: 0,
    totalAdicional: 0,
    totalQuotationclient: 0,
    totalQuotationclientIVA: 0,
    totalProductIvaFlete: 0,
    /********************************* */
    /************************************ */
    /*** sección componentes de cotizaciones para importaciones*/
    newQuotationimport: {
        import_id: '',
        user_id: '',
        client_id: '',
        payment: '',
        state: ''
    },
    fillQuotationimport: {
        id: '',
        import_id: '',
        user_id: '',
        client_id: '',
        payment: '',
        state: ''
    },
    idQuotationimport: null,
    totalQuotationimport: 0,
    totalQuotationimportIVA: 0,
    /********************************* */
    /********************************* */
    /*** sección componentes de cotizaciones usuario cliente */
    formCotizacion: {
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

    formCotizacionExpress: {
        patentchasis: '',
        brand: '',
        model: '',
        year: '',
        engine: '',
        description: ''
    },

    formQuotationShipping: {
        id: '',
        nombre: '',
        rut: '',
        telefono: '',
        ciudad: '',
        direccion: 'SIN ENVIO',
        sucursal: ''
    },
    quotationDesc: '',
    /************************************ */
    /*** sección componentes de importaciones */
    idImport: null,
    imports: [],
    newImport: {
        name: '',
        dolar: '',
        safe: '',
        transport: '',
        internment: '',
        other1: '',
        other2: '',
        total: ''
    },
    fillImport: {
        id: '',
        name: '',
        dolar: '',
        safe: '',
        transport: '',
        internment: '',
        other1: '',
        other2: '',
        total: ''
    },
    searchImport: {
        name: ''
    },
    import: null,
    /********************************* */
    /************************************ */
    /*** sección componentes de detalle */
    details: [],
    newDetail: {
        product: '',
        price: ''
    },
    fillDetail: {
        id: '',
        product: '',
        price: ''
    },
    /********************************* */
    /************************************ */
    /*** sección componentes de detalle de un cliente */
    detailclients: [],
    newDetailclient: {
        quotationclient_id: '',
        product: '',
        detail: '',
        price: 1,
        quantity: 1,
        percentage: 0,
        aditional: 0,
        transport: 0,
        utility: 0,
        total: 0,
        days: '24 a 48 Hrs',
        spare_parts: ''
    },
    fillDetailclient: {
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
        total: 0,
        totalIVA: 0,
        days: '',
        spare_parts: ''
    },
    /********************************* */
    /************************************ */
    /*** sección componentes de detalle de un cliente */
    detailimports: [],
    newDetailimport: {
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

    fillDetailimport: {
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

    detailImport: {
        id: 0,
        dolar: 700,
        embarque: 0,
        seguro: 1,
        fee: 0,
        fleteUsa: 0,
        bankusa: 0,
        bankchile: 0,
        transferencia: 0,
        otro: 0,
    },

    detailImportNacional: {
        aduana1: 0,
        aduana2: 0,
        manipuleo: 0,
        bodega: 0,
        guia: 0,
        retiro: 0,
        fleteChile: 0
    },
    totalNeto: 0,
    totalNacional: 0,
    totalInternacional: 0,
    totalCosto: 0,
    totalImport: 0,
    totalValue: 0,
    totalPriceImport: 0,
    totalImportIVA: 0,
    /********************************* */
    /************************************ */
    /*** sección componentes de clientes */
    clients: [],
    client: {
        id: '',
        user_id: '',
        name: '',
        rut: '',
        razonSocial: '',
        email: '',
        phone: '',
        address: '',
        comuna: '',
        giro: '',
        type: '',
        activities: {}
    },
    newClient: {
        user_id: '',
        rut: '',
        name: '',
        razonSocial: '',
        email: '',
        phone: '',
        address: '',
        comuna: '',
        giro: '',
        type: '',
        activities: {}
    },
    fillClient: {
        id: '',
        user_id: '',
        name: '',
        rut: '',
        razonSocial: '',
        email: '',
        phone: '',
        address: '',
        comuna: '',
        type: ''
    },
    searchClient: {
        rut: ''
    },
    /********************************* */
    /************************************ */
    /*** sección componentes de productos */
    import_file: '',
    products: [],
    product: {
        name: '',
        detail: ''
    },
    newUtilidad:{
        utilidad: ''
    },
    newTipoPago: {
        pago: '',
        utilidad: ''
    },
    newDescuento: {
        descuento: 0,
    },
    tipospagos:[],
    fillTipoPago: {
        id: '',
        pago: '',
        utilidad: ''
    },
    fillProduct:{ id: 0, name: '', codebar: '', detail: '', atributo: 0, utilidad: 0, flete: 0, folio: 0 },
    searchProduct: {
        name: ''
    },
    search:{
        name: ''
    },
    calendar: {
        search: ''
    },
    /*********************************** */
    /************************************ */
    /*** sección componentes de codigos */
    codes: [],
    newProduct:{ name: '', codebar: '', client_id: '', detail: '', atributo: 0, utilidad: 0, flete: 0, folio: 0 },
    searchCode: {
        codebar: ''
    },

    /*** seccion inventario ***/
    nuevoInventario: {
        product_id: '',
        client_id: '',
        code_id: '',
        price: 0,
        quantity: 0
    },

    /*********************************** */
    /************************************ */
    /*** sección componentes de inventarios */
    newInventory: {product_id: 0, quantity: 1, price: 0, discount: 0},
    inventories: [],
    fileInvoice: null,
    searchInventory: { name: ''},
    /********************************* */
    /************************************ */
    /*** sección de la empresa de un usuario */
    newCompany: {
        user_id: '',
        rut: '',
        razonSocial: '',
        email: '',
        phone: '',
        address: '',
        comuna: '',
        giro: '',
        type: '',
        logo: '',
        id: ''
    },
    /******************************************* */
    errorsLaravel: [],
    pagination: {
        'total': 0,
        'current_page': 0,
        'per_page': 0,
        'last_page': 0,
        'from': 0,
        'to': 0,
    },
    offset: 2,

    pagination_shipping: {
        'total': 0,
        'current_page': 0,
        'per_page': 0,
        'last_page': 0,
        'from': 0,
        'to': 0,
    },
    offset_shipping: 2,

    pagination_form: {
        'total': 0,
        'current_page': 0,
        'per_page': 0,
        'last_page': 0,
        'from': 0,
        'to': 0,
    },
    offset_form: 2,

    pagination_marca: {
        'total': 0,
        'current_page': 0,
        'per_page': 0,
        'last_page': 0,
        'from': 0,
        'to': 0,
    },
    offset_marca: 5,

    pagination_tipo: {
        'total': 0,
        'current_page': 0,
        'per_page': 0,
        'last_page': 0,
        'from': 0,
        'to': 0,
    },
    offset_tipo: 5,

    pagination_modelo: {
        'total': 0,
        'current_page': 0,
        'per_page': 0,
        'last_page': 0,
        'from': 0,
        'to': 0,
    },
    offset_modelo: 5,

    pagination_year: {
        'total': 0,
        'current_page': 0,
        'per_page': 0,
        'last_page': 0,
        'from': 0,
        'to': 0,
    },
    offset_year: 5,

    pagination_motor: {
        'total': 0,
        'current_page': 0,
        'per_page': 0,
        'last_page': 0,
        'from': 0,
        'to': 0,
    },
    offset_motor: 5,

    attachment: [],
    form: new FormData(),
    records: [],
    images: [],
    docs: [],
    links: [],

    /********************************* */
    /************************************ */
    /*** sección componentes de usuarios */
    idUser: null,
    quotationusers:[],
    quotationUserMechanic:[],
    users: [],
    totalvehi: [],
    sumavehi: [],
    totalcli: [],
    totalcliadmin: [],
    totalvehiadmin: [],
    cantCliVehiAdmin: [],
    quotationRoles: [],
    user: {
        name: '',
        email: '',
        password: '',
        logo: ''
    },
    newUser: {
        id: '',
        name: '',
        email: '',
        password: '',
        url: '',
        logo: '',
        //mecanico: '',
        //cant_client: 0,
        cant_vehicle: 0
    },
    fillUser: {
        id: '',
        name: '',
        email: '',
        password: '',
        url: '',
        ip_acceso: '',
        logo: ''
    },
    fillCantCliVehi: {
        id: '',
        cant_client: 0,
        cant_vehicle: 0,
        rol: ''
    },
    fillCantVehicle: {
        id: '',
        cant_vehicle: 0
    },
    searchUser: {
        barcode: '',
        rut: ''
    },
    /********************************** */
    /*** sección componentes de roles */
    roles: [],
    newRole: {
        name: '',
        description: ''
    },
    fillRole: {
        id: '',
        name: '',
        description: '',
        special: '',
        permissions: []
    },
    userRoles: [],
    fillUserRoles: {
        name: null
    },
    fillQuotationShipping: {
        id: '',
        direccion: ''
    },
    fillFacebookShipping: {
        id: '',
        url: ''
    },
    facebookshipping: [],
    checkedRoles: [],
    permissions: [],
    checkedSpecialRole: '',
    checkedSelect1: '',
    checkedSelect2: [],
    checkedPermissions: [],


    newAllUtilidad: {
        check: [],
        pago: '',
        utilidad: ''
    },
    /***************************************************Seccion Ventas */
    optionsCode: [],
    optionsPrice: [],
    cart: [],
    trabajos: [],
    orden_trabajo: [],
    formapago: 'CONTADO',
    aplicardescuento: 0,
    selectedCode: {
        label: '',
        value: ''
    },
    selectedPrice: {
        label: '',
        value: ''
    },
    newSale: {
        id: 0, 
        product:'', 
        codebar: '', 
        quantity: 1, 
        price: 0, 
        price_sale: 0, 
        utilidad: 0, 
        flete: 0, 
        priceMax: 0, 
        average_price: 0, 
        totalSumPrice: 0,
        totalSumQuantity: 0,
        totalNeto: 0
    },
    cartNeto: 0,
    cartTotal: 0,
    sales: [],
    searchFecha: [],
    productSearch: [],
    productSales: [],
    optionsMechanicClient: [],
    selectedMechanicClient: {
        label: '',
        value: ''
    },

    resultado: 'Archivo no Generado',
    data1: {
        fecha: new Date(),
        giroEmisor: '',
        dirOrigen: '',
        rutReceptor: '',
        producto: '',
        cantidad: '',
        precio: ''
    },
    data2: {
        producto: '',
        cantidad: '',
        precio: ''
    },
    arrayBoleta: [],
    newFlete: { flete: 0 },
    newUtility: { utility: 0 },
    checkedSpareParts: '',
    pago: '',
    productsale: '',
    kilometrajeActual: 0,
    alertkm: '',
    id_trabajo: '',
    verBotonActualizar: false,
    crearFormatoCheckList: true,
    crearIntervencionCheckList: false,
    intervencionCheckList: false,
    mostrarCheckListVehicle: true,
    mostrarObservacion: false
}
