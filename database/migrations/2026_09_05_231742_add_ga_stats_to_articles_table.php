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
        Schema::table('articles', function (Blueprint $table) {
            $table->unsignedBigInteger('ga_total_views')->default(0)->after('is_featured');
            $table->unsignedBigInteger('ga_daily_views')->default(0)->after('ga_total_views');
            $table->date('ga_stats_date')->nullable()->after('ga_daily_views');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['ga_total_views', 'ga_daily_views', 'ga_stats_date']);
        });
    }
};
