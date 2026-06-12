# Mapa Tecnico SupraApp

## Objetivo de este documento

Este archivo existe para acelerar futuras asistencias y mejoras sobre SupraApp.
La idea es que sirva como mapa tecnico estable: modulos, tablas, relaciones,
estados, puntos de entrada y criterios para cambios.

Se construyo cruzando:

- `routes/web.php`
- controladores Laravel
- componentes Vue
- `supra_suprapp_2024.sql`

## Resumen tecnico rapido

- Framework: Laravel 8
- Frontend: Vue 2 sobre Blade
- Rutas de datos: principalmente en `routes/web.php`
- Autorizacion: `spatie/laravel-permission`
- Base de datos real: representada por `supra_suprapp_2024.sql`
- Patrón UI: Blade monta componentes Vue por modulo

## Modulos prioritarios para mantenimiento

Estos son los modulos donde hoy conviene pensar el sistema:

1. Cotizaciones formales
2. Envios
3. Vehiculos
4. Ordenes de trabajo
5. Check list
6. Ventas
7. Catalogo automotriz
8. Usuarios, roles y relacion mecanico-cliente

## Mapa de capas

### Capa 1: Navegacion y pantallas

Las vistas administrativas montan componentes Vue:

- `admin-clientes` -> `clients-component`
- `admin-productos` -> `product-component`
- `admin-inventario` -> `inventario-component`
- `admin-vehiculos` -> `vehicle-component`
- `admin-vehiculosM` -> `vehicle-mechanic-component`
- `admin-orden-trabajos` -> `ordentrabajos-component`
- `admin-check-list` -> `checklist-component`
- `admin-ventas` -> `sales-component`
- `admin-cotizaciones-formales` -> `quotationsclient-component`
- `admin-envios` -> `envios-component`
- `admin-utilidad` -> `utilidad-component`
- `admin-importaciones` -> `imports-component`

Registro central:

- `resources/assets/js/app.js`

### Capa 2: Endpoints de negocio

Los endpoints no viven en `api.php`, sino casi todos en `routes/web.php`.
Eso implica que los cambios funcionales suelen tocar tres lugares:

1. ruta
2. controlador
3. componente Vue

### Capa 3: Base de datos

Las tablas reales del negocio no estan en migraciones del repo. Para cambios
importantes hay que considerar siempre el dump SQL como fuente de verdad.

## Modulo: Cotizaciones formales

### Tablas principales

- `quotationclients`
- `detailclients`
- `clients`
- `companies`
- `quotation_users`
- `quotation_user_vehicles`

### Campos importantes en `quotationclients`

Segun el dump:

- `user_id`: dueño/comercial que genera la cotizacion
- `client_id`: cliente interno asociado
- `correlativo`: numeracion por usuario
- `payment`: forma de pago visible
- `client_text`: nombre visible del cliente
- `vehicle`: descripcion vehiculo
- `state`: estado textual, por ejemplo `Pendiente`
- `generado`: bandera operativa importante
- `generado_client`: bandera ligada a creacion posterior de cliente/usuario
- `tipo_detalle`: tipo de detalle/cotizacion
- `url`: campo libre usado en algunos flujos
- `ppu`: patente / identificador asociado
- `spare_parts`: observacion de repuestos

### Estado y banderas inferidas

Desde `QuotationclientController.php`:

- al crear, `state` se inicializa en `Pendiente`
- al crear, `payment` se inicializa en `Contado`
- `generado` se usa para separar listas y flujos

Inferencia operativa:

- `generado != 3` y `generado != 5`: cotizaciones activas en listado principal
- `generado == 3` o `generado == 5`: cotizaciones que pasan a listado de formularios

Esto sale de:

- `index()`: excluye `3` y `5`
- `clientsform()`: incluye `3` y `5`

### Logica de creacion

Controlador: `app/Http/Controllers/QuotationclientController.php`

Puntos relevantes:

- el `correlativo` se calcula por `user_id`, excepto comportamiento especial de usuario `1`
- si el usuario tiene cierto rol y viene `cliente_part`, fuerza:
  - `generado = 1`
  - `tipo_detalle = 1`
- si `client_id == 1`, tambien deja `generado = 1`
- en otro caso usa `generado = 2`

### Detalle de cotizacion

`detailclients` modela el detalle por item:

- `product`
- `detail`
- `price`
- `quantity`
- `percentage`
- `aditional`
- `transport`
- `utility`
- `total`
- `days`

Esto indica que el precio final no es simple: mezcla costo base, margen,
adicionales, transporte y tiempo de entrega.

### PDF

Las cotizaciones salen por:

- `quotationclient-pdf/{id}`
- `quotationclient-pdf-iva/{id}`

Vistas:

- `resources/views/pdf/quotationclient.blade.php`
- `resources/views/pdf/quotationclientiva.blade.php`

## Modulo: Envios

### Tablas principales

- `quotation_shippings`
- `towns`

### Campos clave de `quotation_shippings`

- `user_id`
- `nombre`
- `rut`
- `telefono`
- `ciudad`
- `direccion`
- `sucursal`
- `url`
- `enviado`

### Logica funcional

