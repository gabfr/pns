<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('application_id')->unsigned()->index()->nullable();
            $table->integer('created_by')->unsigned()->index()->nullable();

            $table->string('title');
            $table->text('alert_message');
            $table->string('icon');
            $table->string('url');

            $table->timestamps();

            $table->foreign('application_id')->references('id')->on('applications')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notifications');
    }
}
