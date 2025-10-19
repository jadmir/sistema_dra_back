<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubSector extends Model
{
    use HasFactory;

    protected $table = 'sub_sectores';

    protected $fillable = [
        'codigo',
        'descripcion',
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

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'sub_sector_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActivos($q)
    {
        return $q->where('estado', true);
    }
}
