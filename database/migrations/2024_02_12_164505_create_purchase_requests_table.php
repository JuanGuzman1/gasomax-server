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
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->float('paymentAmount');
            $table->unsignedBigInteger('quote_id')->nullable();
            $table->unsignedBigInteger('petitioner_id');
            $table->enum('status', ['pending', 'approved', 'paid', 'rejected'])->default('pending');
            $table->date('paymentDate')->nullable();
            $table->boolean('totalPaymentApproved')->default(false);
            $table->boolean('totalPaymentModified')->default(false);
            $table->timestamps();

            $table->foreign('quote_id')->references('id')->on('quotes');
            $table->foreign('petitioner_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};
