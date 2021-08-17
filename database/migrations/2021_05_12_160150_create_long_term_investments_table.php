<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLongTermInvestmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('long_term_investments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->decimal('return_on_invest', $precision = 20 , $scale = 2);
            $table->decimal('fees', $precision = 20 , $scale = 2);
            $table->decimal('monthly_account_fee', $precision = 20 , $scale = 2);
            $table->decimal('inflation', $precision = 20 , $scale = 2);
            $table->decimal('monthly_invest', $precision = 20 , $scale = 2);
            $table->decimal('interest', $precision = 20 , $scale = 2);
            $table->decimal('total_interest', $precision = 20 , $scale = 2);
            $table->decimal('after_fees', $precision = 20 , $scale = 2);
            $table->decimal('total_invested', $precision = 20 , $scale = 2);
            $table->date('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('long_term_investments');
    }
}
