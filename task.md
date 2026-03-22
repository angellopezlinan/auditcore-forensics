# Aicor UAS - Next Steps
- [x] Planificar "Sistema de gestión de pilotos y licencias"
- [x] Añadir modelos y migraciones para Pilotos
- [x] Crear controladores y endpoints para Pilotos
- [x] Implementar Autenticación y Seguridad (Sanctum)
- [x] Generación automática de PDFs para AESA
- [x] Implementar CI/CD con GitHub Actions
- [x] Validación del Espacio Aéreo (Airspace Check - ENAIRE)
- [x] Implementar Roles y Operadores (Normativa NO EASA con Spatie)
- [x] Documentación Interactiva de la API (Scribe)

## Fase 2: Centro de Mando Web (Dashboard)
- [x] Instalar Filament PHP y panel base
- [x] Configurar Branding y Tema de Aicor (Color, Fuente y Modo Oscuro)
- [x] Generar y configurar el recurso `FlightResource` para visualizar vuelos
- [x] Comprobar funcionamiento y subir a Git

## Fase 2 (Part 2): Gestión de la Flota (Hangar)
- [x] Crear migración para actualizar campos en `drones` (brand, weight_grams, registration_mark)
- [x] Actualizar modelo `Drone` (fillable)
- [x] Generar y configurar `DroneResource` (Formulario, Tabla con Badges, Filtros)
- [x] Actualizar `FlightResource` para incluir un Select relacional de `drone_id`
- [x] Migrar y comprobar funcionamiento
## Fase 2 (Part 3): Gestión de Personal Operativo
- [x] Crear migración y modelo para tabla `licenses` (user_id, type, identifier, expiry_date, certificate_path)
- [x] Conectar relación `hasMany` de licencias en el modelo `User`
- [x] Generar y configurar `UserResource` en Filament (CRUD de agentes y selector de Rol de Spatie)
- [x] Crear e integrar `LicensesRelationManager` en `UserResource` para gestionar expediente
- [x] Aplicar Multi-Tenancy (Scoping) en `UserResource` para aislar usuarios por organización
- [x] Migrar y comprobar

## Fase 3: Dashboard Widgets
- [x] Construir y configurar `StatsOverviewWidget` (Vuelos, Flota y Personal con iconos y Scoping)
- [x] Construir y configurar `LatestFlightsWidget` (Tabla de 5 últimos vuelos sin paginación, con Scoping)
- [ ] Comprobar renderizado en pantalla de Inicio

## Fase 4: Despacho y Planificación de Vuelos
- [x] Crear migración para `flights` (status, location_name, enaire_pdf_path, user_id)
- [x] Actualizar modelo `Flight` con fillable y relación `user`
- [x] Reestructurar formulario de `FlightResource` en secciones
- [x] Aplicar Multi-Tenancy en los selects de Dron y Usuario en `FlightResource`
- [x] Integrar distintivos visuales (Badges) y descarga de PDF ENAIRE en tabla
- [x] Migrar y probar interfaz en `/admin/flights`

## Fase 4 (Part 2): Inteligencia ENAIRE
- [x] Crear migración para `enaire_json_path` y `enaire_warnings`
- [x] Actualizar modelo `Flight` con fillables
- [x] Implementar FileUpload de JSON en `FlightResource` con `live()` y `afterStateUpdated`
- [x] Extraer advertencias del JSON y autorellenar el Textarea
- [x] Añadir Action de descarga de PDF en la tabla
- [x] Migrar y probar

## Fase 5: Parte Oficial de Vuelo (PDF)
- [x] Instalar `barryvdh/laravel-dompdf`
- [x] Crear plantilla Blade `resources/views/pdf/flight-report.blade.php` con logotipo en base64 y marca de agua
- [x] Aplicar diseño CSS institucional y agrupar datos de trazabilidad y operacionales en el Blade
- [x] Añadir Action `imprimir_parte` a la tabla del `FlightResource` para renderizar y descargar el PDF
- [x] Probar generación del Parte de Vuelo

## Fase 6: Mejoras de UX en Tiempos de Vuelo
- [x] Deshabilitar input nativo del navegador en `start_time` y `end_time` a favor de Filament DateTimePicker
- [x] Calcular dinámicamente `duration_minutes` a través del hook `afterStateUpdated`
- [x] Limpiar cache de vistas `php artisan view:clear`
- [x] Resolver 403 Forbidden para evidencias PDF ENAIRE mediante `storage:link` y disco público
- [x] Aplicar fuerza bruta a CSS DomPDF para aislar renderizado del Anexo ENAIRE
- [x] Establecer URL de redireccionamiento al crear el vuelo
- [x] Injectar atributo HTML5 `novalidate` en formulario Filament para solventar errores de foco en botones
- [x] Sustituir atributos nativos required() por validación de backend rules(['required']) para evitar bloqueos UI
- [x] Eliminar `minutesStep(5)` de `start_time` y `end_time` para remover validación oculta HTML5 en Chrome

