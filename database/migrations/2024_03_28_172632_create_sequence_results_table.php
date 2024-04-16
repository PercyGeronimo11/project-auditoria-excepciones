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
        Schema::create('sequence_results', function (Blueprint $table) {
            $table->id();
            $table->string("bdManager");
            $table->string("dbName");
            $table->string("tableName");
            $table->string("field");
            $table->string("sequenceType");
            $table->string("sequenceOrder");
            $table->integer("increment");
            $table->string("state");
            $table->string("user");
            $table->string("observation")->nullable();
            $table->string("url_doc")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sequence_results');
    }
};
