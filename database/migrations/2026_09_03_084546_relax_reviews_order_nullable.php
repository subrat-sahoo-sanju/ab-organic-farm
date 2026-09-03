<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Old schema made a "review" mean (product, user, order) exactly once.
        // We relax it: an admin may curate/import reviews before an order exists,
        // and the unique constraint would block multiple curated entries.
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropUnique('review_unique_per_order');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->foreignId('order_id')->nullable()->change()->constrained()->nullOnDelete();
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->foreignId('order_id')->change();
            $table->unique(['product_id', 'user_id', 'order_id'], 'review_unique_per_order');
        });
    }
};