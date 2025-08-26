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
        Schema::create('missing_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('missing_order_id')->constrained('missing_orders')->onDelete('cascade');
            $table->string('product_title');
            $table->string('price');
            $table->string('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missing_order_items');
    }
};
