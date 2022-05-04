<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWriteoffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('writeoffs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('storage_id');
            $table->integer('user_id');
            $table->integer('product_id');
            $table->char('title');
            $table->integer('category_id');
            $table->integer('purchase_price');
            $table->integer('selling_price');
            $table->char('currency');
            $table->integer('count_writtenoff');
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('writeoffs');
    }
}
