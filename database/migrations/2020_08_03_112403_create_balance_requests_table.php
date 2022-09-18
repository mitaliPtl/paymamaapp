<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBalanceRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_balance_requests', function (Blueprint $table) {
            $table->id();
            $table->string('bank');
            $table->string('reference_id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('userId')->on('tbl_users');
            $table->string('role');
            $table->string('mobile_no');
            $table->string('amount');
            $table->text('message');
            $table->text('admin_reply')->nullable();
            $table->integer('receipt_file')->nullable();
            $table->foreign('receipt_file')->references('id')->on('tbl_files');
            $table->dateTime('trans_date')->useCurrent();
            $table->string('status');
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
        Schema::dropIfExists('tbl_balance_requests');
    }
}
