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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            // $table->string('matric_number')->unique();
            // $table->foreignId('department_id')->constrained()->onDelete('cascade'); // Foreign key to departments table
            // $table->foreignId('organization_id')->constrained()->onDelete('cascade'); // Foreign key to organizations table
            // $table->string('duration');
            // $table->timestamps();
            $table->string('name');
            $table->string('matric_number')->unique();
            $table->string('email')->unique();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('organization_id');
            $table->enum('duration', ['3 months', '6 months'])->default('3 months');
            $table->string('password');
            $table->enum('role', ['student', 'admin','supervisor'])->default('student');
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
