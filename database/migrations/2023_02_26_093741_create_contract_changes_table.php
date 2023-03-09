<?php

use App\Models\Contract;
use App\Models\Tariff;
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
        Schema::create('contract_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Contract::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->tinyInteger('type');
            $table->foreignIdFor(Tariff::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->tinyInteger('status');

            $table->softDeletes();
            $table->timestamps();
            $table->dateTime('starts_at')->nullable();
			$table->unsignedSmallInteger('duration')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_changes');
    }
};
