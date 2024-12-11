<?php

use App\Enum\ChampionshipFormatEnum;
use App\Enum\ChampionshipStatusEnum;
use App\Enum\ProjectStatusEnum;
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
        Schema::create('championships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('registration_fee', 10, 2)->default(0);
            $table->string('banner_path')->nullable();
            $table->string('regulation_path')->nullable();
            $table->string('game_platform')->nullable();
            $table->string('max_playes')->nullable();
            $table->tinyInteger('championship_format')->nullable(); // 'cup', 'league', 'KNOCKOUT'
            $table->string('wpp_group_link')->nullable();
            $table->string('registration_link')->nullable();
            $table->text('information')->nullable();
            $table->tinyInteger('status')->default(ChampionshipStatusEnum::INACTIVE->value); // 'active', 'inactive', 'finished'

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('championships');
    }
};
