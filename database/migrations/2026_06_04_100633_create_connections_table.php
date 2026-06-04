<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('restrict');
            $table->foreignId('area_id')->constrained()->onDelete('restrict');
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->enum('connection_type', ['fiber', 'wireless', 'dsl'])->default('fiber');
            $table->enum('status', ['active', 'suspended', 'terminated'])->default('active');
            $table->date('installation_date');
            $table->foreignId('technician_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('connections');
    }
};