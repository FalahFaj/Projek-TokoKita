<?php
// database/migrations/xxxx_xx_xx_000006_create_transaction_details_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('restrict');

            // Product info (snapshot at time of transaction)
            $table->string('product_name');
            $table->string('product_sku');
            $table->decimal('unit_price', 15, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 15, 2);

            $table->timestamps();

            // Indexes
            $table->index('transaction_id');
            $table->index('product_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transaction_details');
    }
};
