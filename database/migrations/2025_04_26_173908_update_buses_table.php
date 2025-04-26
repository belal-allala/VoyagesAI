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
        Schema::table('buses', function (Blueprint $table) {
            $table->string('name'); 
            $table->foreignId('company_id')->constrained('companies'); 

            $table->dropColumn(['departure_city', 'destination_city', 'departure_time']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn(['name', 'company_id']); 

            $table->string('departure_city'); 
            $table->string('destination_city'); 
            $table->dateTime('departure_time'); 
        });
    }
};