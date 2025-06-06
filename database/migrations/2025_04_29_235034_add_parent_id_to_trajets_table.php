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
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('trajets')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('trajets', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
        });
    }
};
