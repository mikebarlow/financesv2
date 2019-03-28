<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateOwners extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('budget_id');
            $table->unsignedBigInteger('user_id');
        });

        Schema::table('budget_user', function (Blueprint $table) {
            $table->foreign('budget_id')
                ->references('id')
                ->on('budgets');

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_user');
    }
}
