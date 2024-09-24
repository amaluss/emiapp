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
        Schema::create('loan_details', function (Blueprint $table) {
            $table->id();  // Primary key (auto-increment)
            $table->unsignedBigInteger('clientid'); // Client ID (assuming it's a foreign key or an integer)
            $table->integer('num_of_payment'); // Number of payments (EMIs)
            $table->date('first_payment_date'); // Start date of payment (YYYY-MM-DD)
            $table->date('last_payment_date'); // End date of payment (YYYY-MM-DD)
            $table->decimal('loan_amount', 15, 2); // Total loan amount (sum of all EMIs)
            $table->timestamps(); // Created at and updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_details');
    }
};
