<?php

use App\Models\Contract;
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
        Schema::create('profitabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Contract::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
			$table->foreignUuid('payment_id')
				->references('id')
				->on('payments')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->unsignedInteger('amount');
            $table->date('accrued_at')->nullable();
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
        Schema::dropIfExists('profitabilities');
    }
};
