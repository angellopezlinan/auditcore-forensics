# 🚁 Aicor UAS - Fleet Management API

Sistema backend profesional para la gestión de flotas de aeronaves no tripuladas (UAS) y organizaciones (Ayuntamientos, FFCCSE, Bomberos). Diseñado para automatizar el registro de aeronaves y la generación de documentación oficial.

## 🚀 Tecnologías
- **Framework:** Laravel 12.5
- **Entorno:** Docker (Laravel Sail)
- **Base de Datos:** MariaDB
- **Protocolo:** REST API (JSON)
- **Generación PDF:** `barryvdh/laravel-dompdf`

## 🖥️ Arquitectura de Despliegue (Production)
El entorno de producción opera bajo una arquitectura **Bare Metal** sobre un VPS con Ubuntu, optimizando el rendimiento al eliminar capas de virtualización innecesarias.

- **OS:** Ubuntu 22.04 LTS / 24.04 LTS
- **Web Server:** Nginx (configurado con FastCGI)
- **Runtime:** PHP 8.2-FPM / 8.3-FPM
- **Database:** MariaDB 10.11+
- **Nota:** No se utiliza Docker/Sail en producción para maximizar la eficiencia de los recursos del sistema.

## 🏗️ Arquitectura de Datos
El sistema gestiona una arquitectura Multi-Tenant basada en Organizaciones:
- Una **Organización** (Ayuntamiento/Jefatura) posee múltiples **Usuarios (Gestores)**, **Pilotos** y **Aeronaves (Drones)**.
- Cada **Usuario** está vinculado a una Organización y la información que lee o crea se restringe automáticamente a su ayuntamiento (Scoping de Datos).
- Los **Drones** registran un historial cronológico de operaciones (**Vuelos**).

## 🛡️ Arquitectura SaaS B2B (Multi-Tenant)

Aicor UAS opera bajo una arquitectura Multi-Tenant estricta, diseñada para aislar los datos de diferentes Ayuntamientos o Jefaturas de Policía ("Entidades").

* **Aislamiento de Datos:** Drones, Vuelos, Mantenimientos y Usuarios están estrictamente vinculados a su Entidad (`team_id`). Un piloto de Sevilla nunca podrá ver la flota de Córdoba.
* **Seguridad Contextual (Spatie Shield):** Los roles y permisos son dinámicos según la Entidad. Un usuario puede ser `super_admin` en la Comandancia Central y un simple `piloto` en otra Jefatura.
* **Comandancia Central:** Actúa como la Entidad fundadora. El usuario creador (`jefe@cordoba.es`) posee el rol de Super Admin global, con capacidad para saltar entre Entidades manteniendo privilegios absolutos, siendo además invisible para la edición de los administradores locales por motivos de seguridad.

## 🛡️ Arquitectura DevSecOps y Hardening

La plataforma AICOR UAS API ha sido sometida a un riguroso proceso de auditoría y blindaje de seguridad estructurado en dos fases críticas, garantizando la integridad de las operaciones y la confidencialidad de los datos bajo estándares profesionales.

### FASE 1: Análisis Estático y Tipado Estricto (SAST/SCA)

En esta fase se sentaron las bases técnicas para evitar errores en tiempo de ejecución y vulnerabilidades lógicas mediante herramientas de análisis de vanguardia:

- **Larastan Nivel 5**: El proyecto ha superado el análisis estático de PHPStan con el estándar más exigente de Laravel (Nivel 5 completo). Se ha implementado tipado estricto en todos los retornos de funciones, closures de Filament y definiciones de relaciones de Eloquent, eliminando ambigüedades en la manipulación de datos.
- **Refactorización a Enums (Integridad de Datos)**: Se han eliminado los *magic strings* en la base de datos, reemplazándolos por Enums nativos de PHP 8.1. El uso de `AesaOperationType` garantiza que solo se procesen valores válidos y homologados (ej. `STS-01`, `OPEN-CATEGORY`), asegurando la coherencia total entre el motor de base de datos y la interfaz de usuario.

### FASE 2: Seguridad Lógica y Multi-Tenant (Protección de Datos)

El enfoque de esta fase fue blindar la aplicación contra vectores de ataque comunes en plataformas multi-inquilino y asegurar la privacidad entre equipos:

