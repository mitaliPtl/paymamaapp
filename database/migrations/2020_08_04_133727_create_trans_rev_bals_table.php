<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransRevBalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_transfer_revert_balances', function (Blueprint $table) {
            $table->id();
            $table->string('bank');
            $table->string('reference_id');
            $table->integer('user_id')->unsigned(); //Change to Transfer To
            $table->foreign('user_id')->references('userId')->on('tbl_users');
            $table->integer('transfered_by')->unsigned();
            $table->foreign('transfered_by')->references('userId')->on('tbl_users');
            $table->string('role');
            $table->string('mobile_no');
            $table->string('amount');
            $table->dateTime('trans_date')->useCurrent();
            $table->string('balance')->nullable();
            $table->string('transfer_type');
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
        Schema::dropIfExists('tbl_transfer_revert_balances');
    }
}
