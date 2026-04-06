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
            $table->string('website_url')->nullable()->after('description'); // 公式サイトURL
            $table->text('service_areas')->nullable()->after('website_url'); // 対応エリア
            $table->boolean('rope_support')->default(false)->after('service_areas'); // ロープ対応
            $table->text('security_points')->nullable()->after('rope_support'); // 安全情報（JSON形式）
            $table->string('performance_summary')->nullable()->after('security_points'); // 実績要約
            $table->text('strength_tags')->nullable()->after('performance_summary'); // 強みタグ（JSON形式）
            $table->integer('recommend_score')->default(0)->after('strength_tags'); // おすすめスコア
            $table->integer('safety_score')->default(0)->after('recommend_score'); // 安全スコア
            $table->integer('performance_score')->default(0)->after('safety_score'); // 実績スコア
            $table->decimal('review_score', 3, 1)->default(0)->after('performance_score'); // 口コミスコア
            $table->integer('review_count')->default(0)->after('review_score'); // 口コミ数
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'website_url',
                'service_areas', 
                'rope_support',
                'security_points',
                'performance_summary',
                'strength_tags',
                'recommend_score',
                'safety_score',
                'performance_score', 
                'review_score',
                'review_count'
            ]);
        });
    }
};
