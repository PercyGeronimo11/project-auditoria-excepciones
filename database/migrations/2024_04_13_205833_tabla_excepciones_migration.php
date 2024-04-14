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
        Schema::create('tabla_excepciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_integridad");
            $table->string("data_key");
            $table->string("message");
            $table->foreign("id_integridad")->references("id")->on("tabla_integridad")->onDelete("cascade");
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('tabla_excepciones');
    }
};
