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
            $table->string('supervisor_name')->nullable()->after('is_featured');
            $table->string('supervisor_title')->nullable()->after('supervisor_name');
            $table->text('supervisor_description')->nullable()->after('supervisor_title');
            $table->string('supervisor_avatar')->nullable()->after('supervisor_description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['supervisor_name', 'supervisor_title', 'supervisor_description', 'supervisor_avatar']);
        });
    }
};
