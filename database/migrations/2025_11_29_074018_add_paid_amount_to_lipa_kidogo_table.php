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
        if (!Schema::hasColumn('lipa_kidogo', 'paid_amount')) {
            Schema::table('lipa_kidogo', function (Blueprint $table) {
                $table->decimal('paid_amount', 10, 2)->default(0)->after('total_amount');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lipa_kidogo', function (Blueprint $table) {
            $table->dropColumn('paid_amount');
        });
    }
};
