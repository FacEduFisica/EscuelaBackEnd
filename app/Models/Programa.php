<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Curso;

class Programa extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'descripcion',
        'valor',
        'estado'
    ];

    public function cursos(){
        return $this->hasMany(Curso::class);
    }
}
