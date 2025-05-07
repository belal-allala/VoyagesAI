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
        Schema::table('compagnies', function (Blueprint $table) {
            $table->string('telephone')->nullable()->after('email'); 
            $table->string('adresse')->nullable()->after('telephone'); 
            $table->text('description')->nullable()->after('adresse'); 
            $table->string('logo')->nullable()->after('description'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compagnies', function (Blueprint $table) {
            $table->dropColumn('logo');
            $table->dropColumn('description');
            $table->dropColumn('adresse');
            $table->dropColumn('telephone');
        });
    }
};