<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_api_settings', function (Blueprint $table) {
            $table->bigIncrements('api_id');
            $table->string('api_name');
            $table->string('api_dtls')->nullable();
            $table->string('api_url')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('balance')->nullable();
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
        Schema::dropIfExists('tbl_api_settings');
    }
}
