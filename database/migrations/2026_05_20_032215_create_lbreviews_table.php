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
        Schema::create('lbreviews', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger("lbteacher_id")->nullable();
              $table->string("rating")->nullable();
               $table->string("review")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lbreviews');
    }
};
