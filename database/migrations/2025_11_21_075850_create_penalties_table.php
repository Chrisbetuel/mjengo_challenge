<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('challenge_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('penalty_type', ['late_payment', 'missed_payment', 'group_violation']);
            $table->decimal('amount', 10, 2);
            $table->text('reason')->nullable();
            $table->enum('status', ['active', 'resolved', 'appealed'])->default('active');
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penalties');
    }
};