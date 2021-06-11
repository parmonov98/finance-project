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
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('pmt_no');
            $table->date('pay_date');
            $table->unsignedDecimal('beg_balance', $precision = 20, $scale = 2);
            $table->decimal('sch_payment', $precision = 20, $scale = 2);
            $table->decimal('ext_payment', $precision = 20, $scale = 2);
            $table->decimal('tot_payment', $precision = 20, $scale = 2);
            $table->decimal('principal', $precision = 20, $scale = 2);
            $table->decimal('interest', $precision = 20, $scale = 2);
            $table->decimal('end_balance', $precision = 20, $scale = 2);
            $table->decimal('cum_interest', $precision = 20, $scale = 2);
        });

        Schema::create('home_loans_savings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('pmt_no');
            $table->date('pay_date');
            $table->unsignedDecimal('beg_balance', $precision = 20, $scale = 2);
            $table->decimal('sch_payment', $precision = 20, $scale = 2);
            $table->decimal('ext_payment', $precision = 20, $scale = 2);
            $table->decimal('tot_payment', $precision = 20, $scale = 2);
            $table->decimal('principal', $precision = 20, $scale = 2);
            $table->decimal('interest', $precision = 20, $scale = 2);
            $table->decimal('end_balance', $precision = 20, $scale = 2);
            $table->decimal('cum_interest', $precision = 20, $scale = 2);
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
        Schema::dropIfExists('home_loans_savings');
    }
}
