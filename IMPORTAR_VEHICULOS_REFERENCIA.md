# Importar nuevos meses a vehicle_reference_data

## Qué es esto

Tabla `vehicle_reference_data` en MySQL (local y producción), cargada desde los
Excel mensuales `SGPRT_RB_<mes>-2026.xlsx`. Se usa en el formulario "Registrar
Vehículo" (`Agregar.vue`) para autocompletar Chasis, N° de Motor, Marca, Modelo,
Año y Cilindrada al escribir una patente (endpoint `vehiculo-referencia/{patente}`,
protegido con login).

Estado al 18/08/2026: cargados abril, mayo y junio 2026 (1.617.470 filas) tanto
en local como en producción.

## Por qué el proceso tiene dos partes (local + servidor)

El hosting de producción tiene límites que no aguantan procesar el Excel
directo (`memory_limit 32M`, `max_execution_time 30s`, sin SSH). Por eso:

- El Excel (170MB+, XML pesado) **solo se procesa en la máquina local**, sin esas
  restricciones.
- Producción **nunca toca el Excel**, solo recibe un dump SQL ya limpio, que es
  liviano y rápido de importar (sin pasar por PHP en absoluto).

## Pasos para agregar un mes nuevo

### 1. Local: procesar el Excel

Pendiente: convertir `scratch_import_excel.php` en un comando Artisan propio
(`php artisan vehiculos:importar archivo.xlsx 2026-07`) que inserte directo en
la tabla MySQL local `vehicle_reference_data` (sin pasar por la SQLite
intermedia que se usó la primera vez, solo para probar). Si todavía no existe
ese comando, revisar `scratch_import_excel.php` (importa a SQLite) +
`scratch_copy_to_mysql.php` (copia esa SQLite a MySQL local) como referencia
del proceso que hay que reproducir/adaptar.

Verificar que no se repita un `periodo` ya cargado:
```sql
SELECT periodo, COUNT(*) FROM vehicle_reference_data GROUP BY periodo;
```

### 2. Local: generar el dump SOLO del mes nuevo

```
mysqldump -u root supra_suprapp_2024 vehicle_reference_data \
  --where="periodo='2026-07'" --no-tablespaces \
  | gzip -9 > vehicle_reference_data_2026-07.sql.gz
```

(Ajustar usuario/BD según tu `.env` local. Solo el mes nuevo, no toda la tabla
de nuevo — mucho más chico y rápido.)

### 3. Subir el dump por FTP

Subir el `.sql.gz` a `storage/app/backups/` en el servidor (carpeta ya
existente, no pública).

### 4. Cron Job (una sola ejecución)

En cPanel → Trabajos de cron, con hora/minuto específicos (no `*`, para que
corra una sola vez):

```
gunzip -c /home/supra/suprapp.comercialsupra.cl/storage/app/backups/vehicle_reference_data_2026-07.sql.gz | mysql supra_suprapp_2024
```

El archivo `~/.my.cnf` en la raíz de la cuenta (creado el 18/08/2026, con las
credenciales de BD de producción) ya existe y se dejó a propósito para
reutilizarlo cada mes — por eso el comando no necesita `-u`/`-p`.

### 5. Verificar y limpiar

- Confirmar en phpMyAdmin: `SELECT periodo, COUNT(*) FROM vehicle_reference_data GROUP BY periodo;`
- Borrar el Cron Job (ya cumplió su función).
- Opcional: borrar el `.sql.gz` subido del servidor.

**No hace falta repetir:** la migración de la tabla (ya existe), la ruta
temporal `deploy-migrate-...` (ya se usó y se borró del código), ni crear
`.my.cnf` de nuevo.

## Archivos relevantes

- `app/Http/Controllers/VehicleReferenceDataController.php` — lógica de
  matching marca/modelo (determinista, sin similitud de texto — ver por qué en
  memoria `feedback_conservative_heuristic_matching`).
- `app/Models/VehicleReferenceData.php`
- `resources/assets/js/components/Vehicle/Agregar.vue` — autocompletado en el
  formulario (recordar `npm run prod` después de tocarlo, y que `npm run prod`
  del `package.json` está roto por un flag `--no-progress` obsoleto de
  webpack-cli; correr manual: `NODE_ENV=production node_modules/webpack/bin/webpack.js --config=node_modules/laravel-mix/setup/webpack.config.js`).