## Fase 7: Sistema de Rangos y Multitenancy Básico
- [x] Crear migración para añadir columna `role` a la tabla `users` (por defecto `piloto`)
- [x] Ascender a todos los usuarios actuales a `admin` mediante query en tinker
- [x] Añadir atributo `role` al fillable del modelo `User`
- [x] Securizar `FlightResource` blindando el Query Builder (solo el admin ve todos los vuelos)
- [x] Bloquear input de selección de Piloto en base a privilegios de administrador

## Fase 8: Instalación de Filament Shield (RBAC Visual)
- [x] Instalar paquete `bezhansalleh/filament-shield`
- [x] Registrar plugin `FilamentShieldPlugin` en `AdminPanelProvider`
- [x] Ejecutar instalador y generar migraciones/permisos para los recursos y widgets
- [x] Limpieza de políticas antiguas hardcodeadas en recursos (comprobado)

## Fase 8 (Hotfix): Solución de Permisos y Guardias (Spatie vs Sanctum)
- [x] Revertir `guard_name` en modelo `User` a `web` para coincidir con la sesión de Filament.
- [x] Purgar caché de permisos antiguos de la guardia API por consola.
- [x] Regenerar todas las directivas Shield (Policies y Permissions) al guard por defecto.
- [x] Re-asignar el rol `super_admin` (`guard: web`) al Capitán (`jefe@cordoba.es`) para reactivar el interceptor Gate.

## Fase 9: Castellanización Total
- [x] Configurar la base de la aplicación Laravel a idioma español (`es`) vía archivo `.env`
- [x] Sobrescribir las propiedades (`$modelLabel`, `$navigationLabel`...) en `FlightResource` a "Parte de Vuelo"
- [x] Sobrescribir las propiedades en `DroneResource` a "Hangar de Drones"
- [x] Publicar módulo base de traducción del Escudo (`vendor:publish`)
- [x] Recompilar directivas de Permisos para ingerir el nuevo alias de las clases base y limpiar optimizaciones

## Fase B: Mantenimiento y Aeronavegabilidad
- [x] Tarea 1: Añadir columna `status` (`default='operativo'`) a la tabla `drones`.
- [x] Tarea 2: Crear modelo y migración para `maintenances` (Libro de Mantenimiento).
- [x] Tarea 3: Configurar `$fillable` y relaciones (`hasMany`/`belongsTo`) entre `Drone` y `Maintenance`.
- [x] Tarea 4: Ejecutar migración y notificar a la base de operaciones.

## Fase B (Interfaz): Despliegue del Logbook y Bloqueos de Seguridad
- [x] Módificar `DroneResource` (Añadir selector de 'status' al form y TextColumn/Badge a la tabla).
- [x] Generar y configurar `MaintenanceResource` (Formulario y Columnas básicas).
- [x] Modificar `FlightResource` para filtrar los drones y solo permitir vuelos en aeronaves "operativas".
- [x] Ejecutar `shield:generate` y vaciar caché para activar directivas del Shield publicadas.

## Fase B (UX): Historial en Hangar y Roles Bidireccionales
- [x] Generar e inyectar `MaintenancesRelationManager` en `DroneResource`.
- [x] Configurar columnas, filtros y esquema con etiquetas en español.
- [ ] Modificar `AppServiceProvider` para extender `RoleResource` (Filament Shield) e inyectar asignación bidireccional de agentes.

## Fase C: Arquitectura Multi-Tenant (B2B SaaS)
- [x] Tarea 1: Crear el Modelo `Team` y migración (`$table->string('name')`).
- [x] Tarea 2: Crear migración pivot `team_user` (`team_id`, `user_id`).
- [x] Tarea 3: Modificar tablas existentes (`drones`, `flights`, `maintenances`) para inyectar la foreign key `team_id`.
- [x] Tarea 4: Ejecutar migraciones y reportar al Capitán.

## Fase C: Activar Multi-Tenancy en Filament
- [x] Configurar modelo `User` (Implementar `HasTenants`).
- [x] Configurar modelo `Team` (`$fillable` y relaciones).
- [x] Inyectar `->tenant()` en `AdminPanelProvider`.
- [x] Asignar primer Team al Super Admin vía Tinker.

