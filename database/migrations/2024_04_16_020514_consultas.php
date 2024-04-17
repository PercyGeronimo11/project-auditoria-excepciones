<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{


    public function up(): void
    {
            Schema::create('consultas', function (Blueprint $table) {
                $table->id();
                $table->string('nombre', 100);
                $table->longText('consulta');
                $table->string('nombre_db', 255);
                $table->timestamp('fecha')->nullable();
                $table->timestamps();
            });
        
    }


    public function down(): void
    {

        Schema::dropIfExists('consultas');

    }
};
