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
        Schema::create('seances', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('salle_id')->constrained()->onDelete('cascade');
            $table->foreignId('movie_id')->constrained();
            $table->dateTime('start_time');
            $table->string('language');
            $table->enum('type' , ['normal' ,'vip']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seances');
    }
};