## Fase C (UX): Registro de Entidades
- [x] Crear la página `RegisterTeam` para el alta de nuevas Jefaturas.
- [x] Vincular el registro activando `->tenantRegistration(...)` en el `AdminPanelProvider`.

## Fase C: Conexión de Modelos y Recursos a Entidades
- [x] Conectar `Drone`, `Flight` y `Maintenance` al modelo `Team`.
- [x] Declarar `$isScopedToTenant = true` en `DroneResource`, `FlightResource` y `MaintenanceResource`.

## Fase C (Auth): Restauración de Privilegios Multi-Tenant
- [x] Inyectar rol `super_admin` al Capitán (`jefe@cordoba.es`) en todos los Teams vía Tinker.
- [x] Parchear `RegisterTeam.php` para otorgar `super_admin` al creador de nuevas entidades.
- [x] Limpiar cachés de Spatie y optimizar sistema.

## Fase C (Auth): Middleware de Sincronización Tenant
- [x] Crear el middleware `SyncSpatieTenant` para inyectar el ID activo en Spatie.
- [x] Registrar `->tenantMiddleware` en el `AdminPanelProvider`.

## Fase C (Auth): Modelo Custom de Roles para Tenancy
- [x] Crear el modelo `app/Models/Role.php` con la relación `team()`.
- [x] Configurar `config/permission.php` para usar el nuevo modelo.
- [x] Configurar `config/filament-shield.php` para usar el nuevo modelo.
- [x] Optimizar cachés.

## Fase C (Auth): Salvamento de Datos y Configuración B2B de Usuarios
- [x] Rescatar vía Tinker Drones, Vuelos y Mantenimientos huérfanos.
- [x] Añadir `$tenantOwnershipRelationshipName = 'teams'` en `UserResource.php`.

## Fase C (Auth): Blindaje Super Admin y Rescate Final
- [x] Ocultar acciones de edición y borrado para el Capitán en `UserResource.php`.
- [x] Ejecutar script final en Tinker para asegurar rescate de Vuelos a Comandancia Central.
- [x] Limpiar cachés y notificar.

## Fase C (Auth): Manto de Invisibilidad y Migración Fuerza Bruta
- [x] Ocultar globalmente al usuario `jefe@cordoba.es` mediante `getEloquentQuery()` en Filament.
- [x] Migrar Vuelos huérfanos usando el Facade `DB::table()` en Tinker.
- [x] Optimizar cachés.

## Fase C (Auth): Rescate de Roles Antiguos
- [x] Mapear Roles huérfanos a la Comandancia Central vía Tinker.
- [x] Limpiar caché de Spatie Permission y Optimizar sistema.

## Fase C (Auth): Estandarización de Roles y Super Admin Global
- [x] Estandarizar base de Roles en todas las Entidades (Tinker).
- [x] Investir a `jefe@cordoba.es` como Superadmin Global (Tinker).
- [x] Sincronizar permisos totales vía `shield:generate --all`.
- [x] Optimizar cachés.

## Fase C (Auth): Configuración Avanzada de Shield y Rescate de Permisos
- [x] Añadir relación `roles()` en `Team.php`.
- [x] Publicar recursos de Filament Shield.
- [x] Configurar el recurso local `RoleResource.php` para Tenancy.
- [x] Inyectar autoridad (permisos) a los roles `super_admin` de cada Entidad vía Tinker.
- [x] Limpiar cachés y notificar.

## Fase C (Auth): Relación Inversa Equipo-Usuario
- [x] Renombrar/Añadir relación `users()` en el modelo `Team.php`.
- [x] Ajustar `UserResource.php` con `$isScopedToTenant` y `$tenantRelationshipName`.
- [x] Optimizar cachés.

## Fase C (Auth): Doble Relación Many-to-Many Filament
- [x] Inyectar `$tenantOwnershipRelationshipName = 'teams'` y `$tenantRelationshipName = 'users'` en `UserResource.php`.
- [x] Verificar relación `teams()` en `User.php`.
- [x] Optimizar cachés.

## Fase D (Auth): Resolución SQL Error 1364 y Pivot Data
- [x] Inyectar `->pivotData()` en la asignación de roles de `UserResource.php`.
- [x] Optimizar cachés y verificar.

## Fase E: Documentación Final y Cierre
- [x] Actualizar `README.md` con la sección de Arquitectura SaaS B2B.
- [x] Desplegar cambios en GitHub.

