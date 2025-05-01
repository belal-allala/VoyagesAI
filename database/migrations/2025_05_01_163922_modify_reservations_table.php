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
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropForeign(['sous_trajet_id']); 
            $table->dropColumn('sous_trajet_id'); 
            $table->foreignId('trajet_id')->constrained('trajets')->onDelete('cascade')->after('user_id'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->foreignId('sous_trajet_id')->constrained('sous_trajets')->onDelete('cascade');
            $table->dropForeign(['trajet_id']);
            $table->dropColumn('trajet_id');
        });
    }
};