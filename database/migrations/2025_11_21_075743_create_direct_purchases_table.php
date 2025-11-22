<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('direct_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->text('delivery_address');
            $table->string('phone_number', 20);
            $table->enum('status', ['pending', 'paid', 'shipped', 'delivered', 'cancelled'])->default('pending');
            $table->string('payment_reference')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('direct_purchases');
    }
};