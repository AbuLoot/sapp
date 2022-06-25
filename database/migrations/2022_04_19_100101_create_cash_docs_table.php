<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_docs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cashbook_id');
            $table->integer('company_id');
            $table->integer('user_id');
            $table->integer('doc_id');
            $table->integer('doc_type_id');
            $table->char('from_contractor');
            $table->char('to_contrator');
            $table->integer('incoming_price');
            $table->integer('outgoing_price');
            $table->integer('sum');
            $table->char('currency');
            $table->string('comment');
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
        Schema::dropIfExists('cash_docs');
    }
}
