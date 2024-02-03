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
            $table->unsignedInteger('provider_id');
            $table->unsignedInteger('petitioner_id');
            $table->boolean('extraordinary');
            $table->string('station');
            $table->string('business');
            $table->enum('paymentMethod', ['transference', 'check', 'cash']);
            $table->enum('status', ['pending', 'approved', 'paid', 'rejected'])->default('pending');
            $table->date('paymentDate')->nullable();
            $table->boolean('pettyCash');
            $table->timestamps();

            $table->foreign('provider_id')->references('id')->on('providers');
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
