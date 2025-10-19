<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubGrupo extends Model
{
    use HasFactory;

    protected $table = 'sub_grupos';

    protected $fillable = [
        'grupo_id',
        'codigo',
        'descripcion',
        'usuario_id',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    public function cultivos()
    {
        return $this->hasMany(Cultivo::class, 'sub_grupo_id');
    }

    public function scopeActivos($q)
    {
        return $q->where('estado', true);
    }
}
