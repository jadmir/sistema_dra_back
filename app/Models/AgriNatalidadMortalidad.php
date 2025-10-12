<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgriNatalidadMortalidad extends Model
{
    use HasFactory;

    protected $table = 'agri_natalidad_mortalidad';

    protected $fillable = [
        'tipo',
        'concepto',
        'observaciones',
        'usuario_id',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
