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
            $table->integer('company_id');
            $table->integer('cashbook_id');
            $table->integer('workplace_id');
            $table->integer('from_user_id')->nullable();
            $table->char('cashier_name')->nullable();
            $table->integer('to_user_id')->nullable();
            $table->integer('opening_cash_balance')->default(0);
            $table->integer('closing_cash_balance')->default(0);
            $table->json('banknotes_and_coins')->nullable();
            $table->integer('incoming_amount')->default(0);
            $table->integer('outgoing_amount')->default(0);
            $table->integer('sum')->default(0);
            $table->char('currency')->nullable();
            $table->enum('mode', ['open', 'close']);
            $table->datetime('opening_time');
            $table->datetime('closing_time')->nullable();
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
