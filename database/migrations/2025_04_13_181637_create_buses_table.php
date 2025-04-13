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
        Schema::create('buses', function (Blueprint $table) {
            $table->id(); 
            $table->string('departure_city'); 
            $table->string('destination_city'); 
            $table->dateTime('departure_time'); 
            $table->decimal('price', 8, 2); 
            $table->integer('capacity'); 
            $table->integer('available_seats');
            $table->string('company_name'); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};