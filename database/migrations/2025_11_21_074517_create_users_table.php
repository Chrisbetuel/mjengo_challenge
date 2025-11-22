<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->string('phone_number', 20)->nullable();
            $table->string('nida_id', 20)->unique()->nullable();
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};