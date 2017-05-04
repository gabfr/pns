<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_deliveries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('device_id')->unsigned()->index()->nullable();
            $table->integer('notification_id')->unsigned()->index()->nullable();

            $table->string('status')->index();
            $table->text('status_message')->nullable();

            $table->timestamps();

            $table->foreign('device_id')->references('id')->on('devices')->onDelete('set null');
            $table->foreign('notification_id')->references('id')->on('notifications')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notification_deliveries');
    }
}
