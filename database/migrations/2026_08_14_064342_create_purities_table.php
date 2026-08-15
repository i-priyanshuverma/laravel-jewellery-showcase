<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('metal_id')->constrained('metals')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('value', 50);
            $table->unsignedInteger('sort_order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            $table->unique(['metal_id', 'value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purities');
    }
};
