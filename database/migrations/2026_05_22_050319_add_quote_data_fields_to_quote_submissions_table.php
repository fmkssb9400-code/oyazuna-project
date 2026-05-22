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
        Schema::table('quote_submissions', function (Blueprint $table) {
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->json('quote_items')->nullable(); // 見積もり項目の詳細
            $table->integer('floor_count')->nullable();
            $table->decimal('work_area', 10, 2)->nullable(); // 施工面積
            $table->string('area_unit')->nullable(); // 面積単位
            $table->text('work_description')->nullable(); // 作業内容詳細
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'total_amount',
                'quote_items',
                'floor_count',
                'work_area',
                'area_unit',
                'work_description'
            ]);
        });
    }
};
