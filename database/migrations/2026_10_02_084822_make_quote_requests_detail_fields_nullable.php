<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 見積もりフォームを9項目(依頼者区分・会社名・担当者名・メール・電話・都道府県・
     * 建物名・階数・作業内容)に絞るのに伴い、フォームで集めなくなった3項目を
     * 必須から任意に変更する。詳細(建物種別・作業規模・希望時期)はフォーム送信後、
     * 個別の打ち合わせで確認する運用に変更したため。
     */
    public function up(): void
    {
        Schema::table('quote_requests', function (Blueprint $table) {
            $table->foreignId('building_type_id')->nullable()->change();
            $table->enum('glass_area_type', ['small', 'medium', 'large'])->nullable()->change();
            $table->enum('preferred_timing', ['urgent', 'this_week', 'this_month', 'undecided'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('quote_requests', function (Blueprint $table) {
            $table->foreignId('building_type_id')->nullable(false)->change();
            $table->enum('glass_area_type', ['small', 'medium', 'large'])->nullable(false)->change();
            $table->enum('preferred_timing', ['urgent', 'this_week', 'this_month', 'undecided'])->nullable(false)->change();
        });
    }
};
