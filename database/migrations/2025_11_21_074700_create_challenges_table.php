<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('daily_amount', 10, 2);
            $table->integer('max_participants')->default(90);
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'inactive', 'pending', 'completed'])->default('active');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('challenges');
    }
};