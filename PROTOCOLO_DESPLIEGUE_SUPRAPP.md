# Protocolo de Despliegue SupraApp

## Objetivo

Evitar que al trabajar localmente se suban al servidor:

- credenciales locales
- configuraciones de XAMPP
- errores por mezclar `.env`
- archivos innecesarios o pesados

## Regla principal

**Se sube codigo. No se sube `.env`.**

## Confirmacion importante

Este repo ya tiene `.env` ignorado en `.gitignore`.

Linea relevante:

- `.env`

Eso protege el archivo en flujos normales con git, pero igual hay riesgo si haces
copia manual de carpetas o subes archivos completos desde cPanel/File Manager.

## Qué SI se puede subir

Normalmente:

- `app/`
- `config/` si el cambio lo requiere
- `resources/`
- `routes/`
- `services/`
- `public/` cuando cambian assets compilados o archivos publicos
- vistas Blade
- controladores
- modelos
- componentes Vue
- CSS/JS compilado si corresponde

## Qué NO se debe subir

Nunca subir desde local a produccion:

- `.env`
- `supra_suprapp_2024.sql`
- `node_modules/`
- `vendor/` salvo caso excepcional y controlado
- caches locales
- archivos de prueba
- respaldos temporales
- cualquier credencial local

## Diferencia entre local y produccion

### Local

Ejemplo actual:

- `DB_HOST=127.0.0.1`
- `DB_DATABASE=supra_suprapp_2024`
- `DB_USERNAME=root`
- `DB_PASSWORD=`

### Produccion

Debe quedar con sus credenciales reales de cPanel/hosting.

**Nunca reemplazar el `.env` del servidor con el `.env` local.**

## Flujo recomendado para subir cambios

1. hacer cambio local
2. probar localmente
3. identificar solo los archivos modificados
4. subir solo esos archivos al servidor
5. no tocar `.env` del servidor
6. verificar funcionamiento en produccion

## Si el cambio toca frontend

Si cambias:

- Vue
- JS
- CSS
- Mix/Webpack

entonces puede que necesites subir tambien:

- `public/js/...`
- `public/css/...`

porque en Laravel muchas veces el navegador consume lo compilado en `public`,
no solo el fuente de `resources`.

## Si el cambio toca configuracion

Si modificas:

- `config/*.php`
- providers
- aliases
- servicios que dependan de config

en servidor puede requerirse limpiar cache/config cache.

Pero eso se hace **en servidor**, no copiando tu `.env` local.

## Si el cambio toca base de datos

Antes de subir:

1. confirmar si requiere SQL
2. preparar script SQL separado
3. ejecutar el SQL en entorno controlado
4. luego subir el codigo

No mezclar:

- cambio de codigo
- cambio manual improvisado de BD
- cambio accidental de `.env`

## Checklist corto antes de subir

- [ ] probe localmente
- [ ] no voy a subir `.env`
- [ ] no voy a subir `node_modules`
- [ ] no voy a subir el SQL de respaldo
- [ ] identifique exactamente los archivos modificados
- [ ] si cambie frontend, subi tambien assets compilados
- [ ] si cambie BD, tengo claro el SQL o ajuste necesario

## Regla operativa para este proyecto

Cuando trabajemos mejoras en SupraApp:

- yo hago cambios pensando en local
- tu subes solo los archivos necesarios
- el `.env` del servidor no se toca salvo decision explicita

## Cierre

Si en algun momento quieres desplegar un cambio, la forma segura de pedirmelo es:

- "haz el cambio pero dejamelo listo para subir a cPanel"
- "dime exactamente que archivos subir"
- "este cambio requiere SQL o no"

Asi evitamos errores por despliegue y no mezclamos configuracion local con produccion.
