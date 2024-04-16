<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tabla_integridad', function (Blueprint $table) {
            $table->id();
            $table->string('table');
            $table->string('column_foreignkey');
            $table->string('table_refer');
            $table->string('column_primarykey'); 
            $table->String('name_bd');
            $table->String('type_db');
            $table->tinyInteger('estado');
            $table->string("user");
            $table->string("url_doc")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tabla_integridad');
    }
};
