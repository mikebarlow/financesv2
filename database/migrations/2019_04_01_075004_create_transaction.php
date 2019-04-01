<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('from_row_id')
                ->nullable();
            $table->string('from_label');

            $table->unsignedBigInteger('to_row_id')
                ->nullable();
            $table->string('to_label');
            $table->string('amount');

            $table->timestamps();

            $table->foreign('from_row_id')
                ->references('id')
                ->on('sheet_rows');

            $table->foreign('to_row_id')
                ->references('id')
                ->on('sheet_rows');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction');
    }
}
