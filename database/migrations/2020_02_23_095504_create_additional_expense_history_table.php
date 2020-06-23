<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionalExpenseHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_expense_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->unsignedBigInteger('additional_expense_id');
            $table->decimal('amount', 8, 2);
            $table->string('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('additional_expense_id')->references('id')->on('additional_expense');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('additional_expense_history');
    }
}
