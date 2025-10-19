<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupos';

    protected $fillable = [
        'codigo',
        'descripcion',
        'sub_sector_id',
        'usuario_id',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    public function subSector()
    {
        return $this->belongsTo(SubSector::class, 'sub_sector_id');
    }

    public function subgrupos()
    {
        return $this->hasMany(SubGrupo::class, 'grupo_id');
    }

    public function scopeActivos($q)
    {
        return $q->where('estado', true);
    }
}
