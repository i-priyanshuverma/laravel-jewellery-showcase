<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('sku')->unique()->index();
            $table->decimal('price', 10, 2)->index();
            $table->unsignedInteger('stock')->default(0)->index();
            $table->string('metal')->nullable()->index();
            $table->string('purity')->nullable()->index();
            $table->string('colour')->nullable()->index();
            $table->string('size')->nullable()->index();
            $table->decimal('weight', 8, 3)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
