<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('creditors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('phone')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('debts', function (Blueprint $table) {
            $table->foreignId('creditor_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('debts', function (Blueprint $table) {
            $table->dropForeign(['creditor_id']);
            $table->dropColumn('creditor_id');
        });
        Schema::dropIfExists('creditors');
    }
};
