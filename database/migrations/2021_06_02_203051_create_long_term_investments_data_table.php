<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLongTermInvestmentsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('long_term_investments_data', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->decimal('min', $precision = 20, $scale = 2);
            $table->decimal('max', $precision = 20, $scale = 2);
            $table->decimal('monthly_invest', $precision = 20 , $scale = 2);
            $table->decimal('fees', $precision = 20, $scale =2 );
            $table->date('start_date');
            $table->decimal('inflation', $precision = 20, $scale = 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('long_term_investments_data');
    }
}