Controlador: `QuotationShippingController.php`

Funciones importantes:

- `index()`: lista envios con nombre de ciudad
- `store()`: crea envio
- `checkEnviado()`: marca `enviado = 1`
- `NocheckEnviado()`: marca `enviado = 0`
- `pdf($id)`: genera comprobante PDF

### Interpretacion

El modulo parece ser un registro/log de solicitudes de envio o despacho,
con posibilidad de marcar si ya fue efectivamente enviado.

## Modulo: Vehiculos

### Tablas principales

- `vehicles`
- `detail_vehicles`
- `images`
- `mechanic_client`

### Campos clave de `vehicles`

- `user_id`
- `patent`
- `chasis`
- `brand`
- `model`
- `year`
- `engine`
- `color`
- `km`
- `mechanic`

### Comportamiento

Controlador: `VehicleController.php`

Hay dos flujos:

1. vehiculos propios del usuario
2. vehiculos de clientes de un mecanico

### Regla de capacidad

El sistema limita creacion por `cant_vehicle`.

Hay dos interpretaciones activas del campo:

- cupo de vehiculos por usuario
- en algunos flujos tambien cupo total administrado por mecanico

### Observacion importante

En BD aparecen campos como `cant_client`, `mac`, etc. en `users`,
pero el modelo `App\User` no refleja todo el esquema real. Hay deriva
entre modelo y BD. Conviene considerar eso antes de refactorizar.

## Modulo: Ordenes de trabajo

### Tablas principales

- `orden_trabajos`
- `trabajos`
- `image_orden_trabajos`
- `observaciones`
- `observaciones_images`

### Estructura funcional

`orden_trabajos` es cabecera por vehiculo:

- `user_id`
- `vehicle_id`
- `km`

`trabajos` son los items dentro de la orden:

- `orden_trabajo_id`
- `descripcion`
- `km`
- `realizado`

### Regla importante

El controlador no crea muchas cabeceras por vehiculo en uso normal.
Cuando agregas un trabajo:

- busca orden existente por `vehicle_id`
- si existe, reutiliza la primera
- si no existe, crea una nueva

Eso significa que el modulo funciona mas como historial de trabajos
sobre una orden/cabecera viva por vehiculo, no como orden independiente
por visita en todos los casos.

### Estado de `trabajos.realizado`

- `0`: pendiente
- `1`: realizado

Acciones:

- `checkRealizado()`
- `NocheckRealizado()`

### Imagenes y observaciones

El trabajo puede tener:

- fotos directas (`image_orden_trabajos`)
- observaciones con imagenes (`observaciones`, `observaciones_images`)

Las imagenes se guardan en:

- `storage/app/public/images/orden_trabajos/`
- `storage/app/public/images/observaciones/`

## Modulo: Check List

### Modelo conceptual

Tiene dos capas:

1. plantilla maestra
2. ejecucion sobre vehiculo

### Plantilla maestra

Tablas:

- `check_lists`
- `check_list_categorias`
- `check_list_intervenciones`

### Ejecucion por vehiculo

Tablas:

- `check_list_vehicles`
- `check_list_vehicle_categorias`
- `check_list_vehicle_intervenciones`
- `check_list_vehicle_condiciones`
- `check_list_vehicle_observaciones`
- `check_list_vehicle_observaciones_images`

### Flujo real

Cuando se pide checklist para un vehiculo:

- si ya existe `check_list_vehicles`, lo reutiliza
- si no existe, clona la plantilla actual del usuario

Eso ocurre en `mostrarCheckList($id)`.

### Campo `realizado`

En `check_list_vehicles.realizado`:

- `0`: checklist creado pero no guardado como completado
- `1`: checklist guardado/completado

Se marca en `guardarCheckListVehicle()`.

### Condiciones por intervencion

La tabla `check_list_vehicle_condiciones` guarda:

- `existe`
- `estado`

Lectura operativa:

- `existe = no` vacia el `estado`
- si existe, ademas guarda un `estado`

### Observaciones del checklist

Cada intervencion puede recibir:

- texto de observacion
- imagenes asociadas

Las imagenes se guardan en:

- `storage/app/public/images/checklist/`

## Modulo: Ventas

### Tablas principales

- `sales`
- `product_sale`
- `inventories`
- `products`
- `clients`
- `boletas`

### Estructura

`sales`:

- cabecera de venta
- `user_id`
- `client_id`
- `total`
- `forma_pago`
- `descuento`

`product_sale`:

- productos incluidos en la venta
- `sale_id`
- `product_id`
- `quantity`
- `neto`
- `total`
- `utility`

### Regla de inventario

En `SaleController::sale()`:

- crea venta
- crea filas en `product_sale`
- descuenta stock desde `inventories` en orden ascendente por id
- incrementa el campo `discount`

Eso sugiere una logica tipo consumo FIFO simplificado.

### Anulacion

En `anularSale($id)`:

- intenta revertir descuentos de inventario
- elimina `product_sale`
- elimina la cabecera `sales`

Conviene tratar este flujo con cuidado si se toca, porque la reversion
de stock es sensible.

## Modulo: Catalogo automotriz

