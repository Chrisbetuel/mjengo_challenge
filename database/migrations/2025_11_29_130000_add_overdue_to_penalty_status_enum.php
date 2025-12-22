<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('penalties', function (Blueprint $table) {
            $table->enum('status', ['active', 'resolved', 'appealed', 'overdue'])->default('active')->change();
        });
    }

    public function down()
    {
        Schema::table('penalties', function (Blueprint $table) {
            $table->enum('status', ['active', 'resolved', 'appealed'])->default('active')->change();
        });
    }
};
