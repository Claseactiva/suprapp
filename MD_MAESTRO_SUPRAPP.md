# MD Maestro SupraApp

## Estado de este analisis

Este documento fue reconstruido desde:

- codigo fuente Laravel/Vue del proyecto
- rutas en `routes/web.php`
- vistas Blade y componentes Vue
- dump de base de datos `supra_suprapp_2024.sql`

El repo no trae documentacion funcional propia. El `README.md` existente es el generico de Laravel.

## Identidad del proyecto

SupraApp es un sistema interno de operacion comercial y taller para Comercial Supra.
No es un sitio publico convencional: es un portal administrativo con modulos de ventas,
cotizaciones, inventario, vehiculos, ordenes de trabajo, checklists, importaciones,
envios, boletas y administracion de usuarios/roles.

## Stack tecnico

- Backend: Laravel 8
- PHP objetivo: `^7.4`
- Frontend: Vue 2 montado sobre Blade
- Bundling: Laravel Mix / Webpack
- UI base: Bootstrap + plantilla tipo admin
- Permisos: `spatie/laravel-permission`
- PDF: `barryvdh/laravel-dompdf`
- Excel: `maatwebsite/excel`
- Imagenes: `intervention/image`
- QR / 2FA instalados por dependencias

Archivos ancla:

- `composer.json`
- `package.json`
- `webpack.mix.js`
- `routes/web.php`
- `resources/assets/js/app.js`
- `resources/views/layouts/portalapp.blade.php`

## Arquitectura general

### 1. Patron de UI

Las vistas Blade son delgadas y casi siempre solo montan un componente Vue.
Ejemplos:

- `resources/views/admin/clientes.blade.php`
- `resources/views/admin/orden-trabajos.blade.php`
- `resources/views/admin/ventas.blade.php`

El registro principal de componentes esta en:

- `resources/assets/js/app.js`

### 2. Rutas

El proyecto trabaja casi todo desde `routes/web.php`.
`routes/api.php` esta practicamente vacio.

Eso significa que:

- las vistas del portal salen por rutas web
- gran parte de los endpoints de datos tambien salen por rutas web
- hay mezcla de UI y API interna en el mismo archivo de rutas

### 3. Control de acceso

El sistema usa autenticacion Laravel, pero personalizada con una capa adicional:

- el usuario tiene un campo `url`
- la entrada al sistema puede pasar por `acceso/{url}`
- luego redirige a `login/{url}`

Esto aparece en:

- `app/User.php`
- `app/Http/Controllers/AccesoController.php`
- `app/Http/Controllers/Auth/LoginController.php`

Ademas el menu del portal se controla con `@can(...)`, por lo que el permiso real
del usuario define que modulos ve.

## Modulos funcionales detectados

Desde `resources/views/layouts/portalapp.blade.php` y `resources/assets/js/app.js`
se detectan estos modulos principales:

1. Empresas / proveedores
2. Productos
3. Inventario
4. Lista de precios
5. Formas de pago / utilidad / descuentos
6. Vehiculos
7. Vehiculos de mecanico
8. Marcas, modelos, anos, motores y tipos de vehiculo
9. Ordenes de trabajo
10. Check list de vehiculos
11. Notas
12. Ventas
13. Cotizaciones express
14. Cotizaciones formales a cliente
15. Envios
16. Boletas
17. Importaciones
18. Usuarios
19. Usuarios mecanicos
20. Roles y permisos

## Base de datos real del negocio

El dump `supra_suprapp_2024.sql` confirma que la base real del sistema vive fuera
de las migraciones del repo.

### Importante

En `database/migrations` solo vienen las migraciones base de Laravel:

- `users`
- `password_resets`
- `failed_jobs`
- `personal_access_tokens`

Pero el negocio real usa muchas tablas adicionales que solo aparecen en el dump.
Por eso este proyecto no se puede entender bien mirando migraciones unicamente.

## Tablas principales detectadas

### Seguridad y acceso

- `users`
- `roles`
- `permissions`
- `model_has_roles`
- `model_has_permissions`
- `role_has_permissions`
- `mechanic_client`

### Clientes, empresas y catalogos comerciales

- `clients`
- `companies`
- `products`
- `inventories`
- `utilities`
- `tipos_pagos`
- `descuentos`
- `fletes`
- `activities`
- `notes`

### Cotizaciones y envios

- `quotationclients`
- `detailclients`
- `quotation_shippings`
- `quotationimports`
- `detailimports`
- `quotation_users`
- `quotation_user_descriptions`
- `quotation_user_vehicles`
- `quotations`

### Taller y vehiculos

- `vehicles`
- `detail_vehicles`
- `images`
- `orden_trabajos`
- `trabajos`
- `image_orden_trabajos`
- `observaciones`
- `observaciones_images`
- `check_lists`
- `check_list_categorias`
- `check_list_intervenciones`
- `check_list_vehicles`
- `check_list_vehicle_categorias`
- `check_list_vehicle_condiciones`
- `check_list_vehicle_intervenciones`
- `check_list_vehicle_observaciones`
- `check_list_vehicle_observaciones_images`

### Catalogo automotriz

- `vehicle_brands`
- `vehicle_brand_models`
- `vehicle_models`
- `vehicle_years`
- `vehicle_engines`
- `vehicle_tipos`

### Venta y documentos

- `sales`
- `product_sale`
- `boletas`

### Importaciones y adjuntos

- `imports`
- `archiveimports`
- `productimports`

### Tablas heredadas o integraciones especiales

- `groups_shopsolver`
- `photos_shopsolver`
- `users_shopsolver`
- `towns`

## Relaciones de negocio importantes

