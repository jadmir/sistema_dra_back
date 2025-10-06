<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'rol_id');
    }

    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'rol_permisos', 'rol_id', 'permiso_id');
    }

    //asignar varios permisos a un rol
    public function asignarPermisos(array $permisos)
    {
        $this->permisos()->sync($permisos);
    }

    //verificar si el rol tiene permisos especificos
    public function tienePermiso($permisoNombre): bool
    {
        return $this->permisos()->where('nombre', $permisoNombre)->exists();
    }
}
