<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->enum('user_type',['Profesor','Adulto','Niño']);
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_empresa')->nullable();
            $table->enum('tipo_documento',['CC', 'TI', 'CE', 'RC', 'Otro']);
            $table->bigInteger('numero_documento');
            $table->string('departamento_expedicion');
            $table->string('municipio_expedicion');
            $table->string('nombre');
            $table->string('apellido');
            $table->date('fecha_nacimiento');
            $table->string('lugar_nacimiento');
            $table->enum('genero', ['Masculino', 'Femenino', 'Otro']);
            $table->string('direccion');
            $table->string('telefono');
            $table->string('email')->nullable();
            $table->string('eps');
            $table->string('parentesco')->nullable();
            $table->string('nombre_contacto_emergencia')->nullable();
            $table->bigInteger('numero_contacto_emergencia')->nullable();
            $table->string('tipo_vinculacion')->nullable();
            $table->string('nombre_padre')->nullable();
            $table->string('celular_padre')->nullable();
            $table->string('nombre_madre')->nullable();
            $table->string('celular_madre')->nullable();
            $table->enum('estudia',['Si','No']);
            $table->enum('grado_escolar',['Primaria incompleta','Primaria completa','Secundaria incompleta','Secundaria completa','Universitario','Otro'])->nullable();
            $table->string('nombre_establecimiento')->nullable();
            $table->string('tipo_establecimiento')->nullable();
            $table->longText('foto')->nullable();
            $table->longText('copia_tarjeta_identidad')->nullable();
            $table->longText('certificado_eps')->nullable();
            $table->longText('certificado_medico')->nullable();
            $table->foreign('id_usuario')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_empresa')->references('id')->on('empresas')->onDelete('cascade');
            $table->timestamps();
        });
    }
                                            
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
