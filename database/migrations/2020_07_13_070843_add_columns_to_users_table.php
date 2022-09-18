<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_users', function (Blueprint $table) {
            $table->string('telegram_no')->nullable()->after('mobile');
            $table->string('whatsapp_no')->nullable()->after('mobile');
            $table->string('gst_no')->nullable()->after('mobile');
            $table->string('aadhar_no')->nullable()->after('mobile');
            $table->string('pan_no')->nullable()->after('mobile');

            $table->string('alternate_mob_no')->nullable()->after('mobile');
            $table->integer('zip_code')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_users', function (Blueprint $table) {
            $table->dropColumn('alternate_mob_no');
            $table->dropColumn('pan_no');
            $table->dropColumn('aadhar_no');
            $table->dropColumn('gst_no');
            $table->dropColumn('whatsapp_no');
            $table->dropColumn('telegram_no');
            $table->dropColumn('zip_code');
        });
    }
}
