<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahal_donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_id')->nullable()->constrained('homes')->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->date('date');
            $table->string('donor_name')->nullable();
            $table->string('payment_method')->default('Cash');
            $table->text('description')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahal_donations');
    }
};
