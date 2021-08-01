<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->text("sitename");
            $table->smallInteger("sitestatus");
            $table->smallInteger("registerstatus");
            $table->string("currency");
            $table->text("regions");
            $table->text("sitelink");
            $table->text("footerdescrip");
            $table->text("emails");
            $table->text("phones");
            $table->smallInteger("phonestatus");
            $table->smallInteger("emailstatus");
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
        Schema::dropIfExists('settings');
    }
}
