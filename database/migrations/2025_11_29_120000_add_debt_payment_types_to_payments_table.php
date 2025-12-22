<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_type ENUM('direct', 'lipa_kidogo', 'penalty', 'debt_payment', 'debt_installment') NOT NULL DEFAULT 'direct'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN payment_type ENUM('direct', 'lipa_kidogo', 'penalty') NOT NULL DEFAULT 'direct'");
    }
};
