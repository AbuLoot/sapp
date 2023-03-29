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
            $table->integer('company_id');
            $table->integer('cashbook_id');
            $table->integer('workplace_id')->nullable();
            $table->integer('user_id');
            $table->string('doc_no');
            $table->integer('doc_type_id');
            $table->char('contractor_type')->nullable();
            $table->integer('contractor_id')->nullable();
            $table->char('operation_code')->nullable();
            $table->integer('sum')->default(0);
            $table->char('currency');
            $table->integer('count')->default(0);
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
