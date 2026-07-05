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
        Schema::create('lbflashcards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lbstudent_id')->nullable();
            $table->unsignedBigInteger('lbteacher_id')->nullable();
 
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('type')->default('Notes'); // e.g. "Notes" | "PDF"
            $table->text('descryption')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lbflashcards');
    }
};
