<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutgoingDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outgoing_docs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('storage_id');
            $table->integer('company_id');
            $table->integer('user_id');
            $table->char('username');
            $table->integer('doc_id');
            $table->integer('doc_type_id');
            $table->json('products_ids');
            $table->char('to_contractor');
            $table->integer('sum');
            $table->char('currency');
            $table->integer('count');
            $table->string('comment')->nullable();
            $table->timestamps();
        });

        /*Schema::create('doc_products_json', function (Blueprint $table) {
            $table->increments('id');
            $table->char('title');
            $table->char('slug');
            $table->char('barcode');
            $table->integer('purchase_price');
            $table->integer('selling_price');
            $table->integer('sum');
            $table->char('currency');
            $table->integer('count');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outgoing_docs');
    }
}
