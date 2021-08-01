<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->UnsignedInteger("section_id");
            $table->text("productname");
            $table->text("description");
            $table->text("spec");
            $table->text("manufacturer");
            $table->text("productimages");
            $table->float("price");
            $table->string("discount");
            $table->string("qty");
            $table->integer("rating");
            $table->text("reviews")->nullable();
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
        Schema::dropIfExists('products');
    }
}
