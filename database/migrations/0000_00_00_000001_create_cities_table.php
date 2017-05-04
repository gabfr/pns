<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100)->nullable();
            $table->char('uf',2)->nullable()->index();
            $table->string('ibge_code',10)->default(0);
            $table->double('area')->default(0);
            $table->string('lat',35)->nullable();
            $table->string('lng',35)->nullable();

            $table->unique(['name','uf']);

            $table->foreign('uf')->references('uf')->on('states')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cities');
    }
}
