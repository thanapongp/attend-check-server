<?php

use Illuminate\Support\Facades\Schema;
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
            $table->string('username')->unique();
            $table->string('password')->nullable();
            $table->string('email')->nullable();
            $table->string('title');
            $table->string('name');
            $table->string('lastname');
            $table->integer('faculty_id')->unsigned()->nullable();
            $table->integer('type_id')->unsigned()->nullable();
            $table->boolean('active')->default(false);
            $table->integer('pickcount')->unsigned()->default(0);
            $table->rememberToken();
            $table->timestamps();
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
