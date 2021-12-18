<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('cash');
            $table->string('product_id');
            $table->decimal('price', 8, 2);
            $table->decimal('total_price', 8, 2);
            $table->decimal('grand_total', 8, 2);
            $table->string('quantity');
            $table->string('uom');
            $table->string('discount')->nullable();
            $table->string('year');
            $table->string('month');
            $table->string('day');
            $table->string('remarks')->nullable();
            // transaction_status
            $table->unsignedInteger('trans_status')->default(1);

            // check_payment
            $table->string('bank_name')->nullable();
            $table->string('check_no')->nullable();
            $table->string('check_date')->nullable();

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
        Schema::dropIfExists('transactions');
    }
}
