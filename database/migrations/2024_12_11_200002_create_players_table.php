<?php

use App\Enum\PlayerStatusEnum;
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
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('championship_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('heart_team_name')->nullable();
            $table->string('championship_team_name');
            $table->string(column: 'wpp_number', length: 13);
            $table->date('birth_dt')->nullable();
            $table->tinyInteger('sex'); //enum sexo: Masculino, Feminino, Outro
            $table->tinyInteger('game_platform'); //enum platform: PLAYSTATION, PC, XBOX, MOBILE
            $table->tinyInteger('status')->default(PlayerStatusEnum::REGISTERED->value); //enum status player: INSCRITO, PENDENTE, APROVADO
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
