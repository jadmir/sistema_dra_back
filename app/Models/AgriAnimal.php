<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgriAnimal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agri_animales';

    protected $fillable = [
        'codigo', 'variedad_id', 'edad', 'peso', 'estado', 'usuario_id'
    ];

    protected $dates = ['deleted_at','created_at','updated_at'];

    public function usuario() {
        return $this->belongsTo(\App\Models\Usuario::class,'usuario_id');
    }

    public function variedad() {
        return $this->belongsTo(\App\Models\AgriVariedad::class,'variedad_id');
    }
}
