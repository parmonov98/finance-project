<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_loans', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('pmt_no');
            $table->date('pay_date');
            $table->bigInteger('beg_balance');
            $table->bigInteger('sch_payment');
            $table->bigInteger('ext_payment');
            $table->bigInteger('tot_payment');
            $table->bigInteger('principal');
            $table->bigInteger('interest');
            $table->bigInteger('end_balance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_loans');
    }
}
