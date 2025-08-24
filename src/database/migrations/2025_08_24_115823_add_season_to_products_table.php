<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_season_to_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // spring / summer / autumn / winter を想定。とりあえず nullable で追加
            $table->string('season', 20)->nullable()->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('season');
        });
    }
};
