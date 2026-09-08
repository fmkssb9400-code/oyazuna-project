<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * 数字だけのslugを持つ会社の、旧URL(/companies/{数字})を301リダイレクトするために
     * 元のslug値を退避しておくカラム。
     */
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('legacy_slug')->nullable()->after('slug')->index();
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('legacy_slug');
        });
    }
};
