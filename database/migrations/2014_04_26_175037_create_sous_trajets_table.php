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
        Schema::create('sous_trajets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trajet_id')->constrained('trajets')->onDelete('cascade');
            $table->string('departure_city');
            $table->dateTime('departure_time');
            $table->string('destination_city');
            $table->dateTime('arrival_time');
            $table->decimal('price', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sous_trajets');
    }
};
