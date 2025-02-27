<?php

use App\Enum\PaymentMethodEnum;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->nullable();
            $table->decimal('value', 10, 2);
            $table->decimal('net_value', 10, 2)->nullable();
            $table->enum('billing_type', PaymentMethodEnum::values())->nullable();
            $table->string('description')->nullable();
            $table->foreignId('registration_player_id')->constrained('registration_players');
            $table->integer('installments')->nullable();
            $table->date('date_created');
            $table->date('due_date')->nullable();
            $table->date('payment_Date')->nullable();
            $table->date('confirmed_date')->nullable();
            $table->enum('status', PaymentStatusEnum::values()); //ENUM
            $table->text('qr_code_64')->nullable();
            $table->text('qr_code')->nullable();
            $table->text('ticket_url')->nullable();
            $table->string('transaction_receipt_url')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
