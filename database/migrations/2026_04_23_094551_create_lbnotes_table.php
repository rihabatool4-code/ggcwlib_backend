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
       Schema::create('lbnotes', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable();
            $table->string("subject")->nullable();
            $table->string("pdf_file")->nullable();
            $table->unsignedBigInteger("Lbteacher_id")->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lbnotes');
    }
};
