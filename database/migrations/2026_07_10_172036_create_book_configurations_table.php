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
        Schema::create('book_configurations', function (Blueprint $table) {
            $table->id();

            $table->integer("fine_per_day");

            $table->integer("max_issue_days");

            $table->integer("reservation_expiry_hours")->default(24);

            $table->integer("max_books_student");

            $table->integer("max_books_staff");

            $table->integer("lost_book_fine");

            $table->integer("damaged_book_fine");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_configurations');
    }
};