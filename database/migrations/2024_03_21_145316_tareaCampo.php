<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      
        Schema::create('TareaCampo', function (Blueprint $table) {
            $table->id();
            $table->string('campo');
            $table->string('condicion');
            $table->string('baseDatos');
            $table->date('fecha');
            $table->string('tabla');
            $table->tinyInteger('estado');
            $table->string('condicion_text');
            $table->string('longitud');
            $table->string('tipoValidar');
         
            $table->tinyInteger('null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TareaCampo');

    }
};
