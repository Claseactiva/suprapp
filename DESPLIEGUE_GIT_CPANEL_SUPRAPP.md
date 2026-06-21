# Despliegue Git cPanel SupraApp

## Estado validado

Este proyecto ya quedo probado con este flujo:

- repo canonico en GitHub: `Claseactiva/suprapp`
- repo clonado en cPanel fuera de la carpeta live
- deploy automatico por `.cpanel.yml`
- despliegue en **2 fases**, porque una sola corrida grande se colgaba

## Rutas usadas

- repo clonado en cPanel: `/home/supra/suprapp_repo`
- sitio live: `/home/supra/suprapp.comercialsupra.cl/`
- carpeta de respaldo manual usada durante la prueba: `supraa_old`
- carpeta de prueba temporal: `/home/supra/suprapp_deploy_test/`

## Regla importante

No clonar el repo directo sobre la carpeta live.

El esquema correcto es:

1. GitHub
2. cPanel clona a `/home/supra/suprapp_repo`
3. `.cpanel.yml` copia hacia `/home/supra/suprapp.comercialsupra.cl/`

## Comportamiento real del hosting

En esta cuenta de cPanel:

- `Update from Remote` funciona
- `Deploy HEAD Commit` funciona
- copiar archivos sueltos funciona
- copiar carpetas individuales funciona
- copiar demasiadas rutas en una sola corrida se queda pensando

Por eso el deploy estable se hace en 2 fases.

## Fase 1

Copiar:

- `app`
- `bootstrap`
- `config`
- `database`
- `.htaccess`
- `.user.ini`
- `artisan`
- `composer.json`
- `composer.lock`
- `php.ini`
- `server.php`
- `README.md`

## Fase 2

Copiar:

- `public`
- `resources`
- `routes`
- `services`
- `package.json`
- `package-lock.json`
- `phpunit.xml`
- `webpack.mix.js`
- `MD_MAESTRO_SUPRAPP.md`
- `MAPA_TECNICO_SUPRAPP.md`
- `PROTOCOLO_DESPLIEGUE_SUPRAPP.md`

## Cosas que NO se suben desde este repo

- `.env`
- `vendor`
- `node_modules`
- `supra_suprapp_2024.sql`
- `storage/app/public/images`

## Orden recomendado de despliegue

1. respaldar carpeta live si el cambio es sensible
2. hacer push a GitHub
3. en cPanel hacer `Update from Remote`
4. ejecutar fase 1
5. cambiar `.cpanel.yml` a fase 2
6. hacer `Update from Remote`
7. ejecutar fase 2
8. probar login, cotizaciones, productos y assets

## SQL que hubo que aplicar en produccion

El deploy de archivos no basto. Produccion quedo con codigo nuevo y BD vieja.

Se aplicaron estos cambios de esquema:

```sql
ALTER TABLE quotationclients
ADD COLUMN telefono TEXT NULL AFTER url;

ALTER TABLE quotationclients
ADD COLUMN vehicle_model_id BIGINT UNSIGNED NULL AFTER vehicle;

CREATE INDEX quotationclients_vehicle_model_id_index
ON quotationclients (vehicle_model_id);

CREATE TABLE delivery_times (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  label VARCHAR(255) NOT NULL UNIQUE,
  is_default TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL
);

INSERT INTO delivery_times (label, is_default, created_at, updated_at)
VALUES ('24 a 48 Hrs', 1, NOW(), NOW());

CREATE TABLE vehicle_model_products (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  vehicle_model_id BIGINT UNSIGNED NOT NULL,
  quotationclient_id BIGINT UNSIGNED NULL,
  detailclient_id BIGINT UNSIGNED NOT NULL UNIQUE,
  product_name VARCHAR(255) NOT NULL,
  product_key VARCHAR(255) NOT NULL,
  product_code VARCHAR(255) NULL,
  source VARCHAR(20) NOT NULL DEFAULT 'live',
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX vehicle_model_products_vehicle_model_id_index (vehicle_model_id),
  INDEX vehicle_model_products_quotationclient_id_index (quotationclient_id),
  INDEX vehicle_model_products_vehicle_model_id_product_key_index (vehicle_model_id, product_key)
);

ALTER TABLE vehicle_model_products
ADD COLUMN user_id BIGINT UNSIGNED NULL AFTER vehicle_model_id,
ADD COLUMN product_id BIGINT UNSIGNED NULL AFTER detailclient_id;

CREATE INDEX vehicle_model_products_user_id_index
ON vehicle_model_products (user_id);

CREATE INDEX vehicle_model_products_product_id_index
ON vehicle_model_products (product_id);

CREATE INDEX vmp_user_model_product_idx
ON vehicle_model_products (user_id, vehicle_model_id, product_id);

CREATE TABLE product_vehicle_models (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  product_id BIGINT UNSIGNED NOT NULL,
  vehicle_model_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  UNIQUE KEY pvm_product_vehicle_unique (product_id, vehicle_model_id),
  KEY pvm_user_vehicle_idx (user_id, vehicle_model_id),
  KEY product_vehicle_models_product_id_index (product_id)
);
```

## Error clave detectado

El primer error real en produccion fue:

- `Unknown column 'quotationclients.telefono'`

Ese error explicaba por que no cargaban las cotizaciones formales no-express.

## Rollback

Si algo sale mal:

1. no borrar `supraa_old`
2. restaurar la carpeta buena anterior
3. revisar `.env`, `vendor` e imagenes

## Nota final

Para este proyecto, subir codigo sin revisar BD puede dejar modulos con `500`.
En cambios de cotizaciones/productos, revisar siempre si produccion necesita SQL acompanante.
