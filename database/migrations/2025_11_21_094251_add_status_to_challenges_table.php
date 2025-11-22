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
        Schema::table('challenges', function (Blueprint $table) {
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('daily_amount', 10, 2);
            $table->integer('max_participants');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'inactive', 'completed'])->default('active');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('challenges', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['name', 'description', 'daily_amount', 'max_participants', 'start_date', 'end_date', 'status', 'created_by']);
        });
    }
};
