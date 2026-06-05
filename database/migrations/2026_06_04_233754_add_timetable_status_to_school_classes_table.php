<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('school_classes', function (Blueprint $table) {
            // Tracks if the schedule matrix has been frozen and published
            $table->boolean('is_timetable_published')->default(false);
            $table->timestamp('timetable_published_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('school_classes', function (Blueprint $table) {
            $table->dropColumn(['is_timetable_published', 'timetable_published_at']);
        });
    }
};