<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('static_pages', function (Blueprint $table) {
            $table->json('custom_html_blocks')->nullable()->after('supervisor_avatar');
            $table->longText('custom_css')->nullable()->after('custom_html_blocks');
        });
    }

    public function down()
    {
        Schema::table('static_pages', function (Blueprint $table) {
            $table->dropColumn(['custom_html_blocks', 'custom_css']);
        });
    }
};
