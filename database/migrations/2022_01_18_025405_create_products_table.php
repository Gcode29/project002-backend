<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\UOM;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class)->unsigned()->index();
            $table->foreignIdFor(Brand::class)->unsigned()->index();
            $table->foreignIdFor(UOM::class)->unsigned()->index();
            $table->string('code')->nullable();
            $table->string('unique_name');
            $table->string('name')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->decimal('selling_price', 8, 2)->default(0);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('products');
    }
}
