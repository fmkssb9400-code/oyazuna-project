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
            $table->json('tags')->nullable()->after('service_categories')->comment('会社の特徴・強みタグ（柔軟に追加可能）');
            $table->string('logo_path')->nullable()->after('tags')->comment('会社ロゴ画像のパス');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['tags', 'logo_path']);
        });
    }
};
