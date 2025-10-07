<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgriSacaClase extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = true;

    protected $table = 'agri_saca_clases';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
        'usuario_id'
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'usuario_id');
    } 
}
