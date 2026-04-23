<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lbebooks', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable();
            $table->string("author")->nullable();
            $table->string("dept")->nullable();
            $table->boolean("pdf_file")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lbebooks');
    }
};
