<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->string('book_no')->nullable()->after('name');
            $table->string('receipt_prefix')->nullable()->after('description');
            $table->integer('receipt_start_no')->default(1)->after('receipt_prefix');
            $table->integer('receipt_end_no')->nullable()->after('receipt_start_no');
            $table->integer('receipt_current_no')->default(0)->after('receipt_end_no');
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn(['book_no', 'receipt_prefix', 'receipt_start_no', 'receipt_end_no', 'receipt_current_no']);
        });
    }
};
