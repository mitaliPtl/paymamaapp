<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOperatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_operators', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('operator_name');
            $table->string('operator_code');
            $table->integer('service_type')->unsigned()->nullable();
            $table->foreign('service_type')->references('service_id')->on('tbl_services_type');
            $table->enum('activated_status', ['YES', 'NO'])->default('NO');
            $table->integer('id_deleted')->default(0);
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
        Schema::dropIfExists('tbl_operators');
    }
}
