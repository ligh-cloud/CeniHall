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
        Schema::create('Reservation_siege' , function (Blueprint $table){
            $table->id();
            $table->foreignId('reservation_id')->constrained();
            $table->foreignId('siege_id')->constrained();
            $table->timestamps();
            $table->unique(['reservation_id', 'siege_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
