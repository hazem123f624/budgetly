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
        Schema::table('debts', function (Blueprint $table) {
            $table->decimal('max_payment', 10, 2)->nullable()->after('amount')->comment('الحد الأقصى للسداد');
            $table->string('entity_name')->nullable()->after('source')->comment('اسم الجهة');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->dropColumn(['max_payment', 'entity_name']);
        });
    }
};
