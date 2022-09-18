<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Role;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_roles', function (Blueprint $table) {
            $table->bigIncrements('roleId');
            $table->string('name');
            $table->boolean('id_deleted')->default(0);
            $table->timestamps();
        });

        $roleData = array(
            array(
                "name" => "System Administrator",
            ),
            array(
                "name" => "Distributor",
            ),
            array(
                "name" => "Fos",
            ),
            array(
                "name" => "Retailer",
            ),
            array(
                "name" => "Customer",
            )
        );

        Role::insert($roleData);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_roles');
    }
}
