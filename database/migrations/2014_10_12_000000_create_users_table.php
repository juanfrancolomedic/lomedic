<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ges_cat_usuarios', function (Blueprint $table) {
            /*Campos principales*/
            $table->increments('id_usuario');
            $table->string('usuario','20')->unique()->comment('Nombre de usuario');
            $table->string('nombre_corto','100')->unique()->comment('Nombre corto de la persona');
            $table->integer('fk_id_empleado')->comment('ID de empleado recursos humanos')->nullable();
            /*$table->string('email')->unique();*/
            $table->string('password','60')->comment('Contrasena de acceso');
            $table->rememberToken();

            /*Campos generales*/
            $table->boolean('activo')->default('1');
            $table->boolean('eliminar')->default('0');

            $table->integer('fk_id_usuario_crea');
            $table->timestamp('fecha_crea')->default(DB::raw('now()'));

            $table->integer('fk_id_usuario_actualiza')->nullable();
            $table->timestamp('fecha_actualiza')->nullable();

            $table->integer('fk_id_usuario_elimina')->nullable();
            $table->timestamp('fecha_elimina')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ges_cat_usuarios');
    }
}