## Fase F: Rescate Dinámico de Vuelos y Mantenimiento
- [x] Ejecutar inyección dinámica en Tinker para rescatar `flights`.
- [x] Ejecutar inyección dinámica en Tinker para rescatar `maintenances`.
- [x] Optimizar cachés.

## Fase G: Conexiones Totales de Entidad y Desbloqueo de Vuelos
- [x] Añadir relaciones `flights()`, `drones()`, `maintenances()` en `Team.php`.
- [x] Desbloquear el selector de Piloto en `FlightResource.php` y enrutarlo al Tenant.
- [x] Optimizar cachés y verificar.

## Fase H: Fijación de Radar en Vuelos y Model Fillables
- [x] Inyectar `team_id` en el `$fillable` de `Flight.php`.
- [x] Inyectar `$tenantOwnershipRelationshipName` en `FlightResource.php`.
- [x] Rescatar vuelo huérfano (Tinker).
- [x] Optimizar cachés.

## Fase I: Hook de Mutación para Vuelos
- [x] Interceptar la creación en `CreateFlight.php` inyectando `team_id`.
- [x] Verificar `$fillable` en `Flight.php`.
- [x] Rescatar vuelos fantasmas recientes.
- [x] Optimizar cachés.

## Fase J: Fuga de Datos y Scope de Widgets
- [x] Verificar `$fillable` en `Drone.php` y `Maintenance.php`.
- [x] Aplicar Muro Tenancy al Widget del Dashboard.
- [x] Rescate Definitivo de Huérfanos (Tinker).
- [x] Optimizar cachés.

## Fase K: Alineación Arquitectónica Integral Tenant
- [x] Forzar relación explícita `team()` en `Flight.php`, `Drone.php`, `Maintenance.php`.
- [x] Inyectar Tenancy Scopes en `FlightResource.php`, `DroneResource.php`, `MaintenanceResource.php`.
- [x] Rescatar vuelos fantasmas cruzados (Tinker).
- [x] Optimizar cachés.

## Fase N: Auditoría de Esquema Normativa AESA
- [x] Analizar Entidad DRON (`Drone.php` y `DroneResource.php`).
- [x] Analizar Entidad USUARIO (`User.php` y `UserResource.php`).
- [x] Analizar Entidad VUELO (`Flight.php` y `FlightResource.php`).
- [x] Analizar Entidad MANTENIMIENTO (`Maintenance.php` y `MaintenanceResource.php`).
- [x] Redactar Reporte Final.

## Fase L: Auditoría de Código y Resolución del Índice de Vuelos
- [x] Auditar `FlightResource.php` para eliminar candados legacy.
- [x] Auditar `Flight.php` para GlobalScopes.
- [x] Auditar `FlightPolicy.php`.
- [x] Aplicar fix y reportar al Capitán.

## Fase M: Documentación de Auditoría y Cierre de Jornada
- [x] Actualizar README.md reflejando logros Multi-Tenant purgados de legacy scopes.
- [x] Push a GitHub con reporte documental.

## Fase O: Adaptación a Normativa AESA (MO y Licencias)
- [x] M1: Configurar migración para campos `mo_read_at` y `mo_version` en `users`.
- [x] M2: Añadir campos al `$fillable` de `User.php`.
- [x] M3: Validar tabla `licenses` y modelo `License` (Relación belongsTo/hasMany).
- [x] U1: Modificar `UserResource.php` - Sección Declaración Operacional AESA.
- [x] U2: Modificar `UserResource.php` - Repeater de Licencias.
- [x] EJEC: Migrar BD y Optimizar Cachés.

## Fase P: Refinamiento UX Normativa AESA
- [x] UX1: Incorporar enlace interactivo oficial AESA en `UserResource`.
- [x] UX2: Refinar Repeater de Licencias con nuevas opciones de Dropdown.
- [x] EJEC: Optimizar cachés y Push a GitHub.

## Fase Q: Actualización de Documentación (README & GitHub)
- [x] Actualizar `README.md` inyectando capacidades sobre Apéndices AESA 1 y 2.
- [x] Sincronizar repositorio remoto en `main` (`commit fd7526f`).

## Fase R: Auto-completado Inteligente AESA
- [x] R1: Instalar `smalot/pdfparser`.
- [x] R2: Inyectar lógica de extracción de fechas al Repeater de `licenses` en `UserResource`.
- [x] EJEC: Optimizar cachés.

## Fase S: Auto-completado Integral AESA
- [x] S1: Refactorizar `UserResource` con lógica de extracción de Tipos, Número de Certificado y Filtro anti-fecha de nacimiento.
- [x] EJEC: Optimizar cachés.

