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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedInteger('petitioner_id');
            $table->unsignedInteger('quote_concept_id');
            $table->text('description')->nullable();
            $table->integer('numProviders')->nullable();
            $table->string('recommendedProviders')->nullable();
            $table->string('line');
            $table->string('unit');
            $table->boolean('rejectQuotes')->default(false);
            $table->float('approvedAmount')->default(0);
            $table->unsignedInteger('provider_id')->nullable();
            $table->unsignedInteger('provider_account_id')->nullable();
            $table->boolean('paymentWithoutInvoice')->default(false);
            $table->enum('status', ['sent', 'inprogress', 'approved', 'rejected'])->default('sent');
            $table->timestamps();


            $table->foreign('petitioner_id')->references('id')->on('users');
            $table->foreign('quote_concept_id')->references('id')->on('quote_concepts');
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->foreign('provider_account_id')->references('id')->on('provider_accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};
