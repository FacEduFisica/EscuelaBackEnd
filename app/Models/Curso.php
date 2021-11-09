<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_programa',
        'id_profesor',
        'nombre',
        'descripcion',
        'cupos',
        'cupos_disponibles',
        'edad_min',
        'edad_max',
        'hora_inicial',
        'hora_final',
        'estado'
    ];
}
