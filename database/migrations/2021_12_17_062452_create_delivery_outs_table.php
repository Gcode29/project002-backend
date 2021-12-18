<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_outs', function (Blueprint $table) {
            $table->id();
            $table->string('client_id')->nullable();
            $table->string('address')->nullable();
            $table->unsignedInteger('employee_id');
            $table->string('product_id')->nullable();
            $table->string('quantity')->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('total_price', 8, 2);
            $table->decimal('grand_total', 8, 2);
            // product_status
            $table->unsignedInteger('product_status')->default('1');

            // delivery_status
            $table->unsignedInteger('delivery_status')->default(1);

            // payment_status
            $table->unsignedInteger('pay_status')->default('1');
            $table->unsignedInteger('payment_id')->nullable();

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
        Schema::dropIfExists('delivery_outs');
    }
}
