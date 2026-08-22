<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('book_id')->constrained('books')->restrictOnDelete();
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->foreign('processed_by')->references('id')->on('users')->nullOnDelete();
            $table->string('borrow_code', 50)->unique();
            $table->date('borrow_date');
            $table->date('due_date');
            $table->dateTime('returned_at')->nullable();
            $table->enum('status', ['pending', 'borrowed', 'returned', 'overdue'])->default('pending');
            $table->timestamps();

            $table->index('user_id');
            $table->index('book_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
