<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserNoticeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_notice', function (Blueprint $table){
            $table->dropColumn('topic_id');
        });
        Schema::table('user_notice', function (Blueprint $table){
            $table->integer('notice_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_notice', function (Blueprint $table){
            $table->dropColumn('notice_id');
        });
    }
}
