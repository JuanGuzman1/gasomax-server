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
        Schema::create('provider_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('bankAccount')->nullable();
            $table->string('clabe')->nullable();
            $table->boolean('primary')->default(false);
            $table->unsignedInteger('bank_id');
            $table->unsignedInteger('provider_id');
            $table->timestamps();

            $table->foreign('bank_id')->references('id')->on('banks');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_accounts');
    }
};
