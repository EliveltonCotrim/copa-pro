<?php

use App\Enum\PlayerPlatformGameEnum;
use App\Enum\PlayerSexEnum;
use App\Enum\PlayerStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->string('nickname', 50)->nullable();
            $table->string('heart_team_name')->nullable();
            $table->date('birth_dt')->nullable();
            $table->enum('sex', PlayerSexEnum::values());
            $table->string('phone', 20);
            $table->text('bio')->nullable();
            $table->enum('status', PlayerStatusEnum::values())->default(PlayerStatusEnum::ACTIVE);
            $table->enum('game_platform', PlayerPlatformGameEnum::values());

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};
