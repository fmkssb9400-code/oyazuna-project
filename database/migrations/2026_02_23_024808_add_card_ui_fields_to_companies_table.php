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
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('gondola_supported')->default(false)->after('rope_support'); // ゴンドラ対応
            $table->string('official_url')->nullable()->after('gondola_supported'); // 公式サイトURL（修正）
            $table->json('areas')->nullable()->after('official_url'); // 対応エリア都道府県配列
            $table->string('achievements_summary')->nullable()->after('areas'); // 実績要約
            $table->json('safety_items')->nullable()->after('achievements_summary'); // 安全情報項目
            $table->boolean('is_featured')->default(false)->after('safety_items'); // ホームおすすめ用
            $table->integer('sort_order')->default(0)->after('is_featured'); // 表示順
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'gondola_supported',
                'official_url',
                'areas',
                'achievements_summary',
                'safety_items',
                'is_featured',
                'sort_order'
            ]);
        });
    }
};
