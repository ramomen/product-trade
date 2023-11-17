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
        Schema::create('offers', function (Blueprint $table) {
            $table->id()->startingValue(1000);
            $table->foreignId('product_id');
            $table->foreignId('seller_id');
            $table->decimal('price', 10, 2);
            $table->enum('condition', ['new', 'used']);
            $table->enum('availability', ['in stock', 'out of stock']);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
