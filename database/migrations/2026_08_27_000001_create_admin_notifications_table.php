<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type', 40)->default('order'); // order, status, system, low_stock
            $table->string('title');
            $table->text('message')->nullable();
            $table->string('icon', 30)->default('shopping-bag'); // lucide icon name
            $table->string('color', 30)->default('forest'); // accent color key
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->json('meta')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['read_at', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
