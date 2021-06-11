<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgram5YRNetworthsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program5_y_r_networths', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->decimal('house_loan', $precision = 20, $scale = 2)->nullable();
            $table->decimal('home_worth', $precision = 20, $scale = 2)->nullable();
            $table->decimal('invest_super', $precision = 20, $scale = 2)->nullable();
            $table->decimal('cash', $precision = 20, $scale = 2)->nullable();
            $table->decimal('invest_personal', $precision = 20, $scale = 2)->nullable();
            $table->decimal('long_term_invest', $precision = 20, $scale = 2)->nullable();

            $table->decimal('real_house_loan', $precision = 20, $scale = 2)->nullable();
            $table->decimal('real_home_worth', $precision = 20, $scale = 2)->nullable();
            $table->decimal('real_invest_super', $precision = 20, $scale = 2)->nullable();
            $table->decimal('real_cash', $precision = 20, $scale = 2)->nullable();
            $table->decimal('real_invest_personal', $precision = 20, $scale = 2)->nullable();
            $table->decimal('real_long_term_invest', $precision = 20, $scale = 2)->nullable();

            $table->boolean('approximate')->nullabe()->default(false);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('program5_y_r_networths');
    }
}