### Tablas principales

- `vehicle_brands`
- `vehicle_models`
- `vehicle_years`
- `vehicle_engines`
- `vehicle_tipos`
- `vehicle_brand_models`

### Cadena logica

La combinacion mas estructurada es:

1. tipo de vehiculo
2. marca
3. modelo
4. año
5. motor

Relaciones visibles por esquema:

- `vehicle_models.brand_id` -> marca
- `vehicle_models.tipo_id` -> tipo
- `vehicle_years.v_id` -> modelo
- `vehicle_engines.year_id` -> año/modelo

### Nota

Hay coexistencia de dos familias parecidas:

- `vehicle_brand_models`
- `vehicle_models`

Antes de unificar o limpiar, conviene revisar uso real en Vue y controladores.

## Modulo: Usuarios, roles y mecanicos

### Tablas principales

- `users`
- `roles`
- `permissions`
- `model_has_roles`
- `role_has_permissions`
- `mechanic_client`

### Rol mecanico-cliente

`mechanic_client` modela una relacion:

- `user_id`: cliente/usuario subordinado
- `mechanic_id`: mecanico responsable

Eso impacta:

- visibilidad de vehiculos
- cupos
- creacion de clientes de mecanico

### Banderas de capacidad

En `users` aparecen:

- `cant_vehicle`
- `cant_client` (en la BD)

El sistema usa esos valores como cupos comerciales/operativos.

### Vinculo con cotizaciones

En `UserController` se ve que al crear ciertos usuarios desde cotizacion
se actualiza:

- `quotationclients.generado_client = 1`

Eso sugiere que una cotizacion puede terminar creando o vinculando un cliente
formal dentro del sistema.

## Estados y banderas inferidos

### `quotationclients.generado`

Significado exacto no esta documentado, pero por comportamiento:

- `1`: cotizacion simple o particular
- `2`: cotizacion asociada a cliente distinto de caso base
- `3` y `5`: cotizaciones movidas al listado `clientsform()`

Esto hay que tratarlo como contrato historico del sistema.

### `quotationclients.generado_client`

Bandera asociada al flujo de convertir cotizacion en cliente/usuario o
marcarla como ya procesada en ese aspecto.

### `quotationclients.state`

Se inicializa en `Pendiente`. No se vio un mapa completo de estados en este
barrido, así que cualquier cambio debe revisar UI y datos existentes primero.

### `trabajos.realizado`

- `0`: pendiente
- `1`: realizado

### `check_list_vehicles.realizado`

- `0`: checklist abierto/no guardado como finalizado
- `1`: checklist guardado

### `quotation_shippings.enviado`

- `0`: no enviado
- `1`: enviado

## Puntos delicados para cambios

1. `routes/web.php` esta muy cargado y mezcla muchas responsabilidades.
2. El esquema BD real no coincide del todo con modelos/migraciones.
3. El modulo de ventas modifica inventario directamente.
4. Ordenes de trabajo y checklist escriben imagenes en storage y luego
   dependen de rutas/links para visualizacion.
5. Hay comportamiento especial por usuario `1` y por ids de roles.

## Cómo conviene pedirme asistencia

Para que yo pueda ayudarte rapido y sin reexplorar todo, conviene pedirlo así:

### Formato recomendado

- modulo
- pantalla
- comportamiento actual
- comportamiento esperado
- si afecta BD, PDF, Vue o rutas

### Ejemplos buenos

- "En cotizaciones formales, al guardar una cotizacion con cliente particular,
  quiero que `payment` no quede fijo en `Contado`."
- "En ordenes de trabajo, quiero permitir multiples cabeceras por vehiculo,
  una por visita."
- "En ventas, revisa si la anulacion devuelve mal el stock cuando hay varias
  filas en inventario para el mismo producto."
- "En checklist, quiero que `realizado` quede en 0 hasta que el usuario confirme."

### Si quieres diagnostico antes de tocar

Dime explícitamente:

- "solo analiza"
- "diagnostica primero"
- "no hagas codigo aun"

## Estrategia recomendada para mejoras

### Cambios pequeños

Tocar solo:

- componente Vue puntual
- endpoint puntual
- tabla/campo puntual

### Cambios medianos

Cruzar siempre:

- componente Vue
- controlador
- tabla real en SQL
- vista PDF si aplica

### Cambios grandes

Antes de tocar:

1. confirmar modulo exacto
2. revisar datos reales del dump
3. revisar si el comportamiento depende de banderas historicas
4. identificar si hay usuario admin o mecanico con reglas especiales

## Pendientes tecnicos que conviene documentar despues

1. mapa exacto de roles y permisos del dump
2. mapa exacto de valores usados en `generado`, `generado_client` y `state`
3. flujo de boletas completo con Haulmer
4. flujo de importaciones
5. uso real de tablas `shopsolver`

## Cierre

Con este archivo y `MD_MAESTRO_SUPRAPP.md` ya hay una base suficiente para
trabajar mejoras de forma bastante mas segura. A partir de ahora, el proyecto
ya no depende solo de "leer todo de nuevo": tenemos un mapa funcional y uno
tecnico para retomar.
