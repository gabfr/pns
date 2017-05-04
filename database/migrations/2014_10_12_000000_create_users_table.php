<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name',200);
            $table->string('email',180)->unique()->index();
            $table->string('password',100);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_super')->default(false);

            $table->string('phone_area_code')->nullable();
            $table->string('phone_number')->nullable();

            $table->string('document_type')->nullable();
            $table->string('document_number')->nullable();

            $table->integer('city_id')->unsigned()->index()->nullable();
            $table->string('zipcode')->nullable();
            $table->string('address')->nullable();
            $table->string('address_neighborhood')->nullable();
            $table->string('address_number')->nullable();
            $table->string('address_complement')->nullable();


            $table->rememberToken();
            $table->timestamps();

            $table->foreign('city_id')->references('id')->on('cities')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
