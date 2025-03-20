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
        Schema::create('sieges', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('salle_id')->constrained()->onDelete('cascade');
            $table->boolean('status')->default('true');
            $table->integer('siege_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sieges');
    }
};