- **Prevención IDOR (Insecure Direct Object Reference)**: Se ha implementado un blindaje a nivel de Policy para todos los modelos críticos (`Drone`, `Flight`, `Incident`, `User`). La validación no se limita a comprobar permisos, sino que exige una validación de pertenencia estricta mediante intersección de equipos (Tenants):
  ```php
  return $user->teams->intersect($model->teams)->isNotEmpty();
  ```
- **Prevención de Mass Assignment**: Se ha deshabilitado la asignación masiva de campos sensibles. Atributos como `team_id`, `user_id`, `role` y `organization_id` han sido eliminados de la propiedad `$fillable` en todos los modelos críticos, mitigando el riesgo de manipulación de payloads.
- **Filament Hardening (Evasión de Parameter Tampering)**: Para garantizar la funcionalidad sin comprometer la seguridad de los modelos, se utiliza el método `mutateFormDataBeforeCreate` en los recursos de Filament. Esto permite la inyección manual y segura de los IDs de Tenant y Usuario directamente en el backend, ignorando cualquier intento de manipulación en las peticiones POST/PUT.
- **Cabeceras HTTP de Seguridad (Hardening de Respuesta)**: Implementación de un `SecurityHeadersServiceProvider` que inyecta automáticamente capas de protección en la capa HTTP:
    - **HSTS (Strict-Transport-Security)**: Fuerza el uso exclusivo de HTTPS en producción.
    - **Anti-Clickjacking**: `X-Frame-Options` configurado en `SAMEORIGIN`.
    - **Anti-MIME Sniffing**: `X-Content-Type-Options` configurado en `nosniff`.
    - **Content-Security-Policy (CSP)**: Implementación de una política base para mitigar ataques XSS, permitiendo exclusivamente los recursos necesarios para el funcionamiento de Filament (incluyendo `unsafe-inline` controlado).

### FASE 3: Análisis Dinámico y Resistencia (DAST)

En esta etapa se ha validado la seguridad del sistema en tiempo de ejecución, optimizando la capa de transporte y blindando los puntos de entrada de datos:

- **Protección contra Fuerza Bruta**: Se ha implementado un *Rate Limiting* estricto tanto a nivel de API (`throttle:6,1`) como en el panel administrativo de Filament, mitigando ataques de diccionario en los formularios de autenticación.
- **Mitigación XSS y RCE (Hardening de Archivos)**: Todos los componentes de subida de archivos de la plataforma (Drones, Vuelos, Licencias) han sido blindados con validación estricta de Mime-Types (ej. bloqueo de `application/javascript` y forzado a `application/pdf`) y una limitación física de tamaño de **5MB**. Esto previene la ejecución remota de código y protege contra ataques de Denegación de Servicio (DoS) por agotamiento de almacenamiento.
- **Blindaje del Transporte (Security Headers Middleware)**: Migración de la inyección de cabeceras a un Middleware global de alto rendimiento, garantizando la persistencia de directivas CSP, HSTS y Anti-Clickjacking en cada respuesta del servidor. Se ha configurado el sistema para forzar cookies seguras (`HttpOnly`, `SameSite=Lax`) y la activación automática de `Secure` en entornos HTTPS.

- **Acceso Restringido (Principio de Privilegio Mínimo)**: La interfaz de auditoría (`ActivityResource`) está integrada en el Hangar pero se configura como **100% Read-Only**. El acceso está restringido dinámicamente mediante políticas de autorización que permiten la visualización única y exclusivamente al rol de **Super Administrador**.

### FASE 5: Cumplimiento ENS y Privacidad de Datos

En la quinta fase de hardening, se ha elevado el estándar de acceso y protección de la información sensible para alinearse con el Esquema Nacional de Seguridad (ENS) y las normativas de protección de datos:

