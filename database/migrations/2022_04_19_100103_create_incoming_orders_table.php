<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incoming_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cashbook_id');
            $table->integer('company_id');
            $table->integer('workplace_id')->nullable();
            $table->integer('user_id');
            $table->string('doc_no');
            $table->integer('doc_type_id');
            $table->integer('out_doc_id')->nullable();
            $table->json('products_data')->nullable();
            $table->char('contractor_type')->nullable();
            $table->integer('from_contractor')->nullable();
            $table->integer('payment_type_id')->nullable();
            $table->json('payment_detail')->nullable();
            $table->integer('sum');
            $table->char('currency');
            $table->integer('count');
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
        Schema::dropIfExists('incoming_orders');
    }
}
