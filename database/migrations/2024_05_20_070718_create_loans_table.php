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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loaner_id')->constrained('loaners')->cascadeOnDelete();
            $table->foreignId('loan_type_id')->constrained('loan_types')->cascadeOnDelete();
            $table->decimal('amount', 10, 2);
            $table->integer('term');
            $table->date('loan_start_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
