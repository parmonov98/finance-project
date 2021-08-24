<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDateToProgram5YRNetworths extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('program5_y_r_networths', function (Blueprint $table) {
            $table->date('date')->nullable();
            $table->decimal('total_debt')->nullable();
            $table->decimal('total_assets')->nullable();
            $table->decimal('difference')->nullable();
            $table->decimal('difference_minus_super')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('program5_y_r_networths', function (Blueprint $table) {
            $table->dropIfExists('date');
        });
    }
}
