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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->constrained('user_adresses')->onDelete('restrict');
            $table->dateTime('order_date');
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('status', ['PENDING','PROCESSING','SHIPPED','COMPLETED','CANCELED'])->default('PENDING');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('products_discount', 10, 2)->default(0); // Soma dos descontos de produtos
            $table->decimal('coupon_discount', 10, 2)->default(0); // Desconto do cupom
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
