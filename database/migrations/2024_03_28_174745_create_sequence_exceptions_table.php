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
        Schema::create('sequence_exceptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("sequence_result_id");
            $table->string("message");
            $table->string("location");
            $table->foreign("sequence_result_id")->references("id")->on("sequence_results")->onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sequence_exceptions');
    }
};
