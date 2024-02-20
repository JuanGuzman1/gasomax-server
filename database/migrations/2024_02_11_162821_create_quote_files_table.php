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
        Schema::create('quote_files', function (Blueprint $table) {
            $table->id();
            $table->string('localName');
            $table->string('name');
            $table->string('tag');
            $table->text('description')->nullable();
            $table->string('extension');
            $table->float('size');
            $table->string('path');
            $table->unsignedBigInteger('quote_id');
            $table->string('provider')->nullable();
            $table->float('amount')->default(0);
            $table->date('deliveryDate')->nullable();
            $table->boolean('selectedQuoteFile')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quote_files');
    }
};
