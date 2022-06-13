<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoreDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_docs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->integer('company_id');
            $table->integer('user_id');
            $table->integer('doc_id');
            $table->integer('doc_type_id');
            $table->char('title')->nullable();
            $table->json('products_data');
            $table->char('from_contractor')->nullable();
            $table->char('to_contractor')->nullable();
            $table->integer('incoming_price');
            $table->integer('outgoing_price')->nullable();
            $table->integer('amount');
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
        Schema::dropIfExists('store_docs');
    }
}
