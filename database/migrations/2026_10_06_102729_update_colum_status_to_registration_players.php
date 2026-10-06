<?php

use App\Enum\PaymentStatusEnum;
use App\Enum\RegistrationPlayerStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('registration_players', function (Blueprint $table) {
            $table->integer('status')->default(RegistrationPlayerStatusEnum::REGISTERED->value)->change();
            $table->integer('payment_status')->default(PaymentStatusEnum::PAYMENT_CREATED->value)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $oldStatus = [1, 2, 3, 4];

        DB::table('registration_players')
            ->whereNotIn('status', $oldStatus)
            ->update(['status' => RegistrationPlayerStatusEnum::REJECTED->value]);

        $oldPayment = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];

        DB::table('registration_players')
            ->whereNotIn('payment_status', $oldPayment)
            ->update(['payment_status' => PaymentStatusEnum::PAYMENT_CREATED->value]);

        Schema::table('registration_players', function (Blueprint $table) use ($oldStatus, $oldPayment) {
            $table->enum('status', $oldStatus)->default(RegistrationPlayerStatusEnum::REGISTERED->value)->change();
            $table->enum('payment_status', $oldPayment)->default(PaymentStatusEnum::PAYMENT_CREATED->value)->change();
        });
    }
};
