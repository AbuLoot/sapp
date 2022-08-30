<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevisionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*Schema::create('revision_products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('revision_id');
            $table->integer('product_id');
            $table->integer('category_id');
            $table->char('barcode');
            $table->integer('purchase_price');
            $table->integer('selling_price');
            $table->char('currency');
            $table->integer('estimated_count');
            $table->integer('actual_count');
            $table->integer('difference');
            $table->integer('surplus_count');
            $table->integer('shortage_count');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('revision_products');
    }
}
