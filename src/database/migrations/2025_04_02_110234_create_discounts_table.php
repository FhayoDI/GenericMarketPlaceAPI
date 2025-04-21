<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['percentage', 'fixed']);
            $table->decimal('discount_value', 10, 2);
            $table->string('description')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->timestamps();
        });

        Schema::create('discount_product', function (Blueprint $table) {
            $table->foreignId('discount_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->primary(['discount_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('discount_product');
        Schema::dropIfExists('discounts');
    }
};