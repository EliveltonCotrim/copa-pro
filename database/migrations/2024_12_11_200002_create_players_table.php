<?php

use App\Enum\ChampionshipGamesEnum;
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
            $table->string('name');
            $table->string('email')->unique();
            $table->string('heart_team_name')->nullable();
            $table->string('championship_team_name');
            $table->string(column: 'wpp_number', length: 25);
            $table->date('birth_dt')->nullable();
            $table->enum('sex', PlayerSexEnum::values()); //enum sexo: Masculino, Feminino, Outro
            $table->enum('game_platform', PlayerPlatformGameEnum::values()); //enum platform: PLAYSTATION, PC, XBOX, MOBILE
            $table->enum('status', RegistrationPlayerStatusEnum::values())->default(RegistrationPlayerStatusEnum::REGISTERED->value); //enum status player: INSCRITO, PENDENTE, APROVADO
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
