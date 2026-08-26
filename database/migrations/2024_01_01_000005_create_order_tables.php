<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 32)->unique();     // ORD-2026-000123
            $table->foreignId('user_id')->constrained()->restrictOnDelete();

            $table->enum('status', [
                'pending', 'confirmed', 'preparing', 'packed', 'assigned',
                'out_for_delivery', 'delivered', 'cancelled', 'returned', 'failed_delivery',
            ])->default('pending')->index();

            $table->string('cancellation_reason')->nullable();
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete();

            // Money
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('product_discount', 10, 2)->default(0);
            $table->decimal('coupon_discount', 10, 2)->default(0);
            $table->decimal('delivery_charge', 8, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);

            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();

            // Address snapshot (survives address deletion)
            $table->string('ship_name');
            $table->string('ship_phone', 20);
            $table->string('ship_house_no');
            $table->string('ship_street')->nullable();
            $table->string('ship_area')->nullable();
            $table->string('ship_landmark')->nullable();
            $table->string('ship_city', 120);
            $table->string('ship_state', 120);
            $table->string('ship_pincode', 10);
            $table->foreignId('address_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('payment_method', ['cod'])->default('cod');

            $table->timestamp('placed_at')->useCurrent();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();

            // Immutable snapshots
            $table->string('product_name');
            $table->string('variant_name', 64)->nullable();
            $table->string('sku', 64)->nullable();
            $table->string('image_path')->nullable();

            $table->unsignedInteger('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('line_discount', 10, 2)->default(0);
            $table->decimal('line_total', 10, 2);

            $table->index(['order_id']);
            $table->index(['product_id']);
            $table->timestamps();
        });

        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->decimal('discount_amount', 10, 2);
            $table->timestamp('used_at')->useCurrent();

            $table->index(['coupon_id', 'user_id']);
        });

        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('from_status', 32)->nullable();
            $table->string('to_status', 32);
            $table->string('note')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['order_id', 'created_at']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->enum('method', ['cod'])->default('cod');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'collected', 'refunded', 'failed'])->default('pending')->index();
            $table->timestamps();
        });

        Schema::create('cod_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->unique()->constrained()->restrictOnDelete();
            $table->unsignedBigInteger('collected_by');       // delivery_persons.id or users.id
            $table->string('collector_type', 32)->default('delivery_person');
            $table->decimal('amount', 10, 2);
            $table->timestamp('collected_at');
            $table->text('notes')->nullable();
            $table->string('receipt_ref', 64)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('cod_collections');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
