<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subject_final_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('class_schedule_id')->constrained('class_schedules')->cascadeOnDelete();
            
            // Accumulated assessment scores
            $table->decimal('total_accumulated_score', 5, 2)->default(0.00);
            $table->string('final_grade_letter', 3)->nullable();
            
            // Workflow States
            $table->boolean('is_submitted_to_office')->default(false);
            $table->timestamp('submitted_to_office_at')->nullable();
            $table->boolean('is_approved_by_manager')->default(false);
            $table->timestamp('approved_by_manager_at')->nullable();
            
            $table->timestamps();
            
            // Prevent duplicate entries for the same student in the same class schedule slot
            $table->unique(['student_id', 'class_schedule_id'], 'student_schedule_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subject_final_grades');
    }
};