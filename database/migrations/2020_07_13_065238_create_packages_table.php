<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_packages', function (Blueprint $table) {
            $table->bigIncrements('package_id');
            $table->integer('commission_id')->unsigned();
            $table->foreign('commission_id')->references('commission_id')->on('tbl_commissions');
            $table->string('package_name');
            $table->text('package_dtls');
            $table->boolean('is_deleted');
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
        Schema::dropIfExists('tbl_packages');
    }
}
