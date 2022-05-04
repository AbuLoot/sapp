<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashShiftJournalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_shift_journal', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cashbook_id');
            $table->integer('company_id');
            $table->integer('from_user_id');
            $table->char('cashier_name');
            $table->integer('to_user_id');
            $table->integer('opening_cash_balance');
            $table->integer('closing_cash_balance');
            $table->json('banknotes_and_coins');
            $table->integer('sum');
            $table->char('currency');
            $table->time('shift_time');
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
        Schema::dropIfExists('cash_shift_journal');
    }
}
