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
        Schema::create('doctor_records', function (Blueprint $table) {
            $table->id();
            $table->string('profile_image')->nullable();
            $table->enum('title', ['Dr.', 'Mr.', 'Mrs.', 'Ms.']);
            $table->string('full_name');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('password');
            $table->text('address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_records');
    }
};
