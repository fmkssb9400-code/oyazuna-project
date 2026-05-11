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
        Schema::create('quote_submissions', function (Blueprint $table) {
            $table->id();
            
            // 必須項目
            $table->string('work_type'); // 作業内容
            $table->string('prefecture'); // 都道府県
            $table->text('comment'); // 一言コメント
            $table->json('images'); // 見積書画像パス（複数対応）
            
            // 任意項目
            $table->integer('building_floors')->nullable(); // 建物階数
            $table->string('order_status')->nullable(); // 依頼したか
            $table->date('quote_date')->nullable(); // 見積もり時期
            
            // メタデータ（OCR・AI解析用）
            $table->json('ocr_data')->nullable(); // OCR解析結果
            $table->json('ai_analysis')->nullable(); // AI解析結果
            $table->string('status')->default('pending'); // 処理ステータス
            $table->text('admin_notes')->nullable(); // 管理者メモ
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_submissions');
    }
};
