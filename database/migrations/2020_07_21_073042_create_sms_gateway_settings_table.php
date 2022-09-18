<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmsGatewaySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_sms_gateway_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('api_name');
            $table->string('api_url');
            $table->string('username');
            $table->string('password');

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
        Schema::dropIfExists('tbl_sms_gateway_settings');
    }
}