- **Autenticación Multi-Factor Robusta (2FA/TOTP v2.0)**: Migración a un sistema de 2FA obligatorio blindado mediante la clase `Setup2FA` aislada. El flujo es interceptado globalmente por el middleware `EnsureMandatory2FA`, que utiliza resolución de URL nativa de Filament para garantizar la navegación fluida incluso en entornos Multi-Tenant y servidores sensibles a mayúsculas (Linux Case-Sensitivity Support).
- **Protocolo de Bloqueo de Cuenta (Sentinel ENS)**: Implementación de un mecanismo de defensa activa que bloquea físicamente la cuenta (`is_locked`) tras **3 intentos fallidos** de validación 2FA. El sistema cierra automáticamente todas las sesiones activas y requiere la intervención manual de un SuperAdministrador para el desbloqueo.
- **Evasión de Bloqueo para SuperAdmins**: Por directiva de disponibilidad ENS, las cuentas con rol de `super_admin` están exentas del bloqueo automático para evitar la "negación de servicio" administrativa en situaciones críticas.
- **Blindaje contra IDOR en Archivos (Bóveda Privada)**: Migración integral de todos los documentos sensibles (licencias de pilotos, pólizas de seguros, evidencias de ENAIRE y registros de la Biblioteca Legal) a una "Bóveda Privada" en almacenamiento de disco local restringido. Los archivos ya no son accesibles mediante URLs directas o públicas (`/storage/...`), eliminando el riesgo de ataques IDOR. Su descarga está ahora enrutada, controlada y protegida por las políticas de autorización (Policies) del backend, exigiendo una validación de permisos en tiempo real antes de servir el flujo de datos.

### FASE 6: Hardening de Sesiones y CSP (ENS Compliance)

Para garantizar el cumplimiento con el Esquema Nacional de Seguridad (ENS) en entornos aeronáuticos, se han implementado protocolos estrictos de persistencia:

- **Control de Sesión**:
    - **Session Lifetime**: Limitado a **60 minutos** (`SESSION_LIFETIME=60`) para minimizar la ventana de exposición.
    - **Expire on Close**: Las sesiones caducan automáticamente al cerrar el navegador (`SESSION_EXPIRE_ON_CLOSE=true`).
- **Eliminación de Persistencia Excesiva**: Se ha desactivado la opción **"Remember Me"** en el login de Filament mediante una sobrescritura de la lógica de autenticación, obligando al usuario a identificarse en cada nueva conexión.
- **Inyección de CSP Dinámica**: El middleware de seguridad autoriza explícitamente a `fonts.bunny.net` en las directivas `style-src` y `font-src`, permitiendo la carga de tipografías sin comprometer la integridad de la política de seguridad.

## 🛠️ Instalación y Puesta en Marcha

1. **Levantar el entorno (Local):**
   ```bash
   ./vendor/bin/sail up -d
   ./vendor/bin/sail artisan migrate --seed
   ```

2. **Despliegue en Servidor (Bare Metal):**
   Tras realizar un `git pull origin main`, ejecute el siguiente protocolo de actualización:
   ```bash
   # 1. Dependencias y optimización
   composer install --optimize-autoloader --no-dev
   
   # 2. Migraciones forzadas (Producción)
   php artisan migrate --force
   
   # 3. Assets de Filament y UI
   php artisan filament:assets
   
   # 4. Limpieza y Re-Caché de alto rendimiento
   php artisan optimize:clear
   php artisan optimize
   
   # 5. Permisos de sistema Nginx/FPM
   sudo chown -R www-data:www-data .
   ```

## 🔄 Sincronización de Datos (Caja Negra)
Protocolo para clonar la base de datos de desarrollo a producción manteniendo la integridad de la "Caja Negra":

1. **Exportación (Local/MariaDB):**
   ```bash
   docker exec [ID_CONTENEDOR] mariadb-dump -u sail -ppassword laravel > backup_aicor.sql
   ```
2. **Transferencia al VPS:**
   ```bash
   scp backup_aicor.sql ubuntu@[IP_DEL_SERVIDOR]:/ruta/proyecto
   ```
3. **Importación (Producción):**
   ```bash
   # En el servidor
   mysql -u aicor_user -p aicor_uas < backup_aicor.sql
   ```
   > [!TIP]
   > Si existen errores de integridad al importar, inicie la consola de MySQL y ejecute `SET FOREIGN_KEY_CHECKS=0;` antes de la importación, reactivándolo con `1` al finalizar.

