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
        Schema::create('quote_requests', function (Blueprint $table) {
            $table->id();
            $table->string('public_id')->unique();
            $table->enum('type', ['bulk', 'single'])->default('bulk');
            $table->enum('client_kind', ['corp', 'personal']);
            $table->string('company_name')->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->foreignId('prefecture_id')->constrained();
            $table->string('city_text')->nullable();
            $table->foreignId('building_type_id')->constrained();
            $table->integer('floors');
            $table->enum('glass_area_type', ['small', 'medium', 'large']);
            $table->foreignId('service_category_id')->constrained();
            $table->foreignId('preferred_service_method_id')->nullable()->constrained('service_methods');
            $table->enum('preferred_timing', ['urgent', 'this_week', 'this_month', 'undecided']);
            $table->text('note')->nullable();
            $table->json('attachments')->nullable();
            $table->enum('status', ['new', 'sent', 'done', 'invalid'])->default('new');
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_requests');
    }
};
