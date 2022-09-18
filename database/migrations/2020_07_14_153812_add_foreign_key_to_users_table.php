<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\User;

class AddForeignKeyToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_users', function (Blueprint $table) {
            $table->integer('updatedBy')->nullable()->after('isDeleted');
            $table->integer('createdBy')->nullable()->after('isDeleted');

            $table->integer('district_id')->nullable()->after('zip_code')->unsigned();
            $table->foreign('district_id')->references('city_id')->on('tbl_district_mst');

            $table->integer('state_id')->nullable()->after('zip_code')->unsigned();
            $table->foreign('state_id')->references('state_id')->on('tbl_states');

            $table->integer('package_id')->nullable()->after('zip_code')->unsigned();
            $table->foreign('package_id')->references('package_id')->on('tbl_packages');

            $table->integer('parent_user_id')->unsigned()->after('zip_code')->nullable();
            $table->foreign('parent_user_id')->references('userId')->on('tbl_users');

            $table->integer('parent_role_id')->unsigned()->after('zip_code')->nullable();
            $table->foreign('parent_role_id')->references('roleId')->on('tbl_roles');

            $table->integer('roleId')->unsigned()->after('zip_code');
            $table->foreign('roleId')->references('roleId')->on('tbl_roles');
        });

        $userData = array(
            "first_name" => "Admin",
            "username" => "admin",
            "user_code" => "AD 0000",
            "email" => "admin@example.com",
            "password" => Hash::make('123456'),
            "roleId" => Config::get('constants.ADMIN'),
            "createdBy" => Config::get('constants.ADMIN'),
            "updatedBy" => Config::get('constants.ADMIN'),
        );

        User::insert($userData);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_users', function (Blueprint $table) {
            $table->dropForeign('roleId');
            $table->dropForeign('parent_role_id');
            $table->dropForeign('district_id');
            $table->dropForeign('state_id');
            $table->dropForeign('package_id');
        });
    }
}
