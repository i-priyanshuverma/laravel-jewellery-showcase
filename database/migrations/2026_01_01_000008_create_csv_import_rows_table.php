<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('csv_import_rows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('csv_import_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('row_number');
            $table->json('data');
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending')->index();
            $table->json('errors')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('csv_import_rows');
    }
};
