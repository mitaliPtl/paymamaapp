<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGateWaySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payment_gateway_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payment_gateway_name');
            $table->string('working_key');
            $table->string('username');
            $table->string('password');
            $table->string('charges');

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
        Schema::dropIfExists('tbl_payment_gateway_settings');
    }
}