## Fase T: Interfaz y Redundancias del Gestor Documental
- [x] T1: Renombrar label de la sección del Apéndice 2 y del Repeater.
- [x] T2: Extirpar `LicensesRelationManager` redundante del Resource.
- [x] EJEC: Optimizar cachés.

## Fase U: Refinamiento Repeater Licencias UX
- [x] U1: Añadir `->collapsed()`, `->defaultItems(0)` e `->itemLabel()` rico al Repeater.
- [x] U2: Añadir text de ayuda (Placeholder) en la sección.
- [x] EJEC: Optimizar cachés.

## Fase V: Refinamiento de Flujo de Navegación de Usuarios
- [x] V1: Inyectar `getRedirectUrl()` en `EditUser.php`.
- [x] V2: Inyectar `getRedirectUrl()` en `CreateUser.php`.
- [x] EJEC: Optimizar cachés.

## Fase W: Fase 2 (Apéndice 3 AESA) - Trazabilidad Legal Drones
- [x] W1: Crear migración para campos legales en `drones`.
- [x] W2: Añadir campos al `$fillable` de `Drone.php`.
- [x] W3: Inyectar formulario de Seguro y AESA en `DroneResource.php`.
- [x] W4: Inyectar columna de Caducidad de Seguro en el `index` de `DroneResource.php`.
- [x] W5: Añadir redirección automatica en `CreateDrone.php` y `EditDrone.php`.
- [x] EJEC: Migrar BD y Optimizar Cachés.

## Fase X: Refinamiento UX Formulario de Drones
- [x] X1: Eliminar campo redundante `registration_mark` de `DroneResource.php`.
- [x] EJEC: Optimizar cachés.

## Fase Y: Fase 2 (Apéndice 3 AESA) - Trazabilidad Baterías
- [x] Y1: Crear Modelo y Migración para `Battery`.
- [x] Y2: Configurar relaciones en `Battery.php` y `Drone.php`.
- [x] Y3: Construir `BatteriesRelationManager` (Filament) con Formularios y Tablas.
- [x] Y4: Inyectar `BatteriesRelationManager` en `DroneResource.php`.
- [x] EJEC: Migrar BD y Optimizar Cachés.

## Fase Z: Reestructuración UI Drones y Bloqueo de Campos
- [x] Z1: Vaciar `getRelations()` en `DroneResource.php`.
- [x] Z2: Bloquear inmutabilidad en `serial_number` y `aesa_registration_number`.
- [x] Z3: Inyectar Repeaters de Baterías y Mantenimientos en `DroneResource.php`.
- [x] EJEC: Optimizar cachés.

## Fase AA: Corrección de inmutabilidad en Drones
- [x] AA1: Restaurar edición en `aesa_registration_number`.
- [x] AA2: Restaurar edición en Baterías (`internal_id`, `model_name`).
- [x] AA3: Restaurar edición en Mantenimientos (`date`, `type`).
- [x] EJEC: Optimizar cachés.

## Fase BB: Actualización Documental (README & Repositorio)
- [x] BB1: Actualizar `README.md` inyectando capacidades sobre Apéndice AESA 3 (Baterías, Mantenimiento y Seguros).
- [x] EJEC: Sincronizar repositorio remoto en `main`.

## Fase CC: Auditoría y Cumplimiento de Vuelos (Apéndice 4 AESA)
- [x] CC1: Crear migración para añadir `observations` y `battery_id` a `flights`.
- [x] CC2: Actualizar `Flight.php` con fillable y relación `battery()`.
- [x] CC3: Refinar `FlightResource.php` estandarizando `mission_type` y añadiendo sección de Telemetría e Incidencias.
- [x] EJEC: Migrar BD y Optimizar Cachés.

## Fase DD: Telemetría Visual y Relación de Baterías
- [x] DD1: Añadir relación `flights()` en `Battery.php`.
- [x] DD2: Inyectar Placeholders de telemetría en el Repeater de baterías de `DroneResource.php`.
- [x] EJEC: Optimizar cachés.

## Fase EE: Refinamiento de Flujo de Vuelos y UX de Baterías
- [x] EE1: Inyectar `getRedirectUrl()` en `CreateFlight.php` y `EditFlight.php`.
- [x] EE2: Ocultar spinners nativos en `charge_cycles` (`DroneResource.php`).
- [x] EJEC: Optimizar cachés.

## Fase FF: Mejora UX Cabeceras Repeaters
- [x] FF1: Añadir `extraItemActions` visual al Repeater de Baterías.
- [x] FF2: Añadir `extraItemActions` visual al Repeater de Mantenimientos.
- [x] EJEC: Optimizar cachés.