## 🔒 Autenticación (Sanctum)
Todas las rutas de la API, a excepción del login, están protegidas mediante Bearer Token.

| Método | Endpoint | Descripción |
| :--- | :--- | :--- |
| `POST` | `/api/login` | Iniciar sesión y obtener el `access_token` Bearer. |
| `POST` | `/api/logout` | Revocar el token actual del usuario. |
| `GET` | `/api/user` | Obtener la información del usuario autenticado actual y su Organización. |

## 📡 Endpoints Protegidos (API)

*Cabeceras necesarias:* `Authorization: Bearer {tu_token_aqui}`

### 🚁 Drones
| Método | Endpoint | Descripción |
| :--- | :--- | :--- |
| `GET` | `/api/drones` | Listado de aeronaves de tu organización. |
| `POST` | `/api/drones` | Registro de una nueva aeronave. |
| `GET` | `/api/drones/{id}/flights` | Consulta de todos los vuelos de un dron específico. |
| `GET` | `/api/drones/{id}/logbook/pdf` | **[NUEVO]** Descargar el Logbook Oficial (Formato AESA) en PDF con el historial del dron. |

### 🪪 Pilotos
| Método | Endpoint | Descripción |
| :--- | :--- | :--- |
| `GET` | `/api/pilots` | Listado completo de los pilotos de tu organización. |
| `GET` | `/api/pilots/{id}` | Detalles completos de un piloto específico. |
| `POST` | `/api/pilots` | Registro de un nuevo piloto (requiere DNI y Número de Licencia únicos). |

### 🛫 Vuelos (Logbook)
| Método | Endpoint | Descripción |
| :--- | :--- | :--- |
| `GET` | `/api/flights` | Historial de vuelos (Filtros: `?drone_id=`, `?mission_type=`). |
| `GET` | `/api/flights/{id}` | Detalles de un vuelo específico. |
| `POST` | `/api/flights` | Registro de un nuevo vuelo. Requisito legal: El payload debe incluir `"enaire_check_passed": true` y opcionalmente latitud, longitud y un PDF de evidencia (`enaire_report_file`). |

## ⚙️ Integración Continua (CI/CD)
Este proyecto incluye un pipeline automatizado configurado con **GitHub Actions**.
Cada vez que se realiza un **Push** o se abre una **Pull Request** hacia la rama `main`, se levanta un entorno virtual Ubuntu con PHP 8.4 y base de datos en memoria (SQLite) que ejecuta los tests de Laravel (`php artisan test`) para prevenir y validar cualquier error de código antes de la subida a producción.

## 🖥️ Centro de Mando Web (Hangar Dashboard)
El proyecto incluye un completo e intuitivo panel de administración (Backoffice) construido con **Filament PHP**, accesible a través de la ruta `/admin`.

### ✨ Funcionalidades del Dashboard
1. **🏠 Inicio (Widgets):** Visión general de la flota, recuento de personal activo y tabla de acceso rápido a los 5 últimos vuelos registrados.
2. **🚁 Gestión de Flota:** Módulo de alta de aeronaves (Drones) con control de marca, modelo, peso y número de registro.
3. **🪪 Personal Operativo (Normativa AESA):** Módulo de usuarios donde los gestores pueden:
   - Asignar jerarquías y Roles de Sistema (Admin/Gestor).
   - **(Apéndice 1):** Registrar la "Declaración Operacional AESA" garantizando la lectura del Manual de Operaciones vigente con conexión directa al Portal del Gobierno de España.
   - **(Apéndice 2):** Administrar el "Expediente de Licencias" (Repeater) permitiendo agrupar múltiples titulaciones (A1/A3, STS, Radiofonista, Médico) con fechas de caducidad y evidencias en PDF para cada piloto.
4. **🛫 Wizard Operativo Universal (3 Pasos):** Refactorización del registro de vuelos en un asistente guiado:
   - **Paso 1: Planificación:** Selección de aeronave, piloto y marco normativo (Agnóstico).
   - **Paso 2: Evaluación de Seguridad:** Checklists dinámicos que aparecen según la categoría elegida (`open_category`, `sts_01/02`, `sora`, `no_easa`).
   - **Paso 3: Registro de Operación:** Telemetría real (tiempos y baterías) bloqueada hasta la finalización del vuelo.
   - **Agilidad para Operaciones de Estado (`no_easa`):** Las misiones de seguridad pública cuentan con un "bypass" inteligente de validaciones de seguro y licencias en caso de emergencia, manteniendo la trazabilidad sin bloquear la respuesta inmediata.
   - **Nomenclatura Jurídica:** Campos de tiempo renombrados a "Hora de inicio/finalización de la operación" para exactitud legal.
