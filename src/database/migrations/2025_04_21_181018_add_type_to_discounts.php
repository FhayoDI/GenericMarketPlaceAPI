<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->enum('type', ['percentage', 'fixed'])->after('id');
            $table->renameColumn('discount_percentage', 'discount_value');
        });
    }

    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            //
        });
    }
};
