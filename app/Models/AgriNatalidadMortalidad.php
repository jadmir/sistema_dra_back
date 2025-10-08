<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgriNatalidadMortalidad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agri_natalidad_mortalidad';
    public $timestamps = true;

    protected $fillable = [
        'animal_id',
        'tipo',
        'concepto',
        'fecha',
        'observaciones',
        'usuario_id',
        'estado'
    ];

    protected $dates = ['deleted_at', 'created_at', 'updated_at'];

    protected $casts = [
        'fecha' => 'date',
        'estado' => 'boolean',
    ];

    // Relación con usuario
    public function animal()
    {
        return $this->belongsTo(AgriAnimal::class, 'animal_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
