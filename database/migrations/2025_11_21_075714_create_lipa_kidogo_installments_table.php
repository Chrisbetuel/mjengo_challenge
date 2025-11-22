<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ensure leftover partial table (from previous failed run) is removed
        Schema::dropIfExists('lipa_kidogo_installments');

        Schema::create('lipa_kidogo_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lipa_kidogo_id')->constrained('lipa_kidogo')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->integer('installment_number');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending');
            $table->date('paid_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('lipa_kidogo_installments');
    }
};