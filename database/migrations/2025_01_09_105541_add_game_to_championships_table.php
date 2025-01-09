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
        Schema::table('championships', function (Blueprint $table) {
            $table->tinyInteger('game')->after('game_platform'); // enum
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('championships', function (Blueprint $table) {
            $table->dropColumn('game');
        });
    }
};
