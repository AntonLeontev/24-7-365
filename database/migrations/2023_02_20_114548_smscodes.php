<?php

use App\Models\Smscode;
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

        Schema::create('smscodes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('code', Smscode::CODE_LENGTH);
            $table->unsignedTinyInteger('operation_type')->default(Smscode::PHONE_CONFIRMATION);
            $table->unsignedTinyInteger('status')->default(Smscode::STATUS_PENDING);

            $table->string('phone', 12)->nullable();

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
        Schema::dropIfExists('smscodes');
        //
    }
};
