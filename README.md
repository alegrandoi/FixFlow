# FixFlow - Asset Maintenance Management System

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-red.svg" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PHP-8.3-blue.svg" alt="PHP 8.3">
  <img src="https://img.shields.io/badge/TailwindCSS-3.x-38B2AC.svg" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License MIT">
</p>

## 📋 Descripción

**FixFlow** es un sistema de gestión de mantenimiento de activos desarrollado con Laravel 11. Permite a las organizaciones rastrear sus activos, registrar mantenimientos preventivos y correctivos, generar informes PDF detallados y exportar datos financieros.

### ✨ Características Principales

- 🔐 **Autenticación y Autorización**: Sistema de roles (Admin/Técnico)
- 📦 **Gestión de Activos**: CRUD completo con estados y ubicaciones
- 🔧 **Registro de Mantenimientos**: Preventivos y correctivos
- 📊 **Informes y Estadísticas**: Dashboard con métricas clave
- 📄 **Generación de PDFs**: Historial detallado por activo
- 📈 **Exportación CSV**: Costos mensuales
- 🔍 **Búsqueda y Filtros**: Encuentra activos y mantenimientos rápidamente
- 📱 **Responsive Design**: Compatible con dispositivos móviles

## 🏗️ Arquitectura

```
app/
├── Enums/              # Estados de activos y tipos de mantenimiento
├── Http/
│   ├── Controllers/    # AssetController, MaintenanceController, ReportController
│   ├── Requests/       # Validaciones (Form Requests)
│   └── Policies/       # Autorización (AssetPolicy, MaintenancePolicy)
├── Models/             # Eloquent Models (Asset, Maintenance, User)
└── Services/           # Lógica de negocio (ReportService)
```

## 🚀 Instalación

### Requisitos Previos

- PHP 8.3 o superior
- Composer
- Node.js & NPM
- MySQL/MariaDB o SQLite

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/alegrandoi/FixFlow.git
   cd FixFlow
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   npm install
   ```

3. **Configurar el entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurar la base de datos**
   
   Edita el archivo `.env` y configura tu base de datos:
   ```
   DB_CONNECTION=sqlite  # o mysql
   DB_DATABASE=/ruta/absoluta/database/database.sqlite
   ```

5. **Ejecutar migraciones y seeders**
   ```bash
   php artisan migrate --seed
   ```
   
   Esto creará:
   - 1 usuario Admin (admin@fixflow.com / password)
   - 5 usuarios Técnicos
   - 20 activos de ejemplo
   - Múltiples registros de mantenimiento

6. **Compilar assets**
   ```bash
   npm run build
   ```

7. **Iniciar el servidor**
   ```bash
   php artisan serve
   ```

   Accede a http://localhost:8000

## 👥 Usuarios de Prueba

| Email | Contraseña | Rol |
|-------|-----------|-----|
| admin@fixflow.com | password | Admin |

Los técnicos son generados automáticamente con contraseña `password`.

## 📖 Guía de Uso

### Gestión de Activos

1. **Listar Activos**: Navega a "Assets" en el menú
2. **Crear Activo**: Click en "Create New Asset" (solo Admin)
3. **Ver Detalles**: Click en un activo para ver su historial de mantenimiento
4. **Editar/Eliminar**: Disponible solo para administradores

### Registro de Mantenimientos

1. **Nuevo Mantenimiento**: Navega a "Maintenances" > "Create New Maintenance"
2. **Seleccionar Activo**: Elige el activo a mantener
3. **Tipo de Mantenimiento**: Preventivo o Correctivo
4. **Registrar Costos**: Ingresa el costo del mantenimiento

### Informes

1. **Dashboard**: Navega a "Reports" > "Dashboard"
2. **PDF por Activo**: En la vista de un activo, click "Download PDF Report"
3. **Costos Mensuales**: "Reports" > "Monthly Costs" > "Export CSV"

## 🧪 Testing

Ejecutar la suite completa de tests:

```bash
php artisan test
```

Tests disponibles:
- Autenticación (Laravel Breeze)
- CRUD de Activos
- CRUD de Mantenimientos
- Políticas de autorización
- Generación de reportes

## 🔒 Roles y Permisos

| Acción | Admin | Técnico |
|--------|-------|---------|
| Ver Activos | ✅ | ✅ |
| Crear Activos | ✅ | ❌ |
| Editar Activos | ✅ | ❌ |
| Eliminar Activos | ✅ | ❌ |
| Ver Mantenimientos | ✅ | ✅ |
| Crear Mantenimientos | ✅ | ✅ |
| Eliminar Mantenimientos | ✅ | ✅ (solo propios) |
| Ver Reportes | ✅ | ✅ |

## 📚 Tecnologías

- **Backend**: Laravel 11
- **Frontend**: Blade + TailwindCSS
- **Auth**: Laravel Breeze
- **PDF**: barryvdh/laravel-dompdf
- **Database**: SQLite/MySQL
- **Testing**: PHPUnit/Pest

## 🛠️ Estructura de Base de Datos

### Tabla `users`
- Roles: `admin`, `technician`
- Relación: `hasMany` maintenances

### Tabla `assets`
- Estados: `active`, `broken`, `under_maintenance`, `retired`
- Relación: `hasMany` maintenances

### Tabla `maintenances`
- Tipos: `preventive`, `corrective`
- Relaciones: `belongsTo` asset, `belongsTo` user (technician)

## 📝 Convenciones de Código

Este proyecto sigue las mejores prácticas de Laravel:

- ✅ Form Requests para validación
- ✅ Policies para autorización
- ✅ Services para lógica de negocio compleja
- ✅ Enums para valores constantes
- ✅ Scopes en modelos para consultas comunes
- ✅ Factories y Seeders para datos de prueba

## 🤝 Contribuir

Las contribuciones son bienvenidas. Por favor:

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👤 Autor

- **Alejandro Grando** - [alegrandoi](https://github.com/alegrandoi)

## 🙏 Agradecimientos

- Laravel Framework
- Laravel Breeze
- DomPDF
- TailwindCSS Community

---

**¿Necesitas ayuda?** Abre un [issue](https://github.com/alegrandoi/FixFlow/issues) en GitHub.

