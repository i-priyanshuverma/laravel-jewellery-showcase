<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('variant_stones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')->constrained('product_variants')->cascadeOnDelete();
            $table->foreignId('stone_type_id')->constrained('stone_types')->cascadeOnDelete();
            $table->decimal('carat_weight', 6, 3)->nullable();
            $table->string('clarity', 50)->nullable();
            $table->string('setting_type', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('variant_stones');
    }
};
