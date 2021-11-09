<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;
    protected $fillable = [
            'id_usuario',
            'user_type',
            'tipo_documento',
            'numero_documento',
            'departamento_expedicion',
            'municipio_expedicion',
            'nombre',
            'apellido',
            'fecha_nacimiento',
            'lugar_nacimiento',
            'genero',
            'direccion',
            'telefono',
            'email',
            'eps',
            'nombre_contacto_emergencia',
            'numero_contacto_emergencia',
            'id_empresa',
            'tipo_vinculacion',
            'nombre_padre',
            'celular_padre',
            'nombre_madre',
            'celular_madre',
            'estudia',
            'nombre_establecimiento',
            'tipo_establecimiento',
            'foto',
            'parentesco',
            'grado_escolar'
    ];
}
