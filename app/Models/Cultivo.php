<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cultivo extends Model
{
    use HasFactory;

    protected $table = 'cultivos';

    protected $fillable =[
        'sub_grupo_id',
        'codigo',
        'descripcion',
        'usuario_id',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    public function subGrupo()
    {
        return $this->belongsTo(SubGrupo::class, 'sub_grupo_id');
    }

    public function scopeActivos($q)
    {
        return $q->where('estado', true);
    }

}
