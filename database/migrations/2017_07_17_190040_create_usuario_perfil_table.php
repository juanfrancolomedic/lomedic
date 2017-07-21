<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioPerfilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ges_det_usuario_perfil', function (Blueprint $table) {
            /*Principal fields*/
            $table->integer('fk_id_usuario')->unsigned()->comment('Llave foranea al usuario');
            $table->integer('fk_id_perfil')->unsigned()->comment('Llave foranea al perfil');
            $table->boolean('activo')->default('1');

            /*Foreign keys*/
            $table->foreign('fk_id_usuario')->references('id_usuario')->on('ges_cat_usuarios');
            $table->foreign('fk_id_perfil')->references('id_perfil')->on('ges_cat_perfiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ges_det_usuario_perfil');
    }
}