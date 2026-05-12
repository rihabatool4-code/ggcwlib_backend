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
        Schema::create('lbbookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger("lbstudent_id")->nullable();
            $table->unsignedInteger("lbteacher_id")->nullable();
            $table->unsignedInteger("lbbook_id")->nullable();
            $table->string("issuedby")->nullable();
            $table->string("status")->nullable();
            $table->date("issue_date")->nullable();
            $table->date("due_date")->nullable();
            $table->integer("fine")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lbbookings');
    }
};