## Fase GG: Resolución Excepción SQL (Técnico de Mantenimiento)
- [x] GG1: Añadir campo `technician` al Repeater de Mantenimientos en `DroneResource.php`.
- [x] GG2: Verificar `$fillable` en `Maintenance.php`.
- [x] EJEC: Optimizar cachés.

## Fase HH: Refinamiento de Cabeceras en Repeaters
- [x] HH1: Deshabilitar cabecera nativa en el Repeater de Baterías (`header(false)`).
- [x] EJEC: Optimizar cachés.

## Fase II: Restauración y Limpieza de Repeaters
- [x] II1: Eliminar `header(false)` del Repeater de Baterías.
- [x] II2: Eliminar `extraItemActions` de Baterías y Mantenimientos.
- [x] EJEC: Optimizar cachés.

## Fase JJ: Documentación Final y Repositorio
- [x] JJ1: Actualizar `README.md` inyectando capacidades sobre Apéndice AESA 4, Baterías y UX de Vuelos.
- [x] EJEC: Sincronizar repositorio remoto en `main`.

## Fase KK: Sincronización Tenant en Mantenimientos
- [x] KK1: Asegurar columna `team_id` en la base de datos `maintenances` (Migración).
- [x] KK2: Confirmar `$fillable` y relación en `Maintenance.php` (Completado previamente).
- [x] KK3: Inyectar ID del Tenant en Repeater de `DroneResource.php` (`mutateRelationshipDataBeforeCreateUsing`).
- [x] EJEC: Migrar y optimizar cachés.

## Fase LL: Exportación de Libro de Vuelos a PDF
- [x] LL1: Crear Acción `export_pdf` en el `headerActions` de `FlightResource.php`.
- [x] LL2: Crear plantilla base Blade en `resources/views/pdf/vuelos-logbook.blade.php`.
- [x] EJEC: Optimizar cachés.

## Fase MM: Estilización Corporativa del Libro de Vuelos PDF
- [x] MM1: Inyectar branding Aicor y diseño CSS en `vuelos-logbook.blade.php`.
- [x] MM2: Añadir sumatorio de tiempo total volado y doble línea para Matrículas.
- [x] EJEC: Limpiar caché de vistas (`view:clear`) y push a repositorio.

## Fase NN: Unificación de Branding Corporativo en PDF
- [x] NN1: Auditar `flight-report.blade.php` en busca del logo institucional y marca de agua.
- [x] NN2: Inyectar imagen Base64 y estilos CSS interactivos en `vuelos-logbook.blade.php`.
- [x] EJEC: Limpiar caché de vistas (`view:clear`) y push a repositorio.

## Fase OO: Ajustes de Nomenclatura AESA en Libro de Vuelos
- [x] OO1: Cambiar "Tenant" por "Operador UAS".
- [x] OO2: Cambiar "Piloto al Mando" por "Piloto".
- [x] OO3: Cambiar "Misión" por "Operación".
- [x] EJEC: Limpiar caché de vistas y push.

## Fase PP: Documentación de Capacidades y Push Final
- [x] PP1: Actualizar `README.md` con las nuevas capacidades del Libro de Vuelos masivo.
- [x] EJEC: Sincronizar repositorio remoto en `main`.

## Fase QQ: Generador PDF del Libro de Mantenimientos AESA
- [x] QQ1: Estructurar `mantenimientos-logbook.blade.php` clonando la cabecera corporativa e inyectando `$maintenances`.
- [x] QQ2: Inyectar Acción `export_pdf` (color warning) en el `MaintenanceResource.php` filtrando mediante Query.
- [x] EJEC: Limpiar caché de vistas (`view:clear`) y push a repositorio.

## Fase RR: Refinamiento UX en Mantenimientos (Datalist y Redirección)
- [x] RR1: Inyectar `->datalist()` dinámico de usuarios en `technician` (Repeater de Drones).
- [x] RR2: Inyectar `->datalist()` dinámico de usuarios en `technician` (Formulario de Mantenimientos).
- [x] RR3: Inyectar `getRedirectUrl()` en `CreateMaintenance.php` y `EditMaintenance.php`.
- [x] EJEC: Optimizar cachés y push a repositorio.

