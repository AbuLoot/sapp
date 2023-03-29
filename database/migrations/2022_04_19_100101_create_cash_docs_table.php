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
            $table->integer('company_id');
            $table->integer('cashbook_id');
            $table->integer('user_id');
            $table->char('order_type');
            $table->integer('order_id');
            $table->integer('doc_id')->nullable();
            $table->char('contractor_type')->nullable();
            $table->integer('contractor_id')->nullable();
            $table->integer('incoming_amount')->default(0);
            $table->integer('outgoing_amount')->default(0);
            $table->integer('sum')->default(0);
            $table->char('currency');
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
        Schema::dropIfExists('cash_docs');
    }
}
