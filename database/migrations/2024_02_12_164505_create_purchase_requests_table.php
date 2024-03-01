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
            $table->float('totalAmount');
            $table->float('paymentAmount');
            $table->unsignedBigInteger('quote_id')->nullable();
            $table->unsignedBigInteger('petitioner_id');
            $table->unsignedBigInteger('provider_id')->nullable();
            $table->unsignedBigInteger('provider_account_id')->nullable();
            $table->boolean('paymentWithoutInvoice')->default(false);
            $table->enum('status', ['pending', 'approved', 'paid', 'rejected'])->default('pending');
            $table->boolean('fromQuote')->default(false);
            $table->date('paymentDate')->nullable();
            $table->boolean('totalPaymentApproved')->default(false);
            $table->boolean('totalPaymentModified')->default(false);
            $table->float('balance')->default(0);
            $table->unsignedBigInteger('purchase_request_pending_id')->nullable();
            $table->timestamps();

            $table->foreign('quote_id')->references('id')->on('quotes');
            $table->foreign('petitioner_id')->references('id')->on('users');
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->foreign('provider_account_id')->references('id')->on('provider_accounts');
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
