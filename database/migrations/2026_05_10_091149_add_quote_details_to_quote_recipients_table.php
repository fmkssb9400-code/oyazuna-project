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
        Schema::table('quote_recipients', function (Blueprint $table) {
            $table->string('region')->nullable()->after('company_id')->comment('地域');
            $table->string('building_type')->nullable()->after('region')->comment('建物種別');
            $table->string('floor_range')->nullable()->after('building_type')->comment('階数帯');
            $table->string('order_type')->nullable()->after('floor_range')->comment('発注形態');
            $table->string('contract_type')->nullable()->after('order_type')->comment('契約形態');
            $table->json('quote_items')->nullable()->after('contract_type')->comment('見積もり項目');
            $table->decimal('total_amount', 12, 2)->nullable()->after('quote_items')->comment('見積もり総額');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quote_recipients', function (Blueprint $table) {
            $table->dropColumn([
                'region',
                'building_type', 
                'floor_range',
                'order_type',
                'contract_type',
                'quote_items',
                'total_amount'
            ]);
        });
    }
};
