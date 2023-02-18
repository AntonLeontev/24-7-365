<?php

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
            $table->id();
            $table->foreignIdFor(Account::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(Contract::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->unsignedTinyInteger('type');
			$table->unsignedTinyInteger('status');
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
