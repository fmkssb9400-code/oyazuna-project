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
        Schema::create('company_prefecture', function (Blueprint $table) {
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->foreignId('prefecture_id')->constrained()->onDelete('cascade');
            $table->primary(['company_id', 'prefecture_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_prefecture');
    }
};
