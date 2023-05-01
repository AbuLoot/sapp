<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->integer('sort_id')->nullable();
            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->integer('in_company_id')->unsigned();
            $table->integer('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies');
            $table->integer('project_id')->nullable();
            $table->string('slug');
            $table->string('title');
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->json('barcodes')->nullable();
            $table->string('id_code')->nullable();
            $table->decimal('purchase_price', 44, 2)->default(0);
            $table->decimal('wholesale_price', 44, 2)->default(0);
            $table->decimal('price', 44, 2)->default(0);
            $table->json('count_in_stores')->nullable();
            $table->integer('count')->default(1);
            $table->integer('unit')->default(0);
            $table->integer('type')->default(1);
            $table->text('description')->nullable();
            $table->text('characteristic')->nullable();
            $table->json('parameters')->nullable(); // Weight, Length, Width, Height, Unit
            $table->char('path', 50)->nullable();
            $table->text('image')->nullable();
            $table->text('images')->nullable();
            $table->char('lang', 4);
            $table->integer('views')->default(0);
            $table->integer('status')->default(1);
            $table->timestamps();
        });

        Schema::create('options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sort_id')->nullable();
            $table->string('slug');
            $table->string('title');
            $table->string('data');
            $table->string('lang');
        });

        Schema::create('product_option', function (Blueprint $table) {
            $table->integer('product_id')->unsigned();
            $table->integer('option_id')->unsigned();

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('options')->onDelete('cascade');

            $table->primary(['product_id', 'option_id']);
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
