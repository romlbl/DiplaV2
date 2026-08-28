<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_card_and_avatar_images_to_companies_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('card_image_url')->nullable()->after('cover_image_url');
            $table->string('avatar_image_url')->nullable()->after('card_image_url');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['card_image_url', 'avatar_image_url']);
        });
    }
};