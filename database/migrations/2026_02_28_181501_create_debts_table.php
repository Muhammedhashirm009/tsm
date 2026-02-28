<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->string('person_name');
            $table->decimal('amount', 12, 2);
            $table->date('date');
            $table->enum('type', ['borrowed', 'lent'])->default('borrowed');
            $table->enum('status', ['pending', 'partial', 'paid'])->default('pending');
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('debts');
    }
};
