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
        Schema::create('tabla_integridad', function (Blueprint $table) {
            $table->id();
            $table->string('table');
            $table->string('column_foreignkey');
            $table->string('table_refer');
            $table->string('column_primarykey'); 
            $table->tinyInteger('estado');
            $table->date('fecha');
            $table->String('name_bd');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabla_integridad');
    }
};