5. **🧠 Inteligencia ENAIRE y PDF:**
   - **Lector de Zonas Aéreas:** Puedes subir directamente el archivo exportable en JSON del mapa de ENAIRE Drones y el sistema procesará, destilará y rellenará de forma automática el informe Anti-Tocho con las "Alertas y Condiciones del Espacio Aéreo".
   - **Recopilador Documental:** Puedes subir el PDF con la Evidencia de autorización de vuelo original y consultarla in-situ en el propio registro con validaciones de "ENAIRE OK".
   - **Generador "Parte Oficial de Vuelo":** Motor DomPDF integrado en la tabla. A golpe de click, se compila un documento A4 totalmente validado y formateado listo para presentar a AESA con un desglose de:
     - Trazabilidad Operacional de la Aeronave y Operador (Responsable legal).
     - Datos del Piloto al Mando.
     - Metadatos Geográficos.
     - Extracción blindada (Fuerza Bruta CSS al 100% render width) de las restricciones legales ENAIRE pre-inyectadas.
   - **Generador "Libro de Vuelos (Apéndice 4 AESA)":** Motor de exportación masiva en PDF desde la tabla principal. Compila el histórico oficial completo de operaciones con inyección de marca de agua Institucional Aicor, sumatorio dinámico de tiempos de vuelo y nomenclatura legal AESA estricta.

6. **🛡️ Sistema de Rangos y Seguridad (Filament Shield):**
   - **Gestor Visual de Permisos (RBAC):** Integración nativa del paquete `bezhansalleh/filament-shield`, permitiendo a los Super Admisnitradores crear roles y establecer políticas granulares (Ver, Crear, Editar, Borrar) recurso a recurso, directamente desde la Interfaz Gráfica sin tocar código.
   - **Multitenancy y Compartimentación Táctica:** El sistema aísla automáticamente los datos (Scoping) en la tabla `Partes de Vuelo` garantizando que los **Privados/Pilotos básicos** solo puedan ver sus propias operaciones, mientras que la Jefatura tiene visión global sobre su Organización.
7. **🇪🇸 Castellanización Total:** Aplicación configurada end-to-end bajo idioma Español (ES) en su núcleo global, afectando componentes genéricos y diccionarios de seguridad interactivos ("Hangar de Drones", "Partes de Vuelo").

