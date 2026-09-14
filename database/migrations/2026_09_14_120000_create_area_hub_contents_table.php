<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * area_hub/custom配下にBladeファイルとして直書きしていたハブページ統合コンテンツを
     * 管理画面(Filament)から編集できるようDB化する。
     */
    public function up(): void
    {
        Schema::create('area_hub_contents', function (Blueprint $table) {
            $table->id();
            $table->string('area_slug');
            $table->string('hub_slug');
            $table->string('title')->nullable();
            $table->string('meta_description')->nullable();
            $table->longText('content')->nullable();
            $table->timestamps();

            $table->unique(['area_slug', 'hub_slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('area_hub_contents');
    }
};
