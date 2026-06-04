<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('model')->nullable();
            $table->enum('category', ['router', 'onu', 'cable', 'switch', 'splitter', 'other'])->default('other');
            $table->integer('total_stock')->default(0);
            $table->integer('available_stock')->default(0);
            $table->integer('assigned_stock')->default(0);
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};