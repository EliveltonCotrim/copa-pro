<?php

use App\Enum\ChampionshipFormatEnum;
use App\Enum\ChampionshipStatusEnum;
use App\Enum\PlayerPlatformGameEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
            $table->enum('game_platform', PlayerPlatformGameEnum::values())->nullable();
            $table->integer('max_playes')->nullable();
            $table->enum('championship_format', ChampionshipFormatEnum::values())->nullable(); // 'cup', 'league', 'KNOCKOUT'
            $table->string('wpp_group_link')->nullable();
            $table->string('registration_link')->nullable();
            $table->text('information')->nullable();
            $table->enum('status', ChampionshipStatusEnum::values())->default(ChampionshipStatusEnum::REGISTRATION_CLOSED->value); // 'active', 'inactive', 'finished'

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
