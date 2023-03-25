<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_settings', function (Blueprint $table) {
            $table->id();

            // Месяц с начала договора, с которого начинаются выплаты
            $table->unsignedTinyInteger('payments_start')->default(2);

            $table->string('organization_title', 100);
            $table->string('inn', 12);
            $table->string('kpp', 12);
            $table->string('ogrn', 15);
            $table->string('director', 100);
            $table->string('director_genitive', 100);
            $table->string('accountant', 100);
            $table->string('legal_address', 255);
            $table->string('actual_address', 255);
            $table->string('payment_account', 20);
            $table->string('correspondent_account', 20);
            $table->string('bik', 9);
            $table->string('bank', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_settings');
    }
};
