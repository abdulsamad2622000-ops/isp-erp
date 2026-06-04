<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suspensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('connection_id')->constrained()->onDelete('cascade');
            $table->enum('reason', ['non_payment', 'request', 'violation', 'other'])->default('non_payment');
            $table->date('suspension_date');
            $table->date('reconnection_date')->nullable();
            $table->enum('status', ['suspended', 'reconnected'])->default('suspended');
            $table->foreignId('actioned_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suspensions');
    }
};