### Usuarios

- un usuario puede tener roles y permisos
- un usuario puede tener clientes
- un usuario puede tener vehiculos
- existe relacion especial mecanico-cliente por tabla `mechanic_client`

### Cotizaciones

Hay al menos dos flujos distintos:

- cotizacion express / usuario
- cotizacion formal a cliente (`quotationclients` + `detailclients`)

Las cotizaciones formales tienen estados y banderas como:

- `state`
- `generado`
- `generado_client`
- `tipo_detalle`
- `url`
- `spare_parts`

### Taller

El modulo de taller se divide en varias capas:

- `vehicles`: ficha vehiculo
- `detail_vehicles`: detalle tecnico / notas / km
- `orden_trabajos`: cabecera de orden
- `trabajos`: items de trabajo dentro de la orden
- `observaciones` + `observaciones_images`: observaciones con imagenes
- `image_orden_trabajos`: fotos asociadas a orden

### Checklist

El checklist tiene doble estructura:

- plantilla maestra: `check_lists`, `check_list_categorias`, `check_list_intervenciones`
- ejecucion por vehiculo: `check_list_vehicles` y tablas derivadas de categoria,
  condicion, intervencion, observacion e imagen

### Catalogo automotriz

La seleccion de vehiculos se construye por:

- tipo
- marca
- modelo
- ano
- motor

Esto explica la gran cantidad de datos en:

- `vehicle_models`
- `vehicle_years`
- `vehicle_engines`

## Flujo funcional resumido

### Acceso

1. El usuario entra por una URL personalizada
2. Llega a `acceso/{url}`
3. Desde ahi pasa a `login/{url}`
4. Si autentica, entra al portal
5. El menu mostrado depende de permisos

### Operacion comercial

1. Se crean clientes o empresas/proveedores
2. Se cargan productos
3. Se administra inventario, utilidad, descuentos, tipos de pago y flete
4. Se generan cotizaciones
5. Las cotizaciones pueden derivar en venta o envio
6. Las ventas generan registros y eventualmente boletas/recibos/PDF

### Operacion de taller

1. Se registra vehiculo
2. Puede asociarse a mecanico
3. Se abre orden de trabajo
4. Se registran trabajos, observaciones y fotos
5. Se puede ejecutar checklist estructurado por categorias/intervenciones

## Correspondencia codigo <-> BD

### Controladores que concentran negocio

- `User/UserController.php`
- `VehicleController.php`
- `VehicleBrandController.php`
- `VehicleModelController.php`
- `VehicleEngineController.php`
- `VehiculoTipoController.php`
- `OrdenTrabajoController.php`
- `CheckListController.php`
- `QuotationclientController.php`
- `QuotationShippingController.php`
- `ProductController.php`
- `InventoryController.php`
- `SaleController.php`
- `BillController.php`
- `ImportController.php`

### Componentes Vue relevantes

- `components/Client`
- `components/Product`
- `components/Inventario`
- `components/Quotationclient`
- `components/Quotationuser`
- `components/QuotationShipping`
- `components/Sales`
- `components/OrdenTrabajos`
- `components/Check-List`
- `components/Vehicle`
- `components/VehicleMechanic`
- `components/VehicleBrand`
- `components/VehicleModel`
- `components/User`
- `components/UserMechanic`
- `components/Roles`
- `components/Utilidad`
- `components/Boleta`

## Hallazgos importantes

### 1. El repo parece copia operativa, no fuente limpia

Trae `vendor` y `node_modules` incluidos.

### 2. La BD manda mas que las migraciones

Para mantener o migrar este proyecto, el dump SQL es parte critica del conocimiento.
Sin eso, el repo queda incompleto.

### 3. Hay logica de negocio incrustada en rutas y controladores

`routes/web.php` esta muy cargado y mezcla:

- endpoints CRUD
- vistas
- acciones operativas
- utilidades puntuales

### 4. Integraciones operativas embebidas

Existe generacion de boletas con llamada directa a Haulmer desde:

- `services/InvoiceGenerateService.php`

### 5. Hay herencia / residuos tecnicos

Aparecen nombres y tablas que sugieren capas historicas o integraciones anteriores:

- `shopsolver`
- middleware `CheckIpAccess` presente pero no registrado como route middleware
- duplicidad de `Route::ApiResource('companies', ...)` en `routes/web.php`

## Riesgos visibles

1. El endpoint `/storage-link` crea symlink desde ruta web. Conviene revisarlo.
2. Credenciales o datos sensibles operativos parecen estar embebidos en codigo.
3. La ausencia de migraciones reales hace dificil reproducir entornos.
4. Tests y seeders practicamente no documentan comportamiento.
5. Hay texto con problemas de codificacion en varias vistas/controladores.

## Qué conviene hacer despues

### Prioridad alta

1. Exportar y resguardar la BD como referencia canonica
2. Levantar un mapa ER basico de tablas clave
3. Revisar `.env` y conexion real esperada
4. Identificar modulos activos vs heredados

### Prioridad media

1. Documentar estados de `quotationclients`
2. Documentar flujo exacto de ordenes de trabajo y checklist
3. Revisar flujo completo de ventas -> boletas -> PDF
4. Revisar permisos/roles reales cargados en la BD

## Resumen ejecutivo

SupraApp es un sistema Laravel + Vue de gestion comercial y taller automotriz,
con fuerte dependencia en la base de datos historica. El conocimiento funcional
real no estaba en los `.md` del repo sino en:

- `routes/web.php`
- componentes Vue
- controladores
- dump `supra_suprapp_2024.sql`

Este documento queda como base de continuidad para futuras sesiones.