## Fase SS: Validez Jurídica PDF y Fix Datalist
- [x] SS1: Inyectar bloque HTML/CSS de Firma y Sello en `vuelos-logbook.blade.php`.
- [x] SS2: Inyectar bloque HTML/CSS de Firma y Sello en `mantenimientos-logbook.blade.php`.
- [x] SS3: Aplicar `withoutGlobalScopes()` en el Datalist de Técnicos (`DroneResource.php` y `MaintenanceResource.php`).
- [x] EJEC: Limpiar cachés de vistas y optimización, push a repositorio.

## Fase TT: Refinamiento Final de Validez Jurídica y Datalist
- [x] TT1: Unificar bloque de Firmas y Sello (sin círculos) en los 3 PDFs (`flight-report`, `vuelos-logbook`, `mantenimientos-logbook`).
- [x] TT2: Refinar consulta SQL del Datalist con Arrow Functions en Formularios.
- [x] TT3: Aplicar Nomenclatura AESA ("OPERACIÓN", "PILOTO") en Libro de Vuelos masivo.
- [x] EJEC: Limpiar caché de vistas (`view:clear`) y push a repositorio.

## Fase UU: Super Select Dinámico para Técnicos de Mantenimiento
- [x] UU1: Refactorizar `TextInput` a `Select` interactivo (`MaintenanceResource.php`).
- [x] UU2: Inyectar `searchable()`, `preload()` y creación modal in-situ (`createOptionForm()`).
- [x] EJEC: Optimizar cachés y push a repositorio.

## Fase VV: Documentación Final y Repositorio
- [x] VV1: Actualizar `README.md` inyectando capacidades sobre Libros de Vuelo/Mantenimiento Jurídicos y UX Avanzada (Selects).
- [x] EJEC: Sincronizar repositorio remoto en `main` declarando finalización de etapa.

## Fase WW: Parche de Seguridad (Cross-Tenant Leakage)
- [x] WW1: Refactorizar el campo `drone_id` en `MaintenanceResource.php` aislando la consulta por `team_id`.
- [x] EJEC: Optimizar cachés y push a repositorio.

## Fase YY: Biblioteca Legal del Operador (Documental Institucional)
- [x] YY1: Crear Modelo y Migración `OperatorDocument` con `team_id` y `expiration_date`.
- [x] YY2: Estandarizar relaciones Multi-Tenant en `App\Models\Team`.
- [x] YY3: Construir `OperatorDocumentResource` blindado (`$isScopedToTenant`) con inputs categóricos y avisos visuales.
- [x] EJEC: Migrar DB, optimizar cachés y push a repositorio.

## Fase ZZ: Refinamiento UX de Auto-redirección en Biblioteca Legal
- [x] ZZ1: Inyectar `getRedirectUrl()` en `CreateOperatorDocument.php`.
- [x] ZZ2: Inyectar `getRedirectUrl()` en `EditOperatorDocument.php`.
- [x] EJEC: Optimizar cachés y push a repositorio.

## Fase CCC: Despliegue del Monitor de Cumplimiento Normativo
- [x] Construir y configurar `ComplianceMonitorWidget` (`columnSpan=1`, `sort=30`).
- [x] Unificar Query de expiración con Drones y Documentos Institucionales simultáneamente (`unionAll`).
- [x] Definir columnas específicas ("Recurso", "Tipo", "Vencimiento", "Gestionar") con enlaces inversos al recurso originario.

## Fase DDD: Operación Dossier Maestro AESA (Custom Page)
- [x] Construir la página personalizada en Filament (`AesaAuditPage.php`).
- [x] Diseñar el componente de Formulario y Parametrización (Rango de Fechas / Checkboxes).
- [x] Generar lógica de sub-renders Blade a DomPDF compilada en una sola descarga perimetrada.

## Fase EEE: Operación Centinela (Seguridad por Diseño)
- [x] Blindaje UX de Vuelos: Filtro inteligente `modifyQueryUsing` en `FlightResource` para habilitar Drones y Pilotos normativos.
- [x] Hard-Security: Validación `beforeCreate()` en la creación de vuelos (Excepción `halt()` ante caducidades del motor).
- [x] Trazabilidad: Inyección de `Log::info` en `AesaAuditPage` como huella digital para la auditoría interna.

