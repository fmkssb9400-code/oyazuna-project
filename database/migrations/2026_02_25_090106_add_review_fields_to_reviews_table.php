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
            $table->string('company_name')->nullable()->after('reviewer_name');
            $table->string('service_category')->nullable()->after('company_name');
            $table->string('building_type')->nullable()->after('service_category');
            $table->string('project_scale')->nullable()->after('building_type');
            $table->string('cost_range')->nullable()->after('project_scale');
            $table->string('usage_period')->nullable()->after('cost_range');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn([
                'company_name',
                'service_category', 
                'building_type',
                'project_scale',
                'cost_range',
                'usage_period'
            ]);
        });
    }
};
