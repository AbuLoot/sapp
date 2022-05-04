<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomingChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incoming_checks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('incoming_order_id');
            $table->integer('company_id');
            $table->integer('user_id');
            $table->integer('doc_type_id');
            $table->json('products_ids');
            $table->char('from_contractor');
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
        Schema::dropIfExists('checks');
    }
}
