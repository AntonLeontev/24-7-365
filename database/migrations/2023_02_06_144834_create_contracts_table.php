<?php

use App\Models\Contract;
use App\Models\Organization;
use App\Models\Tariff;
use App\Models\User;
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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(Organization::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(Tariff::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
			$table->unsignedBigInteger('amount');
			$table->unsignedTinyInteger('status')->default(Contract::ACTIVE);
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
        Schema::dropIfExists('contracts');
    }
};
