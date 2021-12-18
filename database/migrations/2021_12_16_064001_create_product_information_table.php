<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_information', function (Blueprint $table) {
            $table->id();
            // uniquename = productcode + category_name + productname + color + size
            $table->string('uniquename')->unique();
            $table->string('product_name');
            $table->string('product_code')->nullable();
            $table->string('brand')->nullable();
            $table->string('color')->nullable();
            $table->decimal('price', 8, 2);
            $table->string('size')->nullable();
            $table->string('uom');
            // connect category_id from category_table
            $table->string('category_id');
            // connect subcategory_id from subcategory_table
            $table->string('subcategory_id')->nullable();
            $table->string('product_image')->nullable();
            $table->string('codename')->nullable();
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
        Schema::dropIfExists('product_information');
    }
}
