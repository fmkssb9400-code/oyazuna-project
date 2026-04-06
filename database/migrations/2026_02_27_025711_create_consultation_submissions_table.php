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
        Schema::create('consultation_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 100);
            $table->string('ip_address', 45);
            $table->string('user_agent', 500)->nullable();
            $table->json('form_data')->nullable(); // フォーム送信データ
            $table->timestamp('submitted_at');
            $table->timestamps();

            $table->index(['submitted_at']);
            $table->index(['session_id', 'submitted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultation_submissions');
    }
};
