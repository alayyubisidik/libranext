<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrowing_id')->unique()->constrained('borrowings')->restrictOnDelete();
            $table->decimal('rate_per_day', 10, 2)->default(500);
            $table->unsignedInteger('overdue_days')->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->enum('status', ['unpaid', 'paid', 'waived'])->default('unpaid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
