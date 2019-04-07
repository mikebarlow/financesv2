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
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type');

            $table->unsignedBigInteger('from_row_id')
                ->nullable();
            $table->string('from_label')
                ->nullable();

            $table->unsignedBigInteger('to_row_id')
                ->nullable();
            $table->string('to_label')
                ->nullable();

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
        Schema::dropIfExists('transactions');
    }
}
