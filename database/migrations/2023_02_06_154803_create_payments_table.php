<?php

use App\Enums\PaymentStatus;
use App\Models\Account;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
			$table->unsignedInteger('number')->index();
            $table->foreignIdFor(Account::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(Contract::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->string('type', 20);
            $table->string('status', 20)->default(PaymentStatus::pending->value);
            $table->date('planned_at')->nullable();
            $table->date('paid_at')->nullable();
            $table->text('description');
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
        Schema::dropIfExists('payments');
    }
};
