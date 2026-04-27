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
        Schema::create('lbstudents', function (Blueprint $table) {
            $table->id();
            $table->string("fullName")->nullable();
            $table->string("email")->nullable();
            $table->integer("phone")->nullable();
            $table->integer("roll_no")->nullable();
            $table->string("program")->nullable();
            $table->string("session")->nullable();
            $table->string("password")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lbstudents');
    }
};
