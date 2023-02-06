<?php

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
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('title', 100);
            $table->string('type', 100);
            $table->string('inn', 12);
            $table->string('kpp', 12);
            $table->string('ogrn', 12);
            $table->string('director', 100);
            $table->string('director_post', 100);
            $table->string('accountant', 100);
            $table->string('legal_address', 255);
            $table->string('actual_address', 255);
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
        Schema::dropIfExists('organizations');
    }
};
