<?php

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
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_type', ['direct', 'lipa_kidogo'])->default('direct');
            $table->string('selcom_order_id')->nullable();
            $table->string('selcom_trans_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->integer('installment_number')->nullable();
            $table->integer('total_installments')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
};
