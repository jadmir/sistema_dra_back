<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AgriVariedad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'agri_variedads';

    protected $fillable = [
        'nombre', 'descripcion', 'estado'
    ];

    protected $dates = ['deleted_at','created_at','updated_at'];

    // Relación con animales
    public function animales() {
        return $this->hasMany(\App\Models\AgriAnimal::class,'variedad_id');
    }
}
