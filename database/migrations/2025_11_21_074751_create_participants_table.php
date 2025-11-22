<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('challenge_id')->constrained()->onDelete('cascade');
            $table->integer('queue_position')->nullable();
            $table->timestamp('joined_at')->useCurrent();
            $table->enum('status', ['active', 'inactive', 'completed'])->default('active');
            $table->integer('join_attempt')->default(1);
            $table->timestamps();

            $table->unique(['user_id', 'challenge_id', 'join_attempt']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('participants');
    }
};