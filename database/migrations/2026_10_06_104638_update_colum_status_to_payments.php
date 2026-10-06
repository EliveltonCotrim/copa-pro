<?php

use App\Enum\PaymentStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->integer('status')->default(PaymentStatusEnum::PAYMENT_CREATED->value)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $oldPayment = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];

        DB::table('payments')
            ->whereNotIn('status', $oldPayment)
            ->update(['status' => PaymentStatusEnum::PAYMENT_CREATED->value]);

        Schema::table('payments', function (Blueprint $table) use ($oldPayment) {
            $table->enum('status', $oldPayment)->default(PaymentStatusEnum::PAYMENT_CREATED->value)->change();
        });
    }
};
