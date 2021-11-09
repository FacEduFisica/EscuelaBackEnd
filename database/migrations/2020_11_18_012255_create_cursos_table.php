<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_programa');
            $table->unsignedBigInteger('id_profesor');
            $table->string('nombre');
            $table->string('descripcion');
            $table->bigInteger('edad_min');
            $table->bigInteger('edad_max');
            $table->bigInteger('cupos');
            $table->bigInteger('cupos_ocupados')->default(0);
            $table->time('hora_inicial');
            $table->time('hora_final');
            $table->enum('estado',['Abierto','Cerrado'])->default('Abierto');
            $table->foreign('id_programa')->references('id')->on('programas')->onDelete('cascade');
            $table->foreign('id_profesor')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('cursos');
    }
}
