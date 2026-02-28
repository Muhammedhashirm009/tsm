<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->string('receipt_no')->nullable()->after('id');
            $table->foreignId('created_by')->nullable()->after('description')->constrained('users')->onDelete('set null');
        });

        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('voucher_no')->nullable()->after('id');
            $table->foreignId('created_by')->nullable()->after('description')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['receipt_no', 'created_by']);
        });
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn(['voucher_no', 'created_by']);
        });
    }
};
