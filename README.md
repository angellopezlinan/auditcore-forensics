# 🛡️ AuditCore Forensics
### Plataforma B2B Multi-tenant de Auditoría Financiera y Prevención de Fraude

AuditCore Forensics es una solución avanzada diseñada para la detección temprana de irregularidades financieras, auditoría de facturación y blindaje operativo de entidades corporativas y gubernamentales. La plataforma combina un motor de reglas estricto con una arquitectura multi-tenant para garantizar el aislamiento total de datos entre diferentes organizaciones.

## 🚀 Stack Tecnológico
- **Core:** Laravel 12.x (PHP 8.2+)
- **Admin Panel:** Filament PHP v3
- **Base de Datos:** MariaDB
- **Seguridad y Permisos:** Spatie Shield & Permission
- **Arquitectura:** Multi-tenancy basado en Equipos (Teams)
- **Hardening:** 2FA/TOTP integrado, Auditoría de logs inalterable (Spatie Activitylog)

## 🏗️ Estado Actual (Sprint 1: Cimientos de Seguridad)
Hemos completado la fase inicial de migración y consolidación de la infraestructura core:
- ✅ **Refactorización de DB:** Esquema optimizado para entidades financieras, empleados, proveedores y facturación.
- ✅ **Purga de Dominio Legacy:** Eliminación completa de componentes obsoletos del dominio anterior (UAS).
- ✅ **Panel de Administración Blindado:** Implementación de Filament v3 con gestión de usuarios, roles y auditoría.
- ✅ **SuperAdmin Configurado:** Acceso restringido y seguro para la gestión global del sistema.
- ✅ **Multi-tenancy Estricto:** Aislamiento de datos garantizado a nivel de base de datos y aplicación.

## 🛡️ Seguridad y Cumplimiento (Compliance)
La plataforma está diseñada bajo principios de **Security by Design**:
- **Bóveda Privada:** Almacenamiento seguro de facturas y documentos sensibles mediante discos protegidos.
- **Detección de Fraude:** Sistema de alertas automáticas ante duplicidad de facturas o anomalías en proveedores.
- **Auditoría Forense:** Registro total de actividades del sistema (`Caja Negra`) para cumplimiento normativo.
- **Bloqueo Inteligente:** Protección activa contra ataques de fuerza bruta y desvío de credenciales.

## 🕵️‍♂️ Centro de Inteligencia Forense (Intelligence Center)
AuditCore incorpora un centro de mando avanzado para investigadores financieros:
- **Detección de Duplicados:** Algoritmo propietario que cruza identificadores, proveedores e importes para prevenir pagos dobles.
- **Análisis de Ley de Benford:** Visualización estadística de la frecuencia del primer dígito para detectar manipulación de datos.
- **Monitor de Fines de Semana:** Alertas inteligentes para facturas emitidas en días no comerciales.
- **Ranking de Riesgo (Muro de la Vergüenza):** Dashboards de proveedores con mayores ratios de fraude confirmado.
- **Gestión Forense:** Páginas dedicadas con tablas de alta densidad y resolución de incidencias en cadena.

## 🛠️ Instalación y Despliegue Local (Laravel Sail)

Para comenzar el desarrollo en un entorno local controlado:

1. **Clonar el repositorio:**
   ```bash
   git clone [url-del-repo] auditcore-forensics
   cd auditcore-forensics
   ```

2. **Levantar el entorno Docker:**
   ```bash
   ./vendor/bin/sail up -d
   ```

3. **Ejecutar el Setup Automatizado:**
   ```bash
   ./vendor/bin/sail artisan migrate --seed
   ```
   *Este comando configurará las tablas, roles iniciales y el usuario administrador por defecto.*

4. **Acceso al Panel:**
   URL: `http://localhost/admin`
   Credenciales por defecto (Seed): `admin@auditcore.app` / `password123`

## ⚙️ Integración Continua
El proyecto incluye pipelines de **GitHub Actions** que ejecutan automáticamente:
- Análisis estático con Larastan (Nivel 5).
- Suite de tests automatizados (PHPUnit).
- Verificación de estándares de código (Laravel Pint).

---
**AuditCore Forensics** - *Transparencia e Integridad Financiera a escala corporativa.*