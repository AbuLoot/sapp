<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomingDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incoming_docs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('store_id');
            $table->integer('workplace_id')->nullable();
            $table->integer('user_id');
            $table->string('doc_no');
            $table->integer('doc_type_id');
            $table->json('products_data');
            $table->char('contractor_type')->nullable();
            $table->integer('contractor_id')->nullable();
            $table->char('operation_code')->nullable();
            $table->integer('sum')->default(0);
            $table->char('currency');
            $table->integer('count')->default(0);
            $table->char('unit')->nullable();
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
        Schema::dropIfExists('incoming_docs');
    }
}
