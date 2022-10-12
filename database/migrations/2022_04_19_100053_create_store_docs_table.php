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
            $table->char('doc_type');
            $table->integer('doc_id');
            $table->integer('order_id')->nullable();
            $table->json('products_data');
            $table->char('contractor_type')->nullable();
            $table->integer('contractor_id')->nullable();
            $table->integer('incoming_amount')->default(0);
            $table->integer('outgoing_amount')->default(0);
            $table->integer('count')->default(0);
            $table->integer('sum')->default(0);
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
