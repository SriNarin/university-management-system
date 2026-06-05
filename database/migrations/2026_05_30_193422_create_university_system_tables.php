<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // NOTE: If the default Laravel user migration already ran, we modify or replace it cleanly.
        // To prevent table collision crash loops, we drop the basic default stub table first if it exists.
        Schema::dropIfExists('users');

        // 1. Core Authentication Framework Engine Table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'faculty_manager', 'study_office', 'teacher', 'student'])->default('student');
            $table->boolean('is_active')->default(true);
            $table->string('lang_preference')->default('en');
            $table->json('permissions_matrix')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // 2. High-Level Academic Faculty Structural Table
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_kh');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Sub-Faculty Department Core Model Layout Table
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained()->onDelete('cascade');
            $table->string('name_en');
            $table->string('name_kh');
            $table->timestamps();
        });

        // 4. Academic Structure Metadata Layer Component Table
        Schema::create('academic_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('generation'); 
            $table->enum('academic_level', ['bachelor', 'master', 'phd']);
            $table->enum('year_progress', ['foundation', 'year_1', 'year_2', 'year_3', 'year_4', 'graduated']);
            $table->timestamps();
        });

        // 5. Educational Classes Storage Container Table
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_structure_id')->constrained()->onDelete('cascade');
            $table->string('class_code')->unique();
             $table->enum('semester', ['semester_1', 'semester_2','summer', 
                'winter', 
                'autumn', 
                'spring', 
                'fall', 
                'other'
                ]);
            $table->enum('shift', ['morning', 'afternoon', 'evening', 'weekend', 'full_day', 'online', 'other']);
            $table->string('room_number');
            $table->unsignedBigInteger('teacher_id')->nullable()->after('id');
            $table->boolean('is_teacher_timetable_published')->default(false)->after('teacher_id');
            $table->boolean('is_timetable_published')->default(false);
            $table->timestamp('timetable_published_at')->nullable();
            $table->timestamps();
        });

        // 6. Curriculum Subject Blueprint Design Catalog Table
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('subject_code')->unique();
            $table->string('title_en');
            $table->string('title_kh');
            $table->integer('credits');
            $table->timestamps();
        });

        // 7. Student Profiles Registry Layout Table
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->string('student_id_card')->unique();
            $table->integer('age');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('phone_number');
            $table->text('current_address');
            $table->timestamps();
        });

        // 8. Class Allocation Student Mapping Layout Interceptor Table
       Schema::create('class_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Points to the Student User
            
            // Financial Fields (Tuition vs Scholarship tracking)
            $table->enum('enrollment_type', ['paid', 'scholarship'])->default('paid');
            $table->string('scholarship_type')->nullable(); // e.g., 'Full 100%', '50%', 'MoEYS Government'
            $table->decimal('amount_paid', 10, 2)->default(0.00);
            
            // Role-Based Validation Workflows
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by_manager_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->timestamps();
        });

        // 9. Class Schedule Timeline Configuration Engine Table
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
            // $table->foreignId('academic_structure_id')->constrained('academic_structures')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('subject_code');
            $table->string('subject_name_en');
            $table->string('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });

        // 10. Student Attendance Real-Time Tracking Storage Matrix Table
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->date('teaching_date');
            $table->enum('status', ['present', 'absent', 'late', 'permission']);
            $table->timestamps();
        });

        // 11. Multi-Task Academic Assessment Matrix Blueprint Rules Table
        Schema::create('task_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_schedule_id')->constrained()->onDelete('cascade');
            $table->enum('task_type', [
                'attendance_weight',  
                'quiz', 
                'assignment', 
                'homework', 
                'midterm', 
                'project', 
                'final_exam', 
                're_exam']);
            $table->string('title');
            $table->string('attachment_file_path')->nullable();
            $table->float('max_score_threshold')->default(100);
            $table->dateTime('deadline_cut_off');
            $table->json('qcm_blueprint')->nullable();

            $table->timestamps();
        });

        // 12. Assessment Submissions and Grades Calculation Table
        Schema::create('assessment_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_assessment_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->float('secured_score')->default(0);
            $table->string('grade_letter', 5)->default('F');
            $table->string('teacher_feedback')->nullable();
            $table->text('submission_notes')->nullable();
            
            $table->string('attachment_file_path')->nullable();
            $table->json('student_qcm_responses')->nullable();
            $table->boolean('is_locked_by_office')->default(false);
            $table->enum('manager_approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });

        // 13. Digital Lesson Materials Vault Storage Distribution Matrix Table
        Schema::create('lesson_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_schedule_id')->constrained()->onDelete('cascade');
            $table->string('lecture_title_topic');
            $table->string('resource_attachment_path');
            $table->boolean('is_visible_to_students')->default(true);
            $table->timestamps();
        });

        // 14. Event System Broadcast Table
        Schema::create('system_events', function (Blueprint $table) {
            $table->id();
            $table->string('event_title');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('scope_restriction', ['global', 'faculty_only', 'staff_only'])->default('global');
            $table->timestamps();
        });

        // 15. Institutional Corporate Announcement Layout Channels Table
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content')->nullable();
            $table->string('banner_image_path')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->json('target_roles')->nullable();
            $table->boolean('is_pinned_to_top')->default(false)->nullable();
            
            $table->timestamps();
        });

        // 16. Custom System Internal Activity Logs Audit Trail Table
        Schema::create('custom_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('actor_role_context');
            $table->string('action_performed');
            $table->string('target_resource_type');
            $table->text('logged_payload_summary');
            $table->timestamps();
        });

        // 17. Multi-Channel Cross System Operational Notification System Alert Logs Table
        Schema::create('system_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->enum('recipient_type', ['role', 'individual'])->nullable();
            $table->string('receiver');
            $table->string('message_subject')->nullable();
            $table->text('message_body');
            $table->string('attachment_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_notifications');
        Schema::dropIfExists('custom_activity_logs');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('system_events');
        Schema::dropIfExists('lesson_materials');
        Schema::dropIfExists('assessment_submissions');
        Schema::dropIfExists('task_assessments');
        Schema::dropIfExists('attendances');
        Schema::dropIfExists('class_schedules');
        Schema::dropIfExists('class_user');
        Schema::dropIfExists('student_profiles');
        Schema::dropIfExists('subjects');
        Schema::dropIfExists('school_classes');
        Schema::dropIfExists('academic_structures');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('faculties');
        Schema::dropIfExists('users');
    }
};