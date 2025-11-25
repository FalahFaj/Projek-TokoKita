<?php
// database/migrations/xxxx_xx_xx_000004_create_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->string('barcode')->unique()->nullable();
            $table->text('description')->nullable();

            // Foreign keys
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->foreignId('supplier_id')->constrained()->onDelete('restrict');

            // Pricing
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('selling_price', 15, 2);
            $table->decimal('profit_margin', 8, 2)->default(0);

            // Inventory
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(5);
            $table->integer('max_stock')->default(100);
            $table->string('unit')->default('pcs');

            // Media
            $table->string('image')->nullable();

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_available')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['sku', 'barcode']);
            $table->index('category_id');
            $table->index('supplier_id');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
