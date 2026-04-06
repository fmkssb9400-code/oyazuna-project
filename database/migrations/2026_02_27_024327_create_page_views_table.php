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
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('url', 500);
            $table->string('page_type', 50)->nullable(); // 'article', 'company', 'home', etc.
            $table->unsignedBigInteger('article_id')->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->string('ip_address', 45);
            $table->string('session_id', 100);
            $table->timestamp('viewed_at');
            $table->timestamps();

            $table->index(['article_id', 'viewed_at']);
            $table->index(['page_type', 'viewed_at']);
            $table->index(['url', 'viewed_at']);
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
