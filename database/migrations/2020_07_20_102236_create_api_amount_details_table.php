<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiAmountDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_api_by_amount_dtls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('amount');
            $table->string('add_date');

            $table->integer('operator_id')->unsigned()->nullable();
            $table->foreign('operator_id')->references('id')->on('tbl_operators');
           
            $table->integer('api_id')->unsigned()->nullable();
            $table->foreign('api_id')->references('api_id')->on('tbl_api_settings');

            $table->enum('activated_status', ['YES', 'NO'])->default('NO');
            $table->integer('is_deleted')->default(0);
            $table->dateTime('updated_on')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_api_by_amount_dtls');
    }
}