## 🎯 Estado del Proyecto (MVP V3.0)
- ✅ Implementado sistema multi-tenant (Aislamiento de datos por Organización).
- ✅ Autorización Jerárquica y Operador Legal (Spatie RBAC).
- ✅ API REST documentada: Gestión de Drones, Pilotos, Vuelos y Exportación del Logbook Digital.
- ✅ Centro de Mando Web (Filament PHP) de interfaz táctica oscura.
- ✅ Gestión de la Flota de Drones con badges interactivos y filtros.
- ✅ Gestión de Personal Operativo y Gestor Documental de Licencias anidado.
- ✅ Dashboard Analytics (StatsOverview y Últimos Vuelos).
- ✅ Formularios de Operaciones Blindados, con validaciones puras de Livewire Backend.
- ✅ Despacho y Planificación de Vuelos con Procesamiento Automatizado de Intel ENAIRE (Lectura de Coordenadas y Extracción Condicional de XML/JSON).
- ✅ Motor de Renderizado (DomPDF) para extracción del Auténtico "*Parte Oficial de Vuelo*" en 470px (ancho completo adaptable).
- ✅ **[NUEVO]** Panel Visual de Gestión de Perfiles y Accesos mediante `bezhansalleh/filament-shield`.
- ✅ **[NUEVO]** Scoping Estricto (Multitenancy): Aislamiento absoluto de Drones, Mantenimientos y Vuelos. Los Pilotos de una Entidad jamás interactúan con los datos de otra.
- ✅ **[NUEVO]** Refactorización Arquitectónica: Eliminación de escudos heredados (legacy scopes) a favor del ruteo nativo de Filament Tenancy y relaciones explícitas Eloquent `team()`.
- ✅ **[NUEVO]** UI 100% Castellanizada en entorno de producción.
- ✅ **[NUEVO]** Conformidad Normativa AESA estricta: Auditoría digital del Apéndice 1 (Declaraciones Operacionales) y Apéndice 2 (Gestor Documental de Titulaciones de Pilotos).
- ✅ **[NUEVO]** Trazabilidad Legal de Flota (Apéndice 3 AESA): Registro obligatorio de Seguros RC, Vencimientos y Números de Registro.
- ✅ **[NUEVO]** Ecosistema de Aeronavegabilidad (Apéndice 3 AESA): UI unificada en Hangar con sub-registro dinámico para Baterías (Ciclos de carga, estado) y Libreta de Mantenimientos por Dron.
- ✅ **[NUEVO]** Registro Oficial Extendido (Apéndice 4 AESA): Adaptación del formulario de vuelos con estandarización de tipos de operación, vinculación directa de baterías y registro de incidencias/observaciones.
- ✅ **[NUEVO]** Refinamientos Operativos UX/UI: Auto-redirección de navegabilidad inversa (Listados -> Edición -> Listados), desactivación de spinners nativos para entrada segura de datos biométricos, y selectores dinámicos avanzados (Datalists & Modals) para asignación de técnicos en mantenimiento.
- ✅ **[NUEVO]** Motor Documental de Registro Masivo: Exportación PDF nativa del "Libro de Vuelos Oficial" y el "Libro de Mantenimientos", garantizando validez jurídica B2B (Firma, Sello de la Organización) y nomenclatura AESA estricta adaptada a operadores UAS multiplataforma.
- ✅ **[NUEVO]** Wizard de Vuelos 3.0: Asistente guiado con checklists de seguridad reactivos y normalización de categorías normativas (`sts_01`, `sts_02`, `sora`, `no_easa`).
- ✅ **[NUEVO]** Cuestionarios de Seguridad Dinámicos (SMS Aeronáutico): "Paso 2" transformado en un sistema reactivo JSON con mitigaciones hiper-especializadas según la categoría de vuelo (Abierta, STS-01, STS-02 y No EASA).
- ✅ **[NUEVO]** Operación Centinela y Smart Blocker Documental: Validación cruzada estricta (`beforeCreate`/`beforeSave`) en el "Paso 1" para exigir cobertura documental activa en la Biblioteca Legal (Declaraciones STS, EAROs) antes del despegue, impidiendo vacíos normativos.
- ✅ **[NUEVO]** Operación Centinela de Pilotos: Integración del Smart Blocker con el Expediente de Licencias. Denegación automatizada del formulario si el piloto escogido carece de la titulación requerida y en vigor para esa categoría específica (`A1/A3`, `STS-01`, `STS-02`).
- ✅ **[NUEVO]** Biblioteca Legal Expandida: Sistema documental con pestañas interactivas y agrupación inteligente, catalogando Seguros, Registro AESA, MO, EAROs, Plan de Respuesta a Emergencias (ERP) y Justificantes al MIR.
- ✅ **[NUEVO]** Hardening Core y Bases de Datos: Optimización para evitar excepciones nulas en Frontend y purgados de "MethodNotFoundExceptions" bajo Livewire v3 para despliegues inquebrantables.
- ✅ **[NUEVO]** Caja Negra AESA (Fase 4): Implementación de auditoría forense inalterable con tracking total de atributos y panel de visualización exclusivo para Super Administradores.
- ✅ **[NUEVO]** Autenticación Multi-Factor (2FA/TOTP): Cumplimiento ENS mediante integración nativa para accesos administrativos.
- ✅ **[NUEVO]** Bóveda Privada de Documentos: Blindaje de almacenamiento (`local`) y descargas enrutadas protegidas por Policies para evitar IDOR.
- ✅ **[NUEVO]** Sistema de Disaster Recovery: Gestión visual de Backups automatizados directamente desde el Dashboard.