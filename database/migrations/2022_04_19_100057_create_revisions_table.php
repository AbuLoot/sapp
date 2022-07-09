<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->integer('company_id');
            $table->integer('user_id');
            $table->integer('doc_no');
            $table->json('products_data');
            $table->integer('surplus_count');
            $table->integer('shortage_count');
            $table->integer('surplus_sum');
            $table->integer('shortage_sum');
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
        Schema::dropIfExists('revisions');
    }
}