## Fase FFF: Wizard Operativo Universal (Agnóstico y Dinámico)
- [x] Refactorización masiva de `FlightResource` descartando Secciones y adoptando Filament `Wizard` (3 pasos).
- [x] Ampliación de las Categorías Normativas (`Abierta`, `STS`, `SORA`, `NO_EASA`).
- [x] Implementación P2: Evaluaciones de seguridad dinámicas inyectadas como arreglos JSON en el campo `$pre_flight_checklist`.
- [x] Implementación P3: Desvío de telemetría a un Tab Final con condicionales reactivos.
- [x] Lógica de Estado (No EASA): Desvío excepcional en Checklists y directores `beforeCreate()` para asegurar Agilización Jurídica a las Fuerzas de Seguridad.
- [x] FFF (Hotfixes): Ajuste Mimetype JSONs ENAIRE (`text/plain`, `application/javascript`) y nullabilidad preventiva (`start_time`).
- [x] FFF (Hotfixes): Inyección directa de directiva `wire:click="create"` sobre botón HTML del Wizard para forzar salvamento e invoke de `getRedirectUrl()`.
- [x] FFF (Hotfixes): Ajuste literal retroactivo a "Hora de inicio" y "Hora de finalización".
- [x] FFF (DevOps): Pivotado rama Git "feature/wizard-flight-log-refactor" para testing seguro.
- [x] ZZZ (Seguridad Operacional): Implementación del "Smart Blocker" en `FlightResource` para denegar vuelos cuyas categorías restringidas (STS, SORA) carezcan de cobertura explícita en la Biblioteca Legal (`OperatorDocument`).
- [x] ZZZ (Seguridad Operacional): Implementación del "Smart Blocker" de Pilotos en la planificación (`FlightResource`), cruzando la categoría de vuelo deseada con las licencias vigentes registradas por el agente.
- [x] ZZZ (Biblioteca Legal): Refactorización de directiva genérica "Declaracion STS" hacia control estricto de claves `sts_01` y `sts_02` en despliegue de OperatorDocumentResource, sincronizando validación cruzada con el Wizard de vuelos (`FlightResource`).
- [x] ZZZ (Asistente de Vuelo): Refactorización del "Paso 2" del Asistente a un modelo de Cuestionarios de Seguridad Dinámicos inyectables en base de datos tipo JSON, con 4 secciones reactivas hiper-especializadas (A1/A3, STS-01, STS-02 y No EASA).
- [x] FFF (Hotfixes): Eliminación de directiva manual `submitAction(wire:click="create")` en `FlightResource` para evitar crashes de Livewire v3 (`MethodNotFoundException`) y delegar el submit al componente de formulario nativo de Filament.
- [x] ZZZ (Seguridad Operacional): Inmovilización táctica de Registros Completados; Ocultado Acción de Edición en tabla y deshabilitado de campos completos del Wizard (`->disabled()`) si el estado del vuelo es terminal (`completed`).
- [x] FFF (Refinamiento): Normalización y neutralidad terminológica (eliminación de "agente", "jefatura", "policial") en directivas No-EASA para despliegue civil. Inyección de blindaje duro en el Policy `FlightPolicy` para denegar (`Response::deny`) con mensaje institucional la mutación de vuelos cerrados a cualquier rol inferior a `super_admin`.
- [x] FFF (Hotfixes): Reparación de generador PDF de Partes de Vuelo y Libreta de Operaciones (Logbook), subsanando referencias rotas al modelo heredado `Organization` y sustituyéndolas por la nueva estructura MultiTenant de Filament (`Team`).
- [x] DOCS (Estandarización UI): Implementación de arquitectura Blade orientada a Layouts (`master.blade.php`) para unificación estética corporativa (Azul AICOR, tipografía limpia, paginadores automáticos) a lo largo de todo el ecosistema de PDFs institucionales AESA. Se incluyen helpers reactivos PHP para traducción dinámica de keys (`mission_type`, estado, intervenciones).- [x] FFF (Refinamiento): Sincronizada validación Hard-Security en página de Edición y labels en Tabla.
- [x] FFF (Actualización): Normalización quirúrgica de categorías normativas (`open_category`, `sts_01`, `sts_02`, `sora`, `no_easa`, `training`) y corrección de colaterales en validaciones.
- [x] ZZZ (Biblioteca Legal): Incorporación del tipo documental EARO (Estudio Aeronáutico de Seguridad) en el catálogo institucional.
- [x] DOCS (Finalización): Actualización masiva de README.md, Walkthrough y Task Tracker reflejando el MVP V3.5 con Wizard Operativo.
- [x] DEPLOY (Git): Consolidación de ramas y push final a producción (main).
- [x] ZZZ (Biblioteca Legal): Ampliación de tipos documentales con ERP (Plan de Emergencias) y Justificantes MIR.
- [x] ZZZ (Biblioteca Legal): Añadido campo de control `is_active` a Base de Datos.
- [x] ZZZ (Biblioteca Legal): Configuración de Pestañas Dinámicas (En Vigor / Histórico) e implementación de agrupaciones y orden cronológico en la tabla interactiva.
