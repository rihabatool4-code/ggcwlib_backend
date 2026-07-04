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
        Schema::create('lbnotifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("lbstudent_id")->nullable();
            $table->unsignedInteger("lbteacher_id")->nullable();
            $table->unsignedInteger("lbadmin_id")->nullable();
            $table->string("title")->nullable();
            $table->string("subtitle")->nullable();
            $table->string("for")->nullable();
            $table->string('status')->default('unread');
            $table->string('type')->nullable();
            $table->json('detail')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lbnotifications');
    }
};