<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_users', function (Blueprint $table) {
            $table->bigIncrements('userId');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('mpin')->unique();
            $table->string('user_code')->unique();
            $table->string('mobile')->unique()->nullable();
            $table->string('password');
            $table->text('address')->nullable();
            $table->integer('logged_otp')->nullable();
            $table->integer('is_verified')->default(0);
            $table->boolean('isDeleted')->default(0);
            // $table->timestamp('email_verified_at')->nullable();
            // $table->rememberToken();
            $table->timestamp('createdDtm')->useCurrent();
            $table->timestamp('updatedDtm')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_users');
    }
}
