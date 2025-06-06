<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('trajets', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false)->after('chauffeur_id');
        });
    }

    public function down()
    {
        Schema::table('trajets', function (Blueprint $table) {
            $table->dropColumn('is_recurring');
        });
    }
};
