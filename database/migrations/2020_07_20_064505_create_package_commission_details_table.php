<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageCommissionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_pkg_commission_dtls', function (Blueprint $table) {
            $table->bigIncrements('pkg_commission_id');
            $table->string('admin_commission')->nullable();
            $table->string('api_commission')->nullable();
            $table->string('md_commission')->nullable();
            $table->string('distributor_commission')->nullable();
            $table->string('retailer_commission')->nullable();
            $table->enum('commission_type', ['Rupees', 'Percent'])->nullable();

            $table->integer('service_id')->unsigned()->nullable();
            $table->foreign('service_id')->references('service_id')->on('tbl_services_type');

            $table->integer('pkg_id')->unsigned()->nullable();
            $table->foreign('pkg_id')->references('package_id')->on('tbl_package_settings');

            $table->integer('operator_id')->unsigned()->nullable();
            $table->foreign('operator_id')->references('id')->on('tbl_operators');

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
        Schema::dropIfExists('tbl_pkg_commission_dtls');
    }
}
