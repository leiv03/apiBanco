<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrearTablas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('email')->unique();
            $table->timestamps();
            $table->integer('balance');

        });

        Schema::create('Token', function (Blueprint $table) {
            $table->foreignId('idUsuario')->references('id')->on('usuarios');
            $table->integer('token');
            $table->date('fecha');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
