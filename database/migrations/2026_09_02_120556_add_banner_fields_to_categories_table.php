<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('banner_heading', 190)->nullable()->after('meta_description');
            $table->string('banner_subheading', 300)->nullable()->after('banner_heading');
            $table->string('banner_image', 500)->nullable()->after('banner_subheading');
            $table->string('banner_cta_text', 80)->nullable()->after('banner_image');
            $table->string('banner_cta_url', 500)->nullable()->after('banner_cta_text');
            $table->string('banner_bg_color', 20)->nullable()->after('banner_cta_url');
            $table->string('brand_logo', 500)->nullable()->after('banner_bg_color');
            $table->string('brand_name', 120)->nullable()->after('brand_logo');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'banner_heading', 'banner_subheading', 'banner_image',
                'banner_cta_text', 'banner_cta_url', 'banner_bg_color',
                'brand_logo', 'brand_name',
            ]);
        });
    }
};
