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
        Schema::create('quote_data', function (Blueprint $table) {
            $table->id();
            
            // 共通項目
            $table->string('work_type'); // 作業内容
            $table->string('building_type'); // 建物種別
            $table->integer('floors')->nullable(); // 建物の階数
            $table->string('prefecture'); // 所在地（都道府県）
            $table->integer('quote_amount'); // 見積もり金額
            $table->date('quote_date'); // 見積もり時期
            $table->string('company_name')->nullable(); // 依頼した会社名
            $table->string('order_status'); // 実際に依頼したか
            $table->text('memo')->nullable(); // 投稿者メモ
            
            // 窓ガラス清掃用項目
            $table->integer('window_count')->nullable(); // 窓の枚数
            $table->string('work_surface')->nullable(); // 作業面
            $table->string('cleaning_range')->nullable(); // 清掃範囲
            $table->boolean('regular_cleaning')->nullable(); // 定期清掃か
            $table->boolean('rope_work')->nullable(); // ロープ作業の有無
            $table->boolean('gondola_lift')->nullable(); // ゴンドラ/高所作業車の有無
            
            // 外壁塗装用項目
            $table->decimal('painting_area', 8, 2)->nullable(); // 塗装面積
            $table->string('painting_surface')->nullable(); // 塗装する面
            $table->string('paint_type')->nullable(); // 塗料の種類
            $table->boolean('foundation_repair')->nullable(); // 下地補修の有無
            $table->boolean('scaffolding')->nullable(); // 足場の有無
            $table->integer('construction_days')->nullable(); // 施工日数
            
            // 外壁清掃用項目
            $table->decimal('cleaning_area', 8, 2)->nullable(); // 清掃面積
            $table->string('dirt_type')->nullable(); // 汚れの種類
            $table->string('cleaning_method')->nullable(); // 清掃方法
            $table->integer('work_surfaces')->nullable(); // 作業面数
            $table->boolean('wall_rope_work')->nullable(); // ロープ作業の有無（外壁清掃用）
            
            // 外壁点検用項目
            $table->string('inspection_range')->nullable(); // 点検範囲
            $table->string('inspection_method')->nullable(); // 点検方法
            $table->boolean('report_included')->nullable(); // 報告書の有無
            $table->boolean('photo_submission')->nullable(); // 写真提出の有無
            
            // 外壁補修用項目
            $table->string('repair_content')->nullable(); // 補修内容
            $table->integer('repair_locations')->nullable(); // 補修箇所数
            $table->decimal('repair_area', 8, 2)->nullable(); // 補修面積
            $table->boolean('materials_included')->nullable(); // 材料費込みか
            $table->boolean('survey_included')->nullable(); // 調査込みか
            
            // 雨漏り調査用項目
            $table->string('leak_location')->nullable(); // 雨漏り箇所
            $table->string('survey_method')->nullable(); // 調査方法
            $table->boolean('leak_report_included')->nullable(); // 報告書の有無（雨漏り調査用）
            $table->boolean('repair_quote_included')->nullable(); // 補修見積もり込みか
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_data');
    }
};
