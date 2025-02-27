<?php

use App\Enum\ChampionshipGamesEnum;
use App\Enum\PaymentStatusEnum;
use App\Enum\PlayerPlatformGameEnum;
use App\Enum\PlayerSexEnum;
use App\Enum\RegistrationPlayerStatusEnum;
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
        Schema::create('registration_players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('championship_id')->constrained()->cascadeOnDelete();
            $table->foreignId('player_id')->constrained('players')->cascadeOnDelete();
            $table->string('championship_team_name');
            $table->enum('status', RegistrationPlayerStatusEnum::values())->default(RegistrationPlayerStatusEnum::REGISTERED->value);
            $table->enum('payment_status', PaymentStatusEnum::values())->default(PaymentStatusEnum::PAYMENT_CREATED);
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
