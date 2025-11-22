<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('challenges', 'status')) {
            Schema::table('challenges', function (Blueprint $table) {
                $table->enum('status', ['active', 'inactive', 'pending', 'completed'])->default('active')->after('end_date');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('challenges', 'status')) {
            Schema::table('challenges', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
