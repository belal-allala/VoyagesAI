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
        Schema::create('recurring_patterns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trajet_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['daily', 'weekly', 'monthly', 'custom']);
            $table->integer('interval')->default(1); 
            $table->json('days_of_week')->nullable(); 
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recurring_patterns');
    }
};
