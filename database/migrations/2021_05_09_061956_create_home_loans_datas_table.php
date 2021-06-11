<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeLoansDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_loans_datas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->decimal('loan_amount', $precision = 20, $scale = 2);
            $table->decimal('int_rate', $precision = 20, $scale = 2);
            $table->decimal('loan_period', $precision = 20 , $scale = 2);
            $table->decimal('no_payments', $precision = 20, $scale = 2);
            $table->decimal('sch_payment', $precision = 20, $scale =2 );
            $table->date('start_date');
            $table->decimal('opt_payment', $precision = 20, $scale = 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_loans_datas');
    }
}
