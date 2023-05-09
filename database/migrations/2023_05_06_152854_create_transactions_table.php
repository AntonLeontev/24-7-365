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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('operation_id', 128)->unique();
            $table->string('direction');
            $table->string('purpose');
            $table->integer('amount');
            $table->string('currency');

            $table->string('payer_account');
            $table->string('payer_name');
            $table->string('payer_inn');
            $table->string('payer_kpp');
            $table->string('payer_bank_name');
            $table->string('payer_bank_bic');
            $table->string('payer_bank_corr_account');

            $table->string('payee_account');
            $table->string('payee_name');
            $table->string('payee_inn');
            $table->string('payee_kpp');
            $table->string('payee_bank_name');
            $table->string('payee_bank_bic');
            $table->string('payee_bank_corr_account');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
