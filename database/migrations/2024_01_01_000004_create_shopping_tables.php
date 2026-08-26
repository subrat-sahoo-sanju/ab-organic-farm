<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained()->cascadeOnDelete();
            $table->string('session_id', 64)->nullable()->index();
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->constrained()->restrictOnDelete();
            $table->foreignId('product_id')->constrained()->restrictOnDelete(); // denormalised for speed
            $table->unsignedInteger('quantity');
            $table->decimal('price_at_add', 10, 2);
            $table->timestamps();

            $table->unique(['cart_id', 'product_variant_id']);
        });

        Schema::create('wishlist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'product_id']);
        });

        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('label', ['home', 'office', 'other'])->default('home');
            $table->string('name');
            $table->string('phone', 20);
            $table->string('house_no');
            $table->string('street')->nullable();
            $table->string('area')->nullable();
            $table->string('landmark')->nullable();
            $table->string('city', 120);
            $table->string('state', 120);
            $table->string('pincode', 10);
            $table->boolean('is_default')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'is_default']);
        });

        Schema::create('delivery_areas', function (Blueprint $table) {
            $table->id();
            $table->string('pincode', 10)->index();
            $table->string('city', 120);
            $table->string('state', 120)->nullable();
            $table->string('area', 160)->nullable();
            $table->boolean('is_serviceable')->default(true);
            $table->decimal('delivery_charge', 8, 2)->default(0);
            $table->unsignedTinyInteger('eta_days')->default(1);
            $table->boolean('cod_available')->default(true);
            $table->timestamps();

            $table->index(['pincode', 'is_serviceable']);
        });

        Schema::create('search_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('term', 190);
            $table->unsignedInteger('results_count')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index('term');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('search_logs');
        Schema::dropIfExists('delivery_areas');
        Schema::dropIfExists('addresses');
        Schema::dropIfExists('wishlist_items');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};
