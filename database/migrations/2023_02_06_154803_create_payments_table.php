<?php

use App\Models\Account;
use App\Models\Contract;
use App\Models\Payment;
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
            $table->id();
            $table->foreignIdFor(Account::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(Contract::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->unsignedTinyInteger('type');
			$table->unsignedTinyInteger('status')->default(Payment::STATUS_PENDING);
			$table->date('planned_at')->nullable();
			$table->dateTime('paid_at')->nullable();
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
