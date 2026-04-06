<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('static_pages', function (Blueprint $table) {
            $table->string('supervisor_name')->nullable()->after('published_at');
            $table->string('supervisor_title')->nullable()->after('supervisor_name');
            $table->text('supervisor_description')->nullable()->after('supervisor_title');
            $table->string('supervisor_avatar')->nullable()->after('supervisor_description');
        });
    }

    public function down()
    {
        Schema::table('static_pages', function (Blueprint $table) {
            $table->dropColumn(['supervisor_name', 'supervisor_title', 'supervisor_description', 'supervisor_avatar']);
        });
    }
};
