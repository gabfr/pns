<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserIntegrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_integrations', function (Blueprint $table) {
            $table->integer('user_id')->index()->unsigned();
            $table->string('remote_id',100)->index();
            $table->string('provider',20);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unique([
                'remote_id','user_id','provider'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_integrations');
    }
}
