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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('imported_file_id');
            $table->date('order_date')->nullable();
            $table->string('channel')->nullable();
            $table->string('sku')->nullable();
            $table->string('item_description')->nullable();
            $table->string('origin')->nullable();
            $table->string('so_num')->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->decimal('profit', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('imported_file_id')->references('id')->on('imported_files')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
