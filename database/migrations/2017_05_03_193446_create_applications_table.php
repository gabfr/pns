<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->increments('id');

            $table->string('slug');
            $table->string('name');

            $table->string('apns_mode')->nullable()->comment('development or production');
            $table->string('apns_certificate_sandbox')->nullable();
            $table->string('apns_certificate_production')->nullable();
            $table->string('apns_root_certificate')->nullable();
            $table->string('apns_certificate_password')->nullable();

            $table->string('gcm_mode')->nullable()->comment('development or production');
            $table->string('gcm_api_key')->nullable();

            $table->timestamps();

            $table->unique(['slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('applications');
    }
}
