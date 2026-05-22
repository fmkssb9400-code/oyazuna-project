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
            $table->boolean('free_estimate')->default(false)->comment('無料見積もり');
            $table->boolean('regular_work')->default(false)->comment('定期契約');
            $table->boolean('reviews_reputation')->default(false)->comment('口コミ評判');
            $table->boolean('case_studies')->default(false)->comment('実績事例');
            $table->boolean('liability_insurance')->default(false)->comment('賠償責任保険');
            $table->boolean('workers_insurance')->default(false)->comment('労災保険');
            $table->boolean('certified_staff')->default(false)->comment('有資格者在籍');
            $table->boolean('corporate_support')->default(false)->comment('法人サポート');
            $table->boolean('weekend_support')->default(false)->comment('土日対応');
            $table->boolean('night_support')->default(false)->comment('夜間対応');
            $table->boolean('online_consultation')->default(false)->comment('オンライン相談');
            $table->boolean('email_support')->default(false)->comment('メールサポート');
            $table->boolean('line_support')->default(false)->comment('LINEサポート');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'free_estimate',
                'regular_work', 
                'reviews_reputation',
                'case_studies',
                'liability_insurance',
                'workers_insurance',
                'certified_staff',
                'corporate_support',
                'weekend_support',
                'night_support',
                'online_consultation',
                'email_support',
                'line_support'
            ]);
        });
    }
};
