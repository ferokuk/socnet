<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string("first_name")->nullable();
            $table->string("last_name")->nullable();
            $table->enum("gender", ["f", "m"])->nullable();
            $table->boolean("show_personal_info")->default(false);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('image')->default("image.png");
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}
