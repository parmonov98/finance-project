<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyNetworthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_networths', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->decimal('home_value', $precision = 20, $scale = 2)->nullable();
            $table->decimal('home_app', $precision = 20, $scale = 2)->nullable();
            $table->decimal('cash', $precision = 20, $scale = 2)->nullable();
            $table->decimal('other_invest', $precision = 20, $scale = 2)->nullable();
            $table->date('date')->nullable();
            $table->boolean('passed')->default(false);
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monthly_networths');
    }
}
