<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->string("fullname");
            $table->string("email");
            $table->string("phone");
            $table->text("password");
            $table->text("address1");
            $table->text("address2")->nullable();
            $table->string("governorate")->nullable();
            $table->string("city")->nullable();
            $table->string("zip")->nullable();
            $table->text("reset_token")->nullable();
            $table->text("reset_time")->nullable();
            $table->text("verification_token")->nullable();
            $table->string("verification_status");
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
        Schema::dropIfExists('members');
    }
}
