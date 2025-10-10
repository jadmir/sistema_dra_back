<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'email',
        'dni',
        'nombre',
        'apellido',
        'direccion',
        'celular',
        'password',
        'rol_id',
        'activo'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function rol()
    {
        return $this->belongsTo(Rol::class, 'rol_id');
    }

    public function scopeActivos($query, $onlyActives = true)
    {
        if ($onlyActives) {
            $query->where('activo', 1);
        }
        return $query;
    }

    public function scopeSearch($query, ?string $q)
    {
        if (!$q) return $query;
        $q = trim($q);
        return $query->where(function ($w) use ($q) {
            $like = "%{$q}%";
            $w->where('email', 'LIKE', $like)
              ->orWhere('dni', 'LIKE', $like)
              ->orWhere('nombre', 'LIKE', $like)
              ->orWhere('apellido', 'LIKE', $like)
              ->orWhere('direccion', 'LIKE', $like)
              ->orWhere('celular', 'LIKE', $like);
        });
    }

    public function scopeFilter($query, array $filters)
    {
        $map = [
            'email'    => fn($q, $v) => $q->where('email', 'LIKE', "%{$v}%"),
            'dni'      => fn($q, $v) => $q->where('dni', (string)$v),
            'nombre'   => fn($q, $v) => $q->where('nombre', 'LIKE', "%{$v}%"),
            'apellido' => fn($q, $v) => $q->where('apellido', 'LIKE', "%{$v}%"),
            'direccion'=> fn($q, $v) => $q->where('direccion', 'LIKE', "%{$v}%"),
            'celular'  => fn($q, $v) => $q->where('celular', (string)$v),
            'rol_id'   => fn($q, $v) => $q->where('rol_id', (int)$v),
            'activo'   => fn($q, $v) => $q->where('activo', (int)$v),
        ];
        foreach ($filters as $k => $v) {
            if ($v === null || $v === '') continue;
            if (isset($map[$k])) {
                $map[$k]($query, $v);
            }
        }
        return $query;
    }

    public function scopeSortBy($query, ?string $column, ?string $dir)
    {
        $allowed = ['id', 'nombre', 'apellido', 'email', 'dni', 'rol_id', 'activo', 'created_at'];
        $dir = strtolower($dir ?? 'asc');
        if (!in_array($dir, ['asc', 'desc'])) $dir = 'asc';
        if (in_array($column, $allowed)) {
            return $query->orderBy($column, $dir);
        }
        return $query->orderBy('id', 'asc');
    }

    // Verifica si el usuario tiene un permiso específico
    public function tienePermiso(string $permisoNombre): bool
    {
        return $this->rol && $this->rol->tienePermiso($permisoNombre);
    }
}
