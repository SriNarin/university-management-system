<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SchoolClass;
use App\Models\SubjectFinalGrade;
use Illuminate\Http\Request;

class TranscriptPrintController extends Controller
{
    public function print($studentId, $classId)
    {
        // 1. Resolve Student and Class profiles securely
        $student = User::findOrFail($studentId);
        $schoolClass = SchoolClass::with('academicStructure.department.faculty')->findOrFail($classId);

        // 2. Pull ALL compiled final subject course rows for this student inside this specific class slot
        $grades = SubjectFinalGrade::where('student_id', $studentId)
            ->whereHas('classSchedule', function ($query) use ($classId) {
                $query->where('school_class_id', $classId);
            })
            ->with('classSchedule')
            ->get();

        // 3. Process numerical GPA score conversions and total summation tracks
        $totalGpaPoints = 0;
        $totalSubjectsCount = $grades->count();

        foreach ($grades as $grade) {
            // Convert the standard recorded Letter Grades into standard 4.0 scale points
            switch (trim($grade->final_grade_letter)) {
                case 'A+':  $gpa = 4.00; break;
                case 'A':  $gpa = 4.00; break;
                case 'B+': $gpa = 3.50; break;
                case 'B':  $gpa = 3.50; break;
                case 'C+': $gpa = 3.00; break;
                case 'C':  $gpa = 3.00; break;
                case 'D':  $gpa = 2.50; break;
                case 'E':  $gpa = 2.30; break;
                default:   $gpa = 0.00; break;
            }
            // Bind the calculated numerical point directly to the object loop array memory instance
            $grade->calculated_gpa_point = $gpa;
            $totalGpaPoints += $gpa;
        }

        // Calculate final CGPA average safely to protect against division-by-zero crashes
        $finalCumulativeGpa = $totalSubjectsCount > 0 ? round($totalGpaPoints / $totalSubjectsCount, 2) : 0.00;

        // 4. Return view layout tracking maps to the browser canvas layout template
        return view('reports.transcript-certificate', [
            'student' => $student,
            'schoolClass' => $schoolClass,
            'grades' => $grades,
            'cgpa' => number_format($finalCumulativeGpa, 2),
            'issueDate' => now()->format('d-M-Y')
        ]);
    }
}