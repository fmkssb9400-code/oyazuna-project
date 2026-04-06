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
        Schema::table('reviews', function (Blueprint $table) {
            // Add 4 rating columns
            $table->tinyInteger('service_quality')->nullable()->comment('サービス品質 1-5');
            $table->tinyInteger('staff_response')->nullable()->comment('スタッフ対応 1-5'); 
            $table->tinyInteger('value_for_money')->nullable()->comment('料金・コスパ 1-5');
            $table->tinyInteger('would_use_again')->nullable()->comment('また利用したいか 1-5');
            $table->decimal('total_score', 3, 2)->nullable()->comment('4項目の平均値');
            
            // Add index for performance
            $table->index(['company_id', 'status', 'total_score'], 'reviews_company_status_score_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex('reviews_company_status_score_index');
            $table->dropColumn(['service_quality', 'staff_response', 'value_for_money', 'would_use_again', 'total_score']);
        });
    }
};
