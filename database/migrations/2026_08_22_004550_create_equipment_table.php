<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
Schema::create('equipment', function (Blueprint $table) {
    $table->id();
    $table->string('asset_tag')->unique();
    $table->string('name');
    $table->string('category');
    $table->integer('quantity')->default(1); // ENSURE THIS IS PRESENT
    $table->string('serial_number')->nullable();
    $table->enum('status', ['Available', 'Assigned', 'In Maintenance', 'Decommissioned'])->default('Available');
    $table->enum('condition', ['Excellent', 'Good', 'Fair', 'Damaged', 'Expired'])->default('Good');
    $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
    $table->date('expiration_date')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
