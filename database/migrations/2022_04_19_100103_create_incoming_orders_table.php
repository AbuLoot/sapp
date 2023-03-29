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
            $table->integer('company_id');
            $table->integer('cashbook_id');
            $table->integer('workplace_id')->nullable();
            $table->integer('user_id');
            $table->string('doc_no');
            $table->integer('doc_type_id');
            $table->json('products_data')->nullable();
            $table->char('contractor_type')->nullable();
            $table->integer('contractor_id')->nullable();
            $table->char('operation_code')->nullable();
            $table->integer('payment_type_id')->nullable();
            $table->json('payment_detail')->nullable();
            $table->integer('sum')->default(0);
            $table->char('currency');
            $table->integer('count')->default(0);
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
