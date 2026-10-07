<?php

use App\Enum\PaymentCheckoutProviderEnum;
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
            $table->integer('taxes_amount')->after('value')->nullable();
            $table->enum('checkout_provider', PaymentCheckoutProviderEnum::values())->after('net_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('taxes_amount');
            $table->dropColumn('checkout_provider');
        });
    }
};
