<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distribution_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distribution_event_id')->constrained('distribution_events')->cascadeOnDelete();
            $table->foreignId('home_id')->constrained('homes')->cascadeOnDelete();
            $table->boolean('token_given')->default(false);
            $table->timestamp('token_given_at')->nullable();
            $table->boolean('collected')->default(false);
            $table->timestamp('collected_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['distribution_event_id', 'home_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribution_records');
    }
};
