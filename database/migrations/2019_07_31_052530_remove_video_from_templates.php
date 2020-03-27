<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveVideoFromTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn('video');
            $table->dropColumn('video_duration');
            $table->dropColumn('url');
            $table->dropColumn('url_duration');
            $table->dropColumn('photo');
            $table->dropColumn('photo_duration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->string('video');
            $table->string('video_duration');
            $table->string('url');
            $table->string('url_duration');
            $table->string('photo');
            $table->string('photo_duration');
        });
    }
}
