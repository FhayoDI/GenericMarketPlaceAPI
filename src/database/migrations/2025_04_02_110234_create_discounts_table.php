<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('productId')->references('id')->on('products')->onDelete('cascade');
            $table->foreignId('categoryId')->references('id')->on('categories')->onDelete('cascade');
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('discount_percentage');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
