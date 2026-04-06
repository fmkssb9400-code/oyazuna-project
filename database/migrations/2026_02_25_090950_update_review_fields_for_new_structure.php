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
            // Remove cost_range field
            $table->dropColumn('cost_range');
            
            // Remove rating field (overall rating)
            $table->dropColumn('rating');
            
            // Remove old body field
            $table->dropColumn('body');
            
            // Add continue_request field
            $table->string('continue_request')->nullable()->after('usage_period');
            
            // Add new content fields
            $table->text('good_points')->after('continue_request');
            $table->text('improvement_points')->nullable()->after('good_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Restore removed fields
            $table->string('cost_range')->nullable()->after('project_scale');
            $table->integer('rating')->after('usage_period');
            $table->text('body')->after('rating');
            
            // Remove new fields
            $table->dropColumn(['continue_request', 'good_points', 'improvement_points']);
        });
    }
};
