<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lipa_kidogo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('installment_amount', 10, 2);
            $table->integer('num_installments');
            $table->date('start_date');
            $table->enum('user_type', ['businessman', 'employed'])->nullable();
            $table->enum('payment_duration', ['daily', 'weekly', 'monthly'])->default('daily');
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lipa_kidogo');
    }
};