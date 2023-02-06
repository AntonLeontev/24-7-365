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
        Schema::create('tariffs', function (Blueprint $table) {
            $table->id();
			$table->string('title');
			$table->unsignedTinyInteger('annual_rate');
			$table->unsignedSmallInteger('duration_month');
			$table->unsignedBigInteger('min_amount');
			$table->unsignedBigInteger('max_amount');
			$table->unsignedTinyInteger('getting_profit');
			$table->unsignedTinyInteger('getting_deposit');
			$table->softDeletes();
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
        Schema::dropIfExists('tariffs');
    }
};
