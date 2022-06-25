<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutgoingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outgoing_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cashbook_id');
            $table->integer('company_id');
            $table->integer('user_id');
            $table->char('cashier_name');
            $table->string('doc_no');
            $table->integer('doc_type_id');
            $table->json('to_contractors')->nullable();
            $table->integer('sum');
            $table->char('currency');
            $table->integer('count');
            $table->string('comment')->nullable();
            $table->timestamps();
        });

        /*Schema::create('contractors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_card');
            $table->char('fullname');
            $table->date('issued_by');
            $table->date('when_issued');
            $table->integer('comment');
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outgoing_orders');
    }
}
