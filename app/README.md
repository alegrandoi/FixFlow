# FixFlow - Estructura de Aplicación

Este documento describe la organización y estructura de directorios de la aplicación FixFlow.

## Estructura de Directorios

### 📁 Http/Controllers/
**Propósito:** Maneja las peticiones HTTP y devuelve las respuestas.

Los controladores deben:
- Recibir la petición HTTP
- Delegar la lógica de negocio a los Services
- Devolver una respuesta (vista o JSON)
- NO contener lógica de negocio compleja

**Ejemplo:**
```php
public function store(StoreAssetRequest $request)
{
    $asset = $this->assetService->create($request->validated());
    return new AssetResource($asset);
}
```

### 📁 Http/Requests/
**Propósito:** ¡VITAL! Contiene las validaciones de formularios (Form Requests).

Las Form Requests deben:
- Validar los datos de entrada
- Autorizar la acción (método `authorize()`)
- Personalizar mensajes de error
- Preparar datos si es necesario

**Ejemplo:**
```php
class StoreAssetRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'status' => ['required', new Enum(AssetStatus::class)],
        ];
    }
}
```

### 📁 Http/Resources/
**Propósito:** Transformación de datos para APIs (respuestas JSON).

Los Resources deben:
- Transformar modelos a arrays/JSON
- Controlar qué datos se exponen
- Formatear datos para la respuesta

**Ejemplo:**
```php
class AssetResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status->value,
        ];
    }
}
```

### 📁 Models/
**Propósito:** Modelos Eloquent con relaciones y scopes.

Los modelos deben contener:
- Definiciones de relaciones (`hasMany`, `belongsTo`, etc.)
- Scopes para consultas reutilizables
- Casts y mutators
- NO lógica de negocio compleja

**Ejemplo:**
```php
class Asset extends Model
{
    protected $casts = [
        'status' => AssetStatus::class,
    ];

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', AssetStatus::Active);
    }
}
```

### 📁 Services/
**Propósito:** Lógica de negocio compleja que va más allá de operaciones CRUD simples.

Los servicios deben:
- Contener lógica de negocio que involucre múltiples pasos o validaciones
- Orquestar operaciones entre múltiples modelos
- Realizar cálculos complejos y transformaciones de datos
- Interactuar con APIs externas
- Manejar transacciones complejas

**Cuándo crear un Service:**
- Si la lógica del controller supera 10-15 líneas
- Si necesitas reutilizar la misma lógica en varios controllers
- Si la operación involucra múltiples modelos
- Si requieres cálculos o validaciones complejas

**Ejemplo:**
```php
class ReportService
{
    public function generateMaintenanceReport(Asset $asset)
    {
        // Lógica compleja de generación de reportes
        $maintenances = $asset->maintenances()
            ->with('technician')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return $this->calculateMetrics($maintenances);
    }
}
```

### 📁 Enums/
**Propósito:** Enumeraciones para estados y constantes.

Los enums deben:
- Definir estados posibles (Active, Broken, etc.)
- Proporcionar métodos helper
- Usar PHP 8.1+ Enums

**Ejemplo:**
```php
enum AssetStatus: string
{
    case Active = 'active';
    case Broken = 'broken';
    case InMaintenance = 'in_maintenance';
    case Retired = 'retired';

    public function label(): string
    {
        return match($this) {
            self::Active => 'Activo',
            self::Broken => 'Averiado',
            self::InMaintenance => 'En Mantenimiento',
            self::Retired => 'Retirado',
        };
    }
}
```

### 📁 Policies/
**Propósito:** Autorización - ¿Quién puede hacer qué?

Las policies deben:
- Definir permisos de acceso
- Verificar si un usuario puede realizar una acción
- Separar la lógica de autorización de los controladores

**Ejemplo:**
```php
class AssetPolicy
{
    public function update(User $user, Asset $asset)
    {
        return $user->isAdmin() || $user->id === $asset->responsible_id;
    }

    public function delete(User $user, Asset $asset)
    {
        return $user->isAdmin();
    }
}
```

## Flujo de una Petición Típica

1. **Request** → Llega al `Controller`
2. **Validation** → Se valida con `FormRequest`
3. **Authorization** → Se verifica con `Policy`
4. **Business Logic** → Se ejecuta en `Service`
5. **Data Access** → Se consulta el `Model`
6. **Response** → Se formatea con `Resource` y se devuelve

## Beneficios de esta Estructura

✅ **Separación de Responsabilidades:** Cada capa tiene un propósito específico
✅ **Mantenibilidad:** Código más fácil de mantener y actualizar
✅ **Testabilidad:** Componentes independientes son más fáciles de probar
✅ **Escalabilidad:** Estructura clara facilita el crecimiento del proyecto
✅ **Reutilización:** Services y Policies pueden reutilizarse en diferentes contextos

## Mejores Prácticas

1. **Mantén los Controllers delgados** - Solo manejan HTTP, delegando todo lo demás
2. **Usa FormRequests SIEMPRE** - Nunca valides en el controller
3. **Crea Services para lógica compleja** - Si un controller tiene más de 5 líneas de lógica, extrae a un Service
4. **Define Policies para autorización** - No uses `if ($user->isAdmin())` en controllers
5. **Usa Enums para estados** - Evita strings mágicos en tu código
6. **Transforma con Resources** - Controla exactamente qué datos expones en APIs